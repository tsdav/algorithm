<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Products extends Model
{
    use HasApiTokens;
    use HasFactory, Notifiable;

    protected $table = 'products';

    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'price',
        'category_id'
    ];

    public function setNameAttribute($value){
        $this->attributes['name'] = $value;
    }

    public function setPriceAttribute($value){
        $this->attributes['price'] = $value;
    }

    public function setCategoryIdAttribute($value){
        $this->attributes['category_id'] = $value;
    }

    /**
     * Relation to category(one-to-one)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    /**
     * Relation to files(one-to-many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function files(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(File::class);
    }

    /**
     * Relation of user-product(many-to-many)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(User::class,'user_product','product_id','user_id');
    }
}
