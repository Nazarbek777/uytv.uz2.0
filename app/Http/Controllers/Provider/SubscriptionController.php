<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\PropertyBoost;
use App\Models\SubscriptionPlan;
use App\Services\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    protected SubscriptionService $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function index()
    {
        $user = Auth::user();
        $plans = SubscriptionPlan::where('is_active', true)->orderBy('price')->get();
        $currentSubscription = $this->subscriptionService->getActiveSubscription($user);
        $properties = $user->properties()->latest()->get();
        $recentBoosts = PropertyBoost::with('property')
            ->where('user_id', $user->id)
            ->latest('starts_at')
            ->limit(5)
            ->get();

        return view('provider.subscriptions.index', compact(
            'user',
            'plans',
            'currentSubscription',
            'recentBoosts',
            'properties'
        ));
    }

    public function subscribe(Request $request, SubscriptionPlan $plan)
    {
        $user = Auth::user();

        if (! $plan->is_active) {
            return back()->with('error', __('Tanlangan tarif hozircha faol emas.'));
        }

        $subscription = $this->subscriptionService->assignPlan($user, $plan);

        return back()->with('success', __(':plan tarifiga obuna bo\'ldingiz. Muddati: :date gacha.', [
            'plan' => $plan->name,
            'date' => $subscription->ends_at->format('d.m.Y'),
        ]));
    }

    public function boost(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'property_id' => [
                'required',
                'integer',
                Rule::exists('properties', 'id')->where(fn ($query) => $query->where('user_id', $user->id)),
            ],
            'hours' => ['nullable', 'integer', 'min:6', 'max:168'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
        ]);

        $property = Property::findOrFail($validated['property_id']);
        $hours = $validated['hours'] ?? 24;
        $subscription = $this->subscriptionService->getActiveSubscription($user);

        if ($subscription && $this->subscriptionService->hasBoostCredit($subscription)) {
            $this->subscriptionService->consumeBoostCredit($subscription);
            $boost = $this->subscriptionService->scheduleBoost($user, $property, $subscription, $hours);

            return back()->with('success', __('E’lon :title :hours soatga TOPga ko\'tarildi.', [
                'title' => $property->title,
                'hours' => $hours,
            ]));
        }

        $amount = $validated['amount'] ?? 50000;
        $currency = $validated['currency'] ?? 'UZS';

        $boost = PropertyBoost::create([
            'property_id' => $property->id,
            'user_id' => $user->id,
            'status' => 'active',
            'starts_at' => now(),
            'ends_at' => now()->addHours($hours),
            'amount' => $amount,
            'currency' => $currency,
            'meta' => [
                'source' => 'one_time',
            ],
        ]);

        return back()->with('success', __('E’lon :title pullik tarzda TOPga ko\'tarildi.', [
            'title' => $property->title,
        ]));
    }
}
