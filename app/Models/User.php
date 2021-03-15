<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory, Notifiable;
    protected $table = 'users';

    protected $primaryKey = 'id';

    public function role(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(roles::class);
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
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
     * Relation of user-product(many-to-many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function product(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Products::class,'user_product','user_id','product_id');
    }

    public function setNameAttribute($value){
        $this->attributes['name'] = $value;
    }

    public function setPriceAttribute($value){
        $this->attributes['email'] = $value;
    }

    public function setRoleIdAttribute($value){
        $this->attributes['role_id'] = $value;
    }

    public function setPasswordAttribute($value){
        $this->attributes['password'] = $value;
    }

}
