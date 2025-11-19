<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Activity log yaratish
     */
    protected function logActivity(
        string $action,
        ?Model $model = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {
        try {
            ActivityLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'model_type' => $model ? get_class($model) : null,
                'model_id' => $model?->id,
                'description' => $description ?? $this->generateDescription($action, $model),
                'old_values' => $oldValues,
                'new_values' => $newValues,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Log yozishda xatolik bo'lsa, sayt ishlashini to'xtatmaslik uchun
            \Log::error('ActivityLog yozish xatosi: ' . $e->getMessage());
        }
    }

    /**
     * Tavsif yaratish
     */
    protected function generateDescription(string $action, ?Model $model = null): string
    {
        $modelName = $model ? class_basename($model) : 'item';
        $userName = Auth::user()?->name ?? 'System';

        $descriptions = [
            'created' => "{$userName} tomonidan yangi {$modelName} yaratildi",
            'updated' => "{$userName} tomonidan {$modelName} yangilandi",
            'deleted' => "{$userName} tomonidan {$modelName} o'chirildi",
            'approved' => "{$userName} tomonidan {$modelName} tasdiqlandi",
            'rejected' => "{$userName} tomonidan {$modelName} rad etildi",
            'featured' => "{$userName} tomonidan {$modelName} featured qilindi",
            'unfeatured' => "{$userName} tomonidan {$modelName} featured'dan olib tashlandi",
            'verified' => "{$userName} tomonidan {$modelName} verified qilindi",
            'unverified' => "{$userName} tomonidan {$modelName} verified'dan olib tashlandi",
            'published' => "{$userName} tomonidan {$modelName} nashr qilindi",
            'unpublished' => "{$userName} tomonidan {$modelName} nashrdan olib tashlandi",
            'login' => "{$userName} tizimga kirdi",
            'logout' => "{$userName} tizimdan chiqdi",
            'scraped' => "{$userName} tomonidan scraper ishga tushirildi",
        ];

        return $descriptions[$action] ?? "{$userName} tomonidan {$action} amali bajarildi";
    }

    /**
     * Model o'zgarishlarini log qilish
     */
    protected function logModelChanges(Model $model, array $oldValues, array $newValues): void
    {
        $changes = [];
        foreach ($newValues as $key => $newValue) {
            $oldValue = $oldValues[$key] ?? null;
            if ($oldValue != $newValue) {
                $changes[$key] = [
                    'old' => $oldValue,
                    'new' => $newValue,
                ];
            }
        }

        if (!empty($changes)) {
            $this->logActivity(
                'updated',
                $model,
                null,
                array_column($changes, 'old'),
                array_column($changes, 'new')
            );
        }
    }
}


