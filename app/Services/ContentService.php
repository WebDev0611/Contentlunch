<?php

namespace App\Services;

use App\Account;
use App\AccountInvite;
use App\Content;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContentService
{
    protected $content;

    public function __construct(Content $content)
    {
        $this->content = $content;
        $this->selectedAccount = Account::selectedAccount() ?: $content->account;
    }

    public function contentList(array $filters = [])
    {
        $this->selectedAccount->cleanContentWithoutStatus();

        $data = [
            'published' => $this->publishedQuery($filters)->get(),
            'readyPublished' => $this->readyQuery($filters)->get(),
            'written' => $this->writtenQuery($filters)->get(),
            'connections' => $this->connections(),
        ];

        $data['countContent'] = $this->contentCount($data);

        return $data;
    }

    protected function filteredContent(array $filters = [])
    {
        return $this->guestOrUserContentQuery()
            ->filterByAuthor($filters)
            ->filterByCampaign($filters)
            ->filterByStatus($filters);
    }

    protected function guestOrUserContentQuery()
    {
        return Auth::user()->isGuest()
            ? Auth::user()->guestContents()->recentlyUpdated()
            : $this->selectedAccount->contents()->recentlyUpdated();
    }

    public function publishedQuery(array $filters = [])
    {
        return $this->filteredContent($filters)->published();
    }

    public function readyQuery(array $filters = [])
    {
        return $this->filteredContent($filters)->readyToPublish();
    }

    public function writtenQuery(array $filters = [])
    {
        return $this->filteredContent($filters)->written();
    }

    protected function connections()
    {
        return $this->selectedAccount->connections()->active()->withoutGA()->get();
    }

    protected function contentCount(array $data)
    {
        return collect($data)
            ->only('published', 'readyPublished', 'written')
            ->map(function($collection) { return $collection->count(); })
            ->sum();
    }

    public function inviteGuests(Content $content, $emails)
    {
        $emails->each(function($email) use ($content) {
            $this->sendGuestInvite($content, $email);
        });
    }

    protected function sendGuestInvite(Content $content, $email){

        $link = $this->createInviteUrl($content, $email);
        $user = Auth::user();
        $first_name = explode(" ", $user->name)[0];

        $data = [
            'link' => $link,
            'user' => $user,
            'first_name' => $first_name,
            'account' => $this->selectedAccount->proxyToParent(),
            'content' => $content
        ];

        Mail::send('emails.review_document', $data, function($message) use ($email) {
            $message->from("invites@contentlaunch.com", "Content Launch")
                ->to($email)
                ->subject('You\'ve been invited to Content Launch');
        });
    }

    private function createInviteUrl(Content $content, $email)
    {
        $accountInvite = $content->invites()->create([
            'email' => $email,
            'account_id' => $this->selectedAccount->id,
            'is_guest' => true,
        ]);

        return route('guests.create', $accountInvite);
    }
}