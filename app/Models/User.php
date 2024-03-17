<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Http;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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

    public function books(): BelongsToMany
    {
       return $this->belongsToMany(Book::class,'book_users')
           ->withPivot(['start_page','end_page']);
    }

    /**
     * @throws \Exception
     */
    public function SendSms($book)
    {
        $message = " Thanks for reading ".$book->name;
        $data=[
            'to' => $this->mobile,
            'body' => $message
        ];
        try{
            Http::post(env('SMS_URL'), $data);
        }catch(\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }
}
