<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessController extends Controller
{
    public function index(Request $request)
    {
        // Add a filter for the business name if the 'name' query parameter is set
        $query = Business::query();
    
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }
    
        if ($request->has('longitude') && $request->has('latitude')) {
            $radius = 6371; // Earth's radius in kilometers
    
            $query->select('*')
                ->selectRaw(
                    '(? * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                    [$radius, $request->input('latitude'), $request->input('longitude'), $request->input('latitude')]
                )
                ->orderBy('distance');
        }
    
        if ($request->has('service')) {
            $query->whereHas('service', function ($query) use ($request) {
                $query->where('type', $request->input('service'));
            });
        }
    
        // The paginate method will work regardless of whether the other conditions were added to the query or not
        $businesses = $query->paginate(10);
    
        return response()->json(['result' => $businesses, 'error' => null], 200);
    }
    

    public function services_types()
    {
        $servicesByType = Service::with('business')
        ->get()
        ->groupBy('type');

        $formattedData = $servicesByType->map(function ($businesses, $type) {
            // Extract business names and IDs
            return [
                'type' => $type,
                'businesses' => $businesses->pluck('business')->toArray(),
            ];
        })->toArray();
    
        return $formattedData;
    }

    public function business_details()
    {
        $business =  Business::where('user_id', Auth::id())->first();
        return response()->json(['result'=>$business,'error'=>null],200);
    }
}