<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Repositories\Vote\VoteRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'image_name',
        'image_path',
        'is_admin'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected $appends = [
        'is_evaluated',
        'profile_pic_url',
    ];

    /**
     * Get evaluated status 
     */
    public function getIsEvaluatedAttribute()
    {
        $voterId =auth()->user()->id;
        $VoteRepository = new VoteRepository();
        return $VoteRepository->monthCheck($voterId,$this->id);
    }

    /**
     * Get Profile Pic Url
     */
    public function getProfilePicUrlAttribute()
    {
        $url =  asset('/images/no-user.png');
        if (!empty($this->image_path)) {
            if (Storage::disk('public')->exists($this->image_path))
                $url = route('downloadPublicPhoto', ['name' => $this->image_path]);
        }
        return $url;
    }

    public function voting()
    {
        return $this->hasMany(Voting::class, 'candidate_id');
    }
}
