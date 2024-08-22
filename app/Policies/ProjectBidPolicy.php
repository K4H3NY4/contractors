<?php

namespace App\Policies;

use App\Models\ProjectBid;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectBidPolicy
{
    public function update(User $user, ProjectBid $bid)
{
    return $user->id === $bid->user_id;
}

public function delete(User $user, ProjectBid $bid)
{
    return $user->id === $bid->user_id;
}

}
