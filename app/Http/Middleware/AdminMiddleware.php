<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('home')->with('error', 'Siz tizimga kirishingiz kerak.');
        }

        $user = auth()->user();
        
        // Role mavjudligini tekshirish
        if (!$user->role) {
            return redirect()->route('home')->with('error', 'Sizda role belgilanmagan. Iltimos, admin bilan bog\'laning.');
        }

        if (!$user->isAdmin()) {
            $currentRole = $user->role ?? 'belgilanmagan';
            return redirect()->route('home')->with('error', "Sizda admin huquqi yo'q. Sizning rolingiz: {$currentRole}. Admin bo'lish uchun: php artisan user:make-admin {$user->email}");
        }

        return $next($request);
    }
}

