<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{

    use Notifiable, SoftDeletes;

    const ROLE_ADMIN = 'role_admin';
    const ROLE_PUBLISHER = 'role_publisher';
    const ROLE_READER = 'role_reader';

    const MINUTES_TO_RESEND = 15;


    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'deleted_at',
        'next_resend_at',
    ];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'role', 'email', 'password', 'bio', 'image', 'verification_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'role', 'email', 'pivot', 'next_resend_at',
        'remember_token', 'verification_token', 'email_verified_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'next_resend_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['is_verified'];

    /**
     * Get the posts for the user
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function posts() {
        return $this->hasMany(Post::class);
    }

    /**
     * Save the model to the database.
     *
     * @param  array  $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if ($this->role)
        {
            $role = $this->role;
            if ($role != self::ROLE_ADMIN && $role != self::ROLE_PUBLISHER && $role != self::ROLE_READER)
            {
                $this->role = self::ROLE_READER;
            }
        }
        else
        {
            $this->role = self::ROLE_READER;
        }
        return parent::save($options);
    }

    /**
     * @return bool
     */
    public function isVerified()
    {
        return (!is_null($this->email_verified_at));
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return ($this->role == self::ROLE_ADMIN);
    }

    /**
     * @return bool
     */
    public function isPublisher()
    {
        return ($this->role == self::ROLE_ADMIN || $this->role == self::ROLE_PUBLISHER);
    }

    /**
     * @brief   generate a verification token fpr verify user's email
     *          when it is registered by API
     *
     * @return string
     */
    public static function generateVerificationToken()
    {
        return Str::random(200);
    }

    /**
     * Mutator
     */
    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower($value);
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

    public function setSurnameAttribute($value)
    {
        $this->attributes['surname'] = strtolower($value);
    }

    /**
     * Accessors
     */
    public function getNameAttribute($value)
    {
        return ucwords($value);
    }

    public function getSurnameAttribute($value)
    {
        return ucwords($value);
    }

    public function getIsVerifiedAttribute()
    {
        return $this->isVerified();
    }

}
