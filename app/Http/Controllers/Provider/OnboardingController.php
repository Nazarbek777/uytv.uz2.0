<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($this->hasProfileInfo($user) && $user->onboarding_status !== 'approved') {
            $user->onboarding_status = 'approved';
            $user->onboarding_progress = 100;
            $user->save();
        }

        return view('provider.onboarding.index', compact('user'));
    }

    public function updateCompanyInfo(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:50',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|max:255',
            'bio' => 'nullable|string|max:500',
            'telegram' => 'nullable|string|max:100',
            'whatsapp' => 'nullable|string|max:100',
            'instagram' => 'nullable|url|max:255',
        ]);

        $user->name = $validated['full_name'];
        $user->company_name = $validated['full_name']; // Legacy fieldsni moslash
        $user->phone = $validated['phone'];
        $user->address = $validated['address'] ?? $user->address;
        $user->city = $validated['city'] ?? $user->city;
        $user->license_number = $validated['id_number'] ?? $user->license_number;
        $user->bio = $validated['bio'] ?? $user->bio;

        $socialLinks = $user->social_links ?? [];
        $socialLinks['telegram'] = $validated['telegram'] ?? ($socialLinks['telegram'] ?? null);
        $socialLinks['whatsapp'] = $validated['whatsapp'] ?? ($socialLinks['whatsapp'] ?? null);
        $socialLinks['instagram'] = $validated['instagram'] ?? ($socialLinks['instagram'] ?? null);
        $user->social_links = array_filter($socialLinks);

        $onboardingData = $user->onboarding_data ?? [];
        $onboardingData['company'] = $validated;
        $user->onboarding_data = $onboardingData;

        $this->syncOnboardingStatus($user);
        $user->save();

        return back()->with('success', __('Ma\'lumotlar saqlandi. Endi e\'lon joylashni boshlashingiz mumkin.'));
    }

    public function uploadDocuments(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'documents' => 'required|array',
            'documents.*' => 'file|max:5120',
        ]);

        $storedDocuments = $user->provider_documents ?? [];

        foreach ($validated['documents'] as $file) {
            $path = $file->store('provider/documents', 'public');
            $storedDocuments[] = [
                'name' => $file->getClientOriginalName(),
                'path' => $path,
                'uploaded_at' => now()->toDateTimeString(),
            ];
        }

        $user->provider_documents = $storedDocuments;
        $user->onboarding_progress = max($user->onboarding_progress, 70);

        if (in_array($user->onboarding_status, ['not_started', 'in_progress'])) {
            $user->onboarding_status = 'in_progress';
        }

        $user->save();

        return back()->with('success', __('Hujjatlar muvaffaqiyatli yuklandi.'));
    }

    public function submitForReview()
    {
        $user = Auth::user();

        if (! $this->hasProfileInfo($user)) {
            return back()->with('error', __('Iltimos, ism va telefonni to\'liq kiriting.'));
        }

        $this->syncOnboardingStatus($user);
        $user->save();

        return back()->with('success', __('Profil ma\'lumotlari tasdiqlandi. Endi e\'lon qo\'shishingiz mumkin.'));
    }

    protected function hasProfileInfo($user): bool
    {
        return ! empty($user->name)
            && ! empty($user->phone);
    }

    protected function syncOnboardingStatus($user): void
    {
        if ($this->hasProfileInfo($user)) {
            $user->onboarding_status = 'approved';
            $user->onboarding_progress = 100;
        } else {
            if ($user->onboarding_status === 'not_started') {
                $user->onboarding_status = 'in_progress';
            }
            $user->onboarding_progress = max($user->onboarding_progress, 40);
        }
    }
}
