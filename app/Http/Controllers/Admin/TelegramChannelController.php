<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TelegramChannel;
use Illuminate\Http\Request;

class TelegramChannelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $channels = TelegramChannel::latest()->paginate(15);
        
        $stats = [
            'total' => TelegramChannel::count(),
            'active' => TelegramChannel::where('is_active', true)->count(),
            'total_scraped' => TelegramChannel::sum('total_scraped'),
        ];
        
        return view('admin.telegram-channels.index', compact('channels', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.telegram-channels.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:telegram_channels,username',
            'chat_id' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'scrape_limit' => 'nullable|integer|min:1|max:1000',
            'scrape_days' => 'nullable|integer|min:1|max:365',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['scrape_limit'] = $validated['scrape_limit'] ?? 50;
        $validated['scrape_days'] = $validated['scrape_days'] ?? 7;

        TelegramChannel::create($validated);

        return redirect()->route('admin.telegram-channels.index')
            ->with('success', 'Telegram kanal muvaffaqiyatli qo\'shildi.');
    }

    /**
     * Display the specified resource.
     */
    public function show(TelegramChannel $telegramChannel)
    {
        return view('admin.telegram-channels.show', compact('telegramChannel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TelegramChannel $telegramChannel)
    {
        return view('admin.telegram-channels.edit', compact('telegramChannel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TelegramChannel $telegramChannel)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:telegram_channels,username,' . $telegramChannel->id,
            'chat_id' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'scrape_limit' => 'nullable|integer|min:1|max:1000',
            'scrape_days' => 'nullable|integer|min:1|max:365',
        ]);

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['scrape_limit'] = $validated['scrape_limit'] ?? 50;
        $validated['scrape_days'] = $validated['scrape_days'] ?? 7;

        $telegramChannel->update($validated);

        return redirect()->route('admin.telegram-channels.index')
            ->with('success', 'Telegram kanal muvaffaqiyatli yangilandi.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TelegramChannel $telegramChannel)
    {
        $telegramChannel->delete();

        return redirect()->route('admin.telegram-channels.index')
            ->with('success', 'Telegram kanal muvaffaqiyatli o\'chirildi.');
    }

    /**
     * Toggle active status
     */
    public function toggleActive(TelegramChannel $telegramChannel)
    {
        $telegramChannel->is_active = !$telegramChannel->is_active;
        $telegramChannel->save();

        return back()->with('success', 'Kanal holati yangilandi.');
    }

    /**
     * Get channel info from Telegram by username
     */
    public function getChannelInfo(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
        ]);

        $username = ltrim(trim($request->input('username')), '@');
        $token = env('TELEGRAM_BOT_TOKEN');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'TELEGRAM_BOT_TOKEN topilmadi. Iltimos, .env faylida sozlang.',
            ], 400);
        }

        try {
            $telegram = new \Telegram\Bot\Api($token);
            
            // Kanal ma'lumotlarini olish
            $chat = $telegram->getChat(['chat_id' => '@' . $username]);

            $data = [
                'success' => true,
                'name' => $chat->title ?? $username,
                'username' => $username,
                'chat_id' => (string) $chat->id ?? null,
                'description' => $chat->description ?? null,
            ];

            return response()->json($data);
        } catch (\Telegram\Bot\Exceptions\TelegramResponseException $e) {
            $errorMessage = $e->getResponse()->getBody()->getContents();
            $errorData = json_decode($errorMessage, true);
            
            return response()->json([
                'success' => false,
                'message' => $errorData['description'] ?? 'Kanal topilmadi yoki bot kanalga qo\'shilmagan.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Xatolik: ' . $e->getMessage(),
            ], 500);
        }
    }
}
