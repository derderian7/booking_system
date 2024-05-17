<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Business;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('user','business','service')
        ->paginate(10);
        return response()->json(['result'=>$bookings,'error'=>null],200);
    }

    public function store(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'service_id' => 'required',
                'time' => 'required',
                'business_id' => 'required',
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
            }
            $booking = new Booking();
            $booking->user_id = Auth::id();
            $booking->service_id = $request->service_id;
            $booking->business_id = $request->business_id;
            $booking->time = $request->time;
            $booking->save();
        return response()->json(['result'=>['message'=>'Booking is added'],'error'=>null],200);
        }catch (Exception $e){
            return response()->json(['result'=>null,'error'=>$e]);
        }
    }

    public function destroy($id)
    {
        Booking::findOrFail($id)->delete();
        return response()->json(['result'=>['message'=>'Booking is deleted'],'error'=>null],200);
    }

    public function user_bookings()
    {
        $bookings = Booking::where('user_id',Auth::id())
        ->with('service','business')
        ->paginate(10);
        return response()->json(['result'=>$bookings,'error'=>null],200);
    }

    /*public function getAvailableHours($businessId, $serviceId)
    {
        // Step 1: Retrieve Business and Service Information
        //$business = Business::findOrFail($businessId);
        $service = Service::findOrFail($serviceId);
        $open = $service->open;
        $close = $service->close;
        $serviceDuration = $service->duration;

        // Step 2: Calculate Available Hours
        $availableHours = $this->calculateAvailableHours($open,$close, $serviceDuration);

        // Step 3: Filter Out Booked Time Slots
        $bookedAppointments = Booking::where('business_id', $businessId)
            ->where('service_id', $serviceId)
            ->pluck('time')
            ->toArray();

        $availableHours = $this->filterBookedTimeSlots($availableHours, $bookedAppointments, $serviceDuration);

        // Step 4: Return Available Hours
        return response()->json(['data' => $availableHours]);
    }

    private function calculateAvailableHours($open,$close, $serviceDuration)
    {
        // Implement logic to calculate available hours based on opening hours and service duration
        // Example: return ["09:00", "10:30", "12:00", ...]
        // You may use Carbon for time manipulations.
        $start = Carbon::parse($open);
        $end = Carbon::parse($close);
        $interval = CarbonInterval::minutes($serviceDuration);

        $availableHours = [];
        while ($start->add($interval)->lte($end)) {
            $availableHours[] = $start->format('H:i');
        }

        return $availableHours;
    }

    private function filterBookedTimeSlots($availableHours, $bookedAppointments, $serviceDuration)
    {
        // Implement logic to filter out booked time slots
        // Example: return ["09:00", "12:00", ...]
        $x= [];
        foreach($bookedAppointments as $appointment){
            $y = Carbon::parse($appointment);
            $x[] = $y->format('H:i');
        };

        foreach ($x as $bookedTime) {
            $index = array_search($bookedTime, $availableHours);
            if ($index !== false) {
                array_splice($availableHours, $index, 1);
            }
        }

        return $availableHours;
    }*/

    public function getAvailableHours($serviceId,$selectedDate)
    {
        // Step 1: Retrieve Service Information
        $service = Service::findOrFail($serviceId);

        // Step 2: Calculate Available Hours
        $availableHours = $this->calculateAvailableHours($service->duration,$selectedDate);

        // Step 3: Filter Out Booked Time Slots
        $bookedAppointments = $this->getBookedAppointments($serviceId);

        $availableHours = $this->filterBookedTimeSlots($availableHours, $bookedAppointments);

        // Step 4: Return Available Hours
        $time = [];
        foreach($availableHours as $x){
            $time [] =[ 
                'time' => $x['time']
            ];
        };
        return response()->json(['result' => ['Available Slots'=>$time],
        'error'=>null
        ]);
    }

    private function calculateAvailableHours($serviceDuration,$selectedDate)
    {
        $start = Carbon::parse($selectedDate)->setTime(10, 0, 0); // Start from today at 10 am
        $end = Carbon::parse($selectedDate)->setTime(20, 0, 0);   // End today at 8 pm
        $interval = CarbonInterval::minutes($serviceDuration);

        $availableSlots = [];

        while ($start->lte($end)) {
            // Exclude Mondays
            if ($start->dayOfWeek !== Carbon::MONDAY) {
                $availableSlots[] = [
                    'date' => $start->toDateString(),
                    'day' => $start->isoFormat('dddd'), // Full name of the day
                    'time' => $start->format('H:i'),
                ];
            }

            $start->add($interval);
        }

        return $availableSlots;
    }

    private function getBookedAppointments($serviceId)
    {
        return Booking::where('service_id', $serviceId)
            ->pluck('time')
            ->toArray();
    }

    private function filterBookedTimeSlots($availableHours, $bookedAppointments)
    {
        $x= [];
        foreach($bookedAppointments as $appointment){
            $y = Carbon::parse($appointment);
            $x[] = [
                'date' => $y->toDateString(),
                'day' => $y->isoFormat('dddd'), // Full name of the day
                'time' => $y->format('H:i')
            ];
        };
       //dd($x);

        foreach ($x as $bookedTime) {
            $index = array_search($bookedTime, $availableHours);
            if ($index !== false) {
                unset($availableHours[$index]);
               // dd($availableHours);
            }
        }

        return array_values($availableHours); // Reset array keys after unset
    }

}