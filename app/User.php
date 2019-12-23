<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable implements MustVerifyEmail
{
    const ROLE_ADMIN = 'role_admin';
    const ROLE_PUBLISHER = 'role_publisher';
    const ROLE_READER = 'role_reader';

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'surname', 'role', 'email', 'password', 'description', 'image', 'verification_token',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the posts for the user
     */
    public function posts() {
        $this->hasMany(Post::class);
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
     * Mutators
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

}
