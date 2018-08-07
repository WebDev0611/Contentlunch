<?php

namespace App\Policies;

use App\Account;
use App\Idea;
use App\Limit;
use App\User;
use Carbon\Carbon;
use Illuminate\Auth\Access\HandlesAuthorization;

class IdeaPolicy extends BasePolicy
{
    use HandlesAuthorization;

    public function searchTopics(User $user)
    {
        if (!$this->account->hasLimit('topic_search')) {
            return true;
        }

        $topicSearches = $user->limits()->monthly()->whereName('topic_search')->count();
        $maxTopicSearches = $this->account->limit('topic_search');

        return $topicSearches < $maxTopicSearches;
    }

    public function searchTrends(User $user)
    {
        if (!$this->account->hasLimit('trend_search')) {
            return true;
        }

        $trendSearches = $user->limits()->monthly()->whereName('trend_search')->count();
        $maxTrendSearches = $this->account->limit('trend_search');

        return $trendSearches < $maxTrendSearches;
    }

    public function show(User $user, Idea $idea)
    {
        return $idea->user->id == $user->id || $idea->hasCollaborator($user) || $idea->account->hasUser($user);
    }

    public function update(User $user, Idea $idea)
    {
        return $idea->user->id == $user->id || $idea->hasCollaborator($user) || $idea->account->hasUser($user);
    }

    public function write(User $user, Idea $idea)
    {
        return $idea->user->id == $user->id || $idea->hasCollaborator($user) || $idea->account->hasUser($user);
    }

    public function create(User $user)
    {
        if (!$this->account->hasLimit('ideas_created')) {
            return true;
        }

        $ideas = $user->ideas()->monthly()->count();
        $maxIdeas = $this->account->limit('ideas_created');

        return $ideas < $maxIdeas;
    }
}
