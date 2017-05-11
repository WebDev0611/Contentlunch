<?php

namespace App\Services;

use App\Account;
use App\Content;
use Illuminate\Support\Facades\Auth;

class ContentService
{
    protected $content;

    public function __construct(Content $content)
    {
        $this->content = $content;
        $this->selectedAccount = Account::selectedAccount() ?: $account;
    }

    public function list()
    {
        return Auth::user()->isGuest()
            ? $this->guestContentList()
            : $this->userContentList();
    }

    protected function guestContentList()
    {

    }

    protected function userContentList()
    {
        $this->selectedAccount->cleanContentWithoutStatus();

        return [
            'countContent' => $this->selectedAccount->contents()->count(),
            'published' => $this->selectedAccount->contents()->published()->recentlyUpdated()->get(),
            'readyPublished' => $this->selectedAccount->contents()->readyToPublish()->recentlyUpdated()->get(),
            'written' => $this->selectedAccount->contents()->written()->recentlyUpdated()->get(),
            'connections' => $this->selectedAccount->connections()->active()->get(),
        ];
    }
}