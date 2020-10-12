<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{
    public function update(User $user, Topic $topic)
    {
         return $user->isAuthOf($topic);
    }

    public function destroy(User $user, Topic $topic)
    {
        return $user->isAuthOf($topic);

    }
}
