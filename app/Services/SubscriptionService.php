<?php

namespace App\Services;

use App\Models\Property;
use App\Models\PropertyBoost;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserSubscription;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function getActiveSubscription(User $user): ?UserSubscription
    {
        return $user->subscriptions()
            ->where('status', 'active')
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now())
            ->latest('ends_at')
            ->first();
    }

    public function assignPlan(User $user, SubscriptionPlan $plan, ?Carbon $startsAt = null): UserSubscription
    {
        $startsAt = $startsAt ?? now();
        $endsAt = (clone $startsAt)->addDays($plan->duration_days);

        return DB::transaction(function () use ($user, $plan, $startsAt, $endsAt) {
            $user->subscriptions()
                ->where('status', 'active')
                ->update(['status' => 'expired']);

            return $user->subscriptions()->create([
                'subscription_plan_id' => $plan->id,
                'status' => 'active',
                'remaining_listing_slots' => $plan->listing_limit,
                'remaining_boosts' => $plan->boost_credits,
                'auto_renew' => false,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'meta' => [
                    'featured_limit' => $plan->featured_limit,
                ],
            ]);
        });
    }

    public function hasListingSlot(UserSubscription $subscription): bool
    {
        return is_null($subscription->remaining_listing_slots) || $subscription->remaining_listing_slots > 0;
    }

    public function consumeListingSlot(UserSubscription $subscription): void
    {
        if (is_null($subscription->remaining_listing_slots)) {
            return;
        }

        $subscription->decrement('remaining_listing_slots');
    }

    public function hasBoostCredit(UserSubscription $subscription): bool
    {
        return ($subscription->remaining_boosts ?? 0) > 0;
    }

    public function consumeBoostCredit(UserSubscription $subscription): void
    {
        if ($subscription->remaining_boosts === null) {
            return;
        }

        $subscription->decrement('remaining_boosts');
    }

    public function scheduleBoost(User $user, Property $property, ?UserSubscription $subscription = null, int $hours = 24): PropertyBoost
    {
        $startsAt = now();
        $endsAt = (clone $startsAt)->addHours($hours);

        return PropertyBoost::create([
            'property_id' => $property->id,
            'user_id' => $user->id,
            'subscription_plan_id' => $subscription?->subscription_plan_id,
            'status' => 'active',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'amount' => 0,
            'currency' => 'USD',
            'meta' => [
                'source' => $subscription ? 'subscription' : 'one_time',
            ],
        ]);
    }
}

