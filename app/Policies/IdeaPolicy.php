<?php

namespace App\Policies;

use App\Idea;
use App\Limit;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class IdeaPolicy
{
    use HandlesAuthorization;

    public function search(User $user)
    {
        $topicSearches = $user->limits()
            ->monthly()
            ->whereName('topic_search')
            ->count();

        $maxTopicSearches = Limit::whereName('topic_search')->first()->value;

        return $topicSearches <= $maxTopicSearches;
    }
}
