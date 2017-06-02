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

    public function contentList()
    {
        return Auth::user()->isGuest()
            ? $this->guestContentList()
            : $this->userContentList();
    }

    protected function guestContentList()
    {
        return [
           'countContent' => Auth::user()->guestContents()->count(),
           'published' => Auth::user()->guestContents()->published()->recentlyUpdated()->get(),
           'readyPublished' => Auth::user()->guestContents()->readyToPublish()->recentlyUpdated()->get(),
           'written' => Auth::user()->guestContents()->written()->recentlyUpdated()->get(),
           'connections' => $this->selectedAccount->connections()->active()->WithoutGA()->get(),
        ];
    }

    protected function userContentList()
    {
        $this->selectedAccount->cleanContentWithoutStatus();

        return [
            'countContent' => $this->selectedAccount->contents()->count(),
            'published' => $this->selectedAccount->contents()->published()->recentlyUpdated()->get(),
            'readyPublished' => $this->selectedAccount->contents()->readyToPublish()->recentlyUpdated()->get(),
            'written' => $this->selectedAccount->contents()->written()->recentlyUpdated()->get(),
            'connections' => $this->selectedAccount->connections()->active()->WithoutGA()->get(),
        ];
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
                $content->due_date_human = $content->present()->dueDate;

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