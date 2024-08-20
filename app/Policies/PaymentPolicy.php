<?php

namespace App\Policies;

use App\Models\Payment;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PaymentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the payment.
     */
    public function view(User $user, Payment $payment)
    {
        return $user->projects->contains($payment->project_id);
    }

    /**
     * Determine whether the user can update the payment.
     */
    public function update(User $user, Payment $payment)
    {
        return $user->projects->contains($payment->project_id);
    }

    /**
     * Determine whether the user can delete the payment.
     */
    public function delete(User $user, Payment $payment)
    {
        return $user->projects->contains($payment->project_id);
    }
}
