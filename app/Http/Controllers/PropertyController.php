<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Str;

class PropertyController extends Controller
{
    public function createProperty(Request $request) {
        // Validate request body
        $request->validate([
            'name' => ['required','min:5','unique:properties,name'],
            'state' => ['required'],
            'type' => ['required'],
            'bedrooms' => ['required']
        ]);
        // add property to database table
        $newProperty = Property::create([
            'user_id' => 1,
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'state' => $request->state,
            'type' => $request->type,
            'bedrooms' => $request->bedrooms
        ]);
        // return succcess response
        return response()->json([
            'success' => true,
            'message' => 'New property created successfully',
            'data' => $newProperty
        ]);

    }
    public function getProperties() {
        return Property::all();
    }

    public function getProperty() {

    }

    public function updateProperty() {

    }

    public function deleteProperty() {
        
    }
}
