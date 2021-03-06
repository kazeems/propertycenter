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
        'disk',
        'bathrooms',
        'toilets'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getImagesAttribute()
    {
        return [
            "thumbnail" => $this->getImagePath("thumbnail"),
            "original" => $this->getImagePath("original"),
            "large" => $this->getImagePath("large"),
        ];
    }

    public function getGalleryImagesAttribute()
    {
        $galleryImageLink = [];

        foreach($this->galleries as $gallery) {
            array_push($galleryImageLink, $this->getGalleryImagePath($gallery->disk, $gallery->image));
        }

        return $galleryImageLink;
    }

    public function getGalleryImagePath($disk, $image) {
        return Storage::disk($disk)->url("uploads/properties/gallery" . $image);
    }

    public function getImagePath($size)
    {
        return Storage::disk($this->disk)->url("uploads/properties/{$size}/" . $this->image);
    }

    public function galleries() {
        return $this->hasMany(Gallery::class);
    }
}
