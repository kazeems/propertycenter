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
        $allProperties = Property::all();
        return response()->json([
            'success' => true,
            'data' => $allProperties
        ]);
    }

    public function getProperty(Request $request, $propertyId) {
        $property = Property::find($propertyId);
        if($property == null) {
            return response()->json([
                'success' => false,
                'message' => 'Property does not exist'
            ]);
        } else {
            return response()->json([
                'success' => true,
                'message' => 'Property found successfully',
                'data' => $property
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
        if($delProperty == null) {
            return response()->json([
                'success' => false,
                'message' => 'Property does not exist'
            ]);
        } else {
        // delete property
            $delProperty->delete();

            // return succcess response
            return response()->json([
                'success' => true,
                'message' => 'Property deleted successfully'
            ]);
        }

    }
}
