<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProviderOnboarded
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && $user->isProvider()) {
            $allowedRoutes = [
                'provider.onboarding.index',
                'provider.onboarding.company',
                'provider.onboarding.documents',
                'provider.onboarding.submit',
            ];

            if ($user->onboarding_status !== 'approved' && ! $request->routeIs($allowedRoutes)) {
                return redirect()
                    ->route('provider.onboarding.index')
                    ->with('error', __('Iltimos, avval provayder onboarding bosqichlarini yakunlang.'));
            }
        }

        return $next($request);
    }
}
