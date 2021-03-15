<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    /**
     * @var mixed|string
     */
    private $image_path;

    public static function create(array $array)
    {
    }

    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Products::class);
    }
    protected $fillable = [
        'image_path',
        'document_path',
        'product_id'
    ];
}
