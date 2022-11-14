<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'password',
        'firstname',
        'middlename',
        'lastname',
        'name_ext',
        'gender',
        'birthdate',
        'contact_no',
        'user_role_id',
        'status',
        'last_ip_address',
        'last_browser',
        'date_login',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function user_image()
    {
        return $this->hasMany(UserImage::class, 'user_id');
    }

    public function user_role()
    {
        return $this->hasOne(UserRole::class, 'id');
    }

    public function scopeUserRole($query)
    {
        return $query->leftJoin('user_roles', 'users.user_role_id', 'user_roles.id');
    }

    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()->getColumnListing($this->getTable());
    }
}