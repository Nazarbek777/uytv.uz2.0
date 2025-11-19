<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $contactTabs = [
            'profile' => __('Profil ma\'lumotlari'),
            'notifications' => __('Bildirishnomalar'),
            'security' => __('Xavfsizlik'),
        ];

        return view('provider.settings', compact('user', 'contactTabs'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'secondary_email' => ['nullable', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:50'],
            'additional_phones' => ['nullable', 'array', 'max:5'],
            'additional_phones.*' => ['nullable', 'string', 'max:50'],
            'whatsapp_number' => ['nullable', 'string', 'max:50'],
            'telegram_username' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'district' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'string', 'max:30'],
            'longitude' => ['nullable', 'string', 'max:30'],
            'bio' => ['nullable', 'string', 'max:500'],
            'instagram' => ['nullable', 'url', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'is_profile_public' => ['nullable', 'boolean'],
        ]);

        $user->name = $validated['full_name'];
        $user->company_name = $validated['full_name'];
        $user->secondary_email = $validated['secondary_email'] ?? null;
        $user->phone = $validated['phone'];
        $user->additional_phones = collect($validated['additional_phones'] ?? [])
            ->filter(fn ($value) => !empty($value))
            ->values()
            ->all();
        $user->whatsapp_number = $validated['whatsapp_number'] ?? null;
        $user->telegram_username = $validated['telegram_username'] ?? null;
        $user->city = $validated['city'] ?? null;
        $user->district = $validated['district'] ?? null;
        $user->address = $validated['address'] ?? null;
        $user->latitude = $validated['latitude'] ?? null;
        $user->longitude = $validated['longitude'] ?? null;
        $user->bio = $validated['bio'] ?? null;
        $user->website = $validated['website'] ?? null;
        $user->is_profile_public = $request->boolean('is_profile_public');

        $socialLinks = $user->social_links ?? [];
        $socialLinks['instagram'] = $validated['instagram'] ?? ($socialLinks['instagram'] ?? null);
        $socialLinks['facebook'] = $validated['facebook'] ?? ($socialLinks['facebook'] ?? null);
        $user->social_links = array_filter($socialLinks);

        $user->save();

        return back()->with('success', __('Profil ma\'lumotlari saqlandi.'));
    }

    public function updateNotifications(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'notify_on_approval' => ['nullable', 'boolean'],
            'notify_on_messages' => ['nullable', 'boolean'],
            'notify_on_expiry' => ['nullable', 'boolean'],
        ]);

        $preferences = $user->notification_preferences ?? [];
        $preferences['notify_on_approval'] = $request->boolean('notify_on_approval');
        $preferences['notify_on_messages'] = $request->boolean('notify_on_messages');
        $preferences['notify_on_expiry'] = $request->boolean('notify_on_expiry');

        $user->notification_preferences = $preferences;
        $user->save();

        return back()->with('success', __('Bildirishnoma sozlamalari saqlandi.'));
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => __('Joriy parol noto‘g‘ri.')]);
        }

        $user->password = Hash::make($validated['password']);
        $user->save();

        return back()->with('success', __('Parol yangilandi.'));
    }
}
