<?php

namespace App\Policies;

use App\Account;
use App\Idea;
use App\Limit;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class IdeaPolicy
{
    use HandlesAuthorization;

    public function before (User $user)
    {
        if (!Account::selectedAccount()->activeSubscriptions()->isEmpty())
        {
            return true;
        }
    }

    public function searchTopics(User $user)
    {
        $topicSearches = $user->limits()->monthly()->whereName('topic_search')->count();
        $maxTopicSearches = Limit::whereName('topic_search')->first()->value;

        return $topicSearches < $maxTopicSearches;
    }

    public function searchTrends(User $user)
    {
        $trendSearches = $user->limits()->monthly()->whereName('trend_search')->count();
        $maxTrendSearches = Limit::whereName('trend_search')->first()->value;

        return $trendSearches < $maxTrendSearches;
    }

    public function create(User $user)
    {
        $ideas = $user->ideas()->monthly()->count();
        $maxIdeas = Limit::whereName('ideas_created')->first()->value;

        return $ideas < $maxIdeas;
    }
}
