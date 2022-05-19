<?php

namespace App\Http\Controllers;

use App\Jobs\UploadImageToGallery;
use App\Models\Gallery;
use App\Models\Property;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    public function uploadImageToGallery(Request $request, Property $property) {
        // Validate request body
        $request->validate([
            'image' => ['mimes:png,jpeg,gif,bmp', 'max:2048']
        ]);

        //get the image
        $image = $request->file('image');
 
        // get original file name and replace any spaces with _
        // example: ofiice card.png = timestamp()_office_card.pnp
        $filename = time()."_".preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));

        // move image to temp location (tmp disk)
        $tmp = $image->storeAs('uploads/gallery', $filename, 'tmp');

        $galleryImage = Gallery::create([
            'property_id' => $property->id,
            'image' => $filename,
        ]);

        // dispatch upload job
        $this->dispatch(new UploadImageToGallery($galleryImage));

        // return succcess response
        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully',
        ]);
    }
}
