<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use App\Models\Development;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && $request->role) {
            $query->where('role', $request->role);
        }

        // Filter by verified
        if ($request->has('verified')) {
            $query->where('verified', $request->verified == '1');
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        
        // Account ma'lumotlarini olish
        $properties = [];
        $developments = [];
        
        if ($user->role === 'provider') {
            $properties = Property::where('user_id', $user->id)->latest()->limit(10)->get();
        } elseif ($user->role === 'builder') {
            $developments = Development::where('user_id', $user->id)->latest()->limit(10)->get();
        }

        return view('admin.users.show', compact('user', 'properties', 'developments'));
    }

    /**
     * Account holatini modal'da ko'rsatish (admin o'z holatida qoladi)
     */
    public function viewAccount($id)
    {
        $user = User::findOrFail($id);
        
        // Account ma'lumotlarini olish
        $accountData = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? 'N/A',
            'role' => $user->role,
            'company_name' => $user->company_name ?? 'N/A',
            'verified' => $user->verified,
            'featured' => $user->featured,
            'avatar' => $user->avatar,
            'created_at' => $user->created_at->format('d.m.Y H:i'),
        ];

        // Role'ga qarab ma'lumotlar
        if ($user->role === 'provider') {
            $accountData['properties_count'] = Property::where('user_id', $user->id)->count();
            $accountData['published_properties'] = Property::where('user_id', $user->id)->where('status', 'published')->count();
            $accountData['pending_properties'] = Property::where('user_id', $user->id)->where('status', 'pending')->count();
            $recentProps = Property::where('user_id', $user->id)->latest()->limit(5)->get();
            $accountData['recent_properties'] = $recentProps->map(function($prop) {
                return [
                    'id' => $prop->id,
                    'title' => $prop->title ?? ($prop->translations->first()->title ?? 'N/A'),
                    'status' => $prop->status,
                    'city' => $prop->city,
                    'created_at' => $prop->created_at->toDateTimeString(),
                ];
            });
        } elseif ($user->role === 'builder') {
            $accountData['developments_count'] = Development::where('user_id', $user->id)->count();
            $accountData['published_developments'] = Development::where('user_id', $user->id)->where('status', 'published')->count();
            $accountData['pending_developments'] = Development::where('user_id', $user->id)->where('status', 'pending')->count();
            $recentDevs = Development::where('user_id', $user->id)->latest()->limit(5)->get();
            $accountData['recent_developments'] = $recentDevs->map(function($dev) {
                return [
                    'id' => $dev->id,
                    'title_uz' => $dev->title_uz ?? 'N/A',
                    'status' => $dev->status,
                    'city' => $dev->city,
                    'created_at' => $dev->created_at->toDateTimeString(),
                ];
            });
        }

        return response()->json([
            'success' => true,
            'account' => $accountData
        ]);
    }
}
