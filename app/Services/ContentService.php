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
        $data = Auth::user()->isGuest()
            ? $this->guestContentList()
            : $this->userContentList();

        $data['connections'] = $this->connections();
        $data['countContent'] = $this->contentCount($data);

        return $data;
    }

    protected function guestContentList(array $filters = [])
    {
        $published = Auth::user()->guestContents()->published()->recentlyUpdated()->get();
        $readyPublished = Auth::user()->guestContents()->readyToPublish()->recentlyUpdated()->get();
        $written = Auth::user()->guestContents()->written()->recentlyUpdated()->get();

        return compact('published', 'readyPublished', 'written');
    }

    protected function userContentList(array $filters = [])
    {
        $this->selectedAccount->cleanContentWithoutStatus();

        $published = $this->selectedAccount->contents()->published()->recentlyUpdated()->get();
        $readyPublished = $this->selectedAccount->contents()->readyToPublish()->recentlyUpdated()->get();
        $written = $this->selectedAccount->contents()->written()->recentlyUpdated()->get();

        return compact('published', 'readyPublished', 'written');
    }

    protected function connections()
    {
        return $this->selectedAccount->connections()->active()->get();
    }

    protected function contentCount(array $data)
    {
        return collect($data)
            ->only('published', 'readyPublished', 'written')
            ->map(function($collection) { return $collection->count(); })
            ->sum();
    }

    public function recentContent()
    {
        return $this->selectedAccount
            ->contents()
            ->recentlyUpdated()
            ->take(10)
            ->get()
            ->map(function($content) {
                $content->author = $content->author();
                $content->title = $content->present()->title;

                return $content;
            });
    }

    public function inviteGuests(Content $content, $emails)
    {
        $emails->each(function($email) use ($content) {
            $this->sendGuestInvite($content, $email);
        });
    }

    protected function sendGuestInvite(Content $content, $email)
    {
        $data = [
            'link' => $this->createInviteUrl($content, $email),
            'user' => Auth::user(),
            'account' => $this->selectedAccount->proxyToParent(),
        ];

        Mail::send('emails.invite.guest', $data, function($message) use ($email) {
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