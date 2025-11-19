<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'additional_phones',
        'secondary_email',
        'whatsapp_number',
        'telegram_username',
        'avatar',
        'bio',
        'company_name',
        'company_logo',
        'license_number',
        'website',
        'social_links',
        'address',
        'city',
        'district',
        'country',
        'latitude',
        'longitude',
        'properties_count',
        'rating',
        'reviews_count',
        'verified',
        'featured',
        'is_profile_public',
        'onboarding_status',
        'onboarding_progress',
        'onboarding_data',
        'notification_preferences',
        'provider_documents',
        'verification_notes',
        'verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'social_links' => 'array',
            'additional_phones' => 'array',
            'notification_preferences' => 'array',
            'rating' => 'decimal:2',
            'properties_count' => 'integer',
            'reviews_count' => 'integer',
            'verified' => 'boolean',
            'featured' => 'boolean',
            'is_profile_public' => 'boolean',
            'onboarding_progress' => 'integer',
            'onboarding_data' => 'array',
            'provider_documents' => 'array',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * Check if user is provider
     */
    public function isProvider(): bool
    {
        return $this->role === 'provider';
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is builder (quruvchi kompaniya)
     */
    public function isBuilder(): bool
    {
        return $this->role === 'builder';
    }

    /**
     * Get user's properties
     */
    public function properties()
    {
        return $this->hasMany(Property::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(UserSubscription::class);
    }

    public function propertyBoosts()
    {
        return $this->hasMany(PropertyBoost::class);
    }

    /**
     * Get user's comments
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get user's developments (for builders)
     */
    public function developments()
    {
        return $this->hasMany(Development::class, 'user_id');
    }
}
