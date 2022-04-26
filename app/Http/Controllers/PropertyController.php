<?php

namespace App\Http\Controllers;

use App\Http\Resources\PropertyResource;
use App\Jobs\UploadImage;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Str;

class PropertyController extends Controller
{
    public function createProperty(Request $request) {
        // Validate request body
        $request->validate([
            'name' => ['required','min:5','unique:properties,name'],
            'state' => ['required'],
            'type' => ['required'],
            'bedrooms' => ['required'],
            'address' => ['required','string'],
            'price_per_anum' => ['required','integer'],
            'image' => ['mimes:png,jpeg,gif,bmp', 'max:2048']
        ]);

         //get the image
         $image = $request->file('image');
 
         // get original file name and replace any spaces with _
         // example: ofiice card.png = timestamp()_office_card.pnp
         $filename = time()."_".preg_replace('/\s+/', '_', strtolower($image->getClientOriginalName()));
 
         // move image to temp location (tmp disk)
         $tmp = $image->storeAs('uploads/original', $filename, 'tmp');
        // add property to database table
        $newProperty = Property::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'state' => $request->state,
            'type' => $request->type,
            'bedrooms' => $request->bedrooms,
            'image' => $filename,
            'disk' => config('site.upload_disk'),
            'address' => $request->address,
            'price_per_anum' => $request->price_per_anum
        ]);

        //dispacth job to handle image manipulation
        $this->dispatch(new UploadImage($newProperty));

        // return succcess response
        return response()->json([
            'success' => true,
            'message' => 'New property created successfully',
            'data' => new PropertyResource($newProperty)
        ]);

    }
    public function getProperties() {
        $allProperties = Property::all();
        return response()->json([
            'success' => true,
            'data' => PropertyResource::collection($allProperties)
        ]);
    }

    public function getProperty(Request $request, $propertyId) {
        $property = Property::find($propertyId);
        if(!$property) {
            return response()->json([
                'success' => false,
                'message' => 'Property does not exist'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Property found successfully',
                'data' => [
                    'property' => new PropertyResource($property)
                ]
            ]);
        }
    }

    public function updateProperty(Request $request, $propertyId) {
        $request->validate([
            'name' => ['required','min:5','unique:properties,name,'. $propertyId],
            'state' => ['required'],
            'type' => ['required'],
            'bedrooms' => ['required']
        ]);
        // add updated property info to database table
            $propertyInfo = Property::find($propertyId);
            if($propertyInfo == null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Property does not exist'
                ]);
            } else {
                $propertyInfo->name = $request->name;
                $propertyInfo->slug = Str::slug($request->name);
                $propertyInfo->state = $request->state;
                $propertyInfo->type = $request->type;
                $propertyInfo->bedrooms = $request->bedrooms;
                $propertyInfo->save();
            
            // return succcess response
            return response()->json([
                'success' => true,
                'message' => 'Property inforamtion updated successfully'
            ]);
        }
    }

    public function deleteProperty($propertyId) {
        $delProperty = Property::find($propertyId);
        // Check if property exists
        if(!$delProperty) {
            return response()->json([
                'success' => false,
                'message' => 'Property does not exist'
            ]);
        } 

        //Deleting the images associated with the products
        foreach (['thumbnail', 'large', 'original'] as $size) {
            //check if file exist
            if (Storage::disk($delProperty->disk)->exists("uploads/properties/{$size}/" . $delProperty->image)) {
                Storage::disk($delProperty->disk)->delete("uploads/properties/{$size}/" . $delProperty->image);
            }
        }

        // delete property
            $delProperty->delete();

            // return succcess response
            return response()->json([
                'success' => true,
                'message' => 'Property deleted successfully'
            ]);
    }

    public function search(Request $request) {

        $property = new Property();
        $query = $property->newQuery();

        if($request->has('state')) {
            $query->where('state', $request->state);
        }

        if($request->has('type')) {
            $query->where('type', $request->type);
        }

        if($request->has('bedrooms')) {
            $query->where('bedrooms', $request->bedrooms);
        }

        if($request->has('minPrice')) {
            $query->where('price_per_anum', '>=', $request->minPrice);
        }

        if($request->has('maxPrice')) {
            $query->where('price_per_anum', '<=', $request->maxPrice);
        }
        return response()->json([
            'success' => true,
            'message' => 'Search results found',
            'data' => $query->get()
        ]);
    }
}
