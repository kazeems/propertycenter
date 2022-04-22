<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'state',
        'type',
        'bedrooms',
        'address',
        'price_per_anum',
        'image',
        'upload_successful',
        'disk'
    ];

    public function getImagesAttribute()
    {
        return [
            "thumbnail" => $this->getImagePath("thumbnail"),
            "original" => $this->getImagePath("original"),
            "large" => $this->getImagePath("large"),
        ];
    }

    public function getImagePath($size)
    {
        return Storage::disk($this->disk)->url("uploads/properties/{$size}/" . $this->image);
    }
}
