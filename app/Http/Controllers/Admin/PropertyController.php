<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Property;
use App\Models\User;
use App\Traits\LogsActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PropertyController extends Controller
{
    use LogsActivity;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Property::query()
            ->with(['user', 'translations'])
            ->latest();

        if ($search = trim($request->input('search'))) {
            $query->where(function ($q) use ($search) {
                $q->where('id', $search)
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('translations', function ($translationQuery) use ($search) {
                        $translationQuery->where('title', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($type = $request->input('property_type')) {
            $query->where('property_type', $type);
        }

        if ($listing = $request->input('listing_type')) {
            $query->where('listing_type', $listing);
        }

        $properties = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => Property::count(),
            'pending' => Property::where('status', 'pending')->count(),
            'published' => Property::where('status', 'published')->count(),
            'rejected' => Property::where('status', 'rejected')->count(),
            'today' => Property::whereDate('created_at', today())->count(),
        ];

        return view('admin.properties.index', compact('properties', 'stats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Property $property)
    {
        $property->loadMissing('translations');
        foreach (['uz', 'ru', 'en'] as $locale) {
            $property->translateOrNew($locale);
        }

        $users = User::orderBy('name')->get();

        return view('admin.properties.edit', compact('property', 'users'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Property $property)
    {
        $property->loadMissing(['user', 'translations']);

        return view('admin.properties.show', compact('property'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Property $property)
    {
        $validated = $request->validate([
            'user_id' => ['required', Rule::exists('users', 'id')],
            'status' => ['required', Rule::in(['draft', 'pending', 'published', 'rejected'])],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', Rule::in(['UZS', 'USD', 'EUR'])],
            'area' => ['nullable', 'numeric', 'min:0'],
            'bedrooms' => ['nullable', 'integer', 'min:0'],
            'bathrooms' => ['nullable', 'integer', 'min:0'],
            'property_type' => ['required', 'string', 'max:255'],
            'listing_type' => ['required', Rule::in(['sale', 'rent'])],
            'city' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'featured' => ['nullable', 'boolean'],
            'verified' => ['nullable', 'boolean'],
        ]);

        $oldValues = $property->toArray();
        
        DB::transaction(function () use ($property, $validated, $request) {
            $property->fill([
                'user_id' => $validated['user_id'],
                'status' => $validated['status'],
                'price' => $validated['price'],
                'currency' => $validated['currency'],
                'area' => $validated['area'] ?? null,
                'bedrooms' => $validated['bedrooms'] ?? null,
                'bathrooms' => $validated['bathrooms'] ?? null,
                'property_type' => $validated['property_type'],
                'listing_type' => $validated['listing_type'],
                'city' => $validated['city'] ?? null,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'featured' => $request->boolean('featured'),
                'verified' => $request->boolean('verified'),
            ]);

                if ($property->isDirty('status')) {
                    $property->approval_status = match ($property->status) {
                        'published' => 'approved',
                        'pending' => 'pending',
                        'rejected' => 'needs_changes',
                        default => 'draft',
                    };

                    if ($property->status === 'published') {
                        $property->approval_reviewed_at = $property->approval_reviewed_at ?? now();
                        $property->approval_reviewer_id = $property->approval_reviewer_id ?? Auth::id();
                        $property->approval_notes = null;
                    }

                    if ($property->status === 'draft') {
                        $property->approval_notes = null;
                        $property->approval_reviewed_at = null;
                        $property->approval_reviewer_id = null;
                        $property->approval_submitted_at = null;
                    }

                    if ($property->status === 'pending') {
                        $property->approval_reviewed_at = null;
                        $property->approval_reviewer_id = null;
                    }
                }

            $property->save();
            
            $this->logModelChanges($property, $oldValues, $property->fresh()->toArray());

            foreach (['uz', 'ru', 'en'] as $locale) {
                $translation = $property->translateOrNew($locale);
                $translation->title = $request->input("title_{$locale}");
                $translation->description = $request->input("description_{$locale}");
                $translation->address = $request->input("address_{$locale}");
                $translation->save();
            }
        });

        return redirect()
            ->route('admin.properties.show', $property->id)
            ->with('success', 'Uy-joy ma\'lumotlari yangilandi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Property $property)
    {
        $this->logActivity('deleted', $property);
        $property->delete();

        return redirect()
            ->route('admin.properties.index')
            ->with('success', 'Uy-joy o\'chirildi.');
    }

    /**
     * Approve pending property.
     */
    public function approve(Property $property)
    {
        $property->status = 'published';
        $property->approval_status = 'approved';
        $property->approval_reviewed_at = now();
        $property->approval_reviewer_id = Auth::id();
        $property->approval_notes = null;
        $property->verified = true;
        $property->appendApprovalHistory('approved', [
            'action' => 'approved',
            'reviewer' => Auth::user()->name ?? 'Admin',
        ]);
        $property->save();
        
        $this->logActivity('approved', $property);

        return back()->with('success', 'Uy-joy tasdiqlandi va nashr qilindi.');
    }

    /**
     * Reject pending property.
     */
    public function reject(Request $request, Property $property)
    {
        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $property->status = 'rejected';
        $property->approval_status = 'needs_changes';
        $property->approval_reviewed_at = now();
        $property->approval_reviewer_id = Auth::id();
        $property->approval_notes = $validated['reason'];
        $property->verified = false;
        $property->appendApprovalHistory('needs_changes', [
            'action' => 'rejected',
            'reviewer' => Auth::user()->name ?? 'Admin',
            'reason' => $validated['reason'],
        ]);
        $property->save();
        
        $this->logActivity('rejected', $property);

        return back()->with('success', 'Uy-joy rad etildi.');
    }

    /**
     * Toggle featured flag.
     */
    public function toggleFeatured(Property $property)
    {
        $property->featured = ! $property->featured;
        $property->save();
        
        $this->logActivity($property->featured ? 'featured' : 'unfeatured', $property);

        return back()->with('success', 'Featured holati yangilandi.');
    }

    /**
     * Toggle verified flag.
     */
    public function toggleVerified(Property $property)
    {
        $property->verified = ! $property->verified;
        $property->save();
        
        $this->logActivity($property->verified ? 'verified' : 'unverified', $property);

        return back()->with('success', 'Verified holati yangilandi.');
    }
}
