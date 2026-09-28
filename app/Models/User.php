<?php

namespace App\Models;

use App\Models\Concerns\HasBinaryUuid;
use Database\Factories\UserFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasBinaryUuid, HasFactory, HasRoles, Notifiable;

    public function getAuthIdentifier()
    {
        return $this->uuid();
    }

    public function socialAccounts(): HasMany
    {
        return $this->hasMany(UserSocialAccount::class);
    }

    public function profile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(UserAddress::class);
    }

    public function primaryDomicile(): HasOne
    {
        return $this->hasOne(UserAddress::class)->where('address_type', 'domicile')->where('is_primary', true);
    }

    public function interests(): BelongsToMany
    {
        return $this->belongsToMany(Interest::class, 'user_interests')->withTimestamps()->wherePivotNull('deleted_at');
    }

    public function identity(): HasOne
    {
        return $this->hasOne(UserIdentity::class);
    }

    public function profileVisibility(): HasOne
    {
        return $this->hasOne(UserProfileVisibility::class);
    }

    public function skills(): BelongsToMany
    {
        return $this->belongsToMany(Skill::class, 'user_skills')
            ->withPivot(['id', 'proficiency_level', 'is_self_reported'])
            ->withTimestamps()
            ->wherePivotNull('deleted_at');
    }

    public function userSkills(): HasMany
    {
        return $this->hasMany(UserSkill::class);
    }

    public function educations(): HasMany
    {
        return $this->hasMany(UserEducation::class)->orderByDesc('start_date');
    }

    public function organizationExperiences(): HasMany
    {
        return $this->hasMany(OrganizationExperience::class)->orderByDesc('start_date');
    }

    public function achievements(): HasMany
    {
        return $this->hasMany(UserAchievement::class)->orderByDesc('achievement_date');
    }

    public function siporaNotifications(): HasMany
    {
        return $this->hasMany(UserNotification::class);
    }

    public function createdOrganizations(): HasMany
    {
        return $this->hasMany(Organization::class, 'created_by_user_id');
    }

    public function createdActivities(): HasMany
    {
        return $this->hasMany(Activity::class, 'created_by_user_id');
    }

    public function activityParticipations(): HasMany
    {
        return $this->hasMany(ActivityParticipation::class);
    }

    public function organizationMemberships(): HasMany
    {
        return $this->hasMany(OrganizationMembership::class);
    }

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
        ];
    }
}
