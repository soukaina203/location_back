<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Car;
use App\Models\Rental;

class carController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function CarsForUser(){
        $topCars = Car::orderByDesc('nbrRentals')->limit(7)->get();
        $distinctTypes = Car::distinct()->pluck('type');
        $distinctColors= Car::distinct()->pluck('color');
        $distinctModels= Car::distinct()->pluck('model');
        $distinctYear= Car::distinct()->pluck('year');
        // Groupes
        $carsByType = Car::orderBy('type')->get()->groupBy('type');
        $carsByColor = Car::orderBy('color')->get()->groupBy('color');
        $carsByModel = Car::orderBy('model')->get()->groupBy('model');
        $carsByYear = Car::orderBy('year')->get()->groupBy('year');
        $available=Car::where('available',1)->get();
        $NotAvailable=Car::where('available',0)->get();

        return response()->json([
            'cars' => $topCars,
            'distinctTypes' => $distinctTypes,
            'distinctColors'=>$distinctColors,
            'distinctModels'=>$distinctModels,
            'distinctYear'=>$distinctYear,

            'groupeTypes' => $carsByType,
            'groupeColor' => $carsByColor,
            'groupeModel' => $carsByModel,
            'groupeYear' => $carsByYear,
            'available'=> $available,
            'NotAvailable'=>$NotAvailable,

        ]);

     }

     public function uploadCars(Request $request)
     {
         $request->validate([
             'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Allow jpeg, png, jpg, gif images up to 2MB
         ]);
         // return response()->json(['img'=> $request->file('image')]);
         if ($request->hasFile('image')) {
             $image = $request->file('image');
             $name = time() . '.' . $image->getClientOriginalExtension();
             $image->move('images/', $name);

             return ['image_url' => $name];
         }

         return response()->json(['message' => 'No image uploaded.'], 400);
     }

    public function uploadImgs(Request $request, string $id)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Allow jpeg, png, jpg, gif images up to 2MB
        ]);
        // return response()->json(['img'=> $request->file('image')]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time() . '.' . $image->getClientOriginalExtension();
            $image->move('images/', $name);
            $car = Car::findOrFail($id);
            $car->photo = $name;
            $car->update();
            return ['image_url' => $name];
        }

        return response()->json(['message' => 'No image uploaded.'], 400);
    }


    public function store(Request $request)
    {

            // $image = $request->file('photo');
            // $name = time() . '.' . $image->getClientOriginalExtension();
            // $image->move('images/', $name);
            $car = new Car([
                "model" => $request->input('model'),
                "make" => $request->input('make'),
                'photo' => $request->input('photo'),
                "year" => $request->input('year'),
                "color" => $request->input('color'),
                "price_per_day" => $request->input('price_per_day'),
                "available" => $request->input('available'),
            ]);
            $car->save();
            return response()->json([
                'message' => 'Item added successfully'
            ]);

    }
    public function index()
    {
        // for users
        $all = Car::all();
        $cars = [];

        for ($i = 0; $i < count($all); $i++) {
            if ($all[$i]->available === 1) {
                array_push($cars, $all[$i]);
            }
        }
        return  response()->json($cars);
    }
    public function carsForAdmin()
    {
        // for admin
        //distinct
        $distinctTypes = Car::distinct()->pluck('type');
        $distinctColors= Car::distinct()->pluck('color');
        $distinctModels= Car::distinct()->pluck('model');
        $distinctYear= Car::distinct()->pluck('year');
        // Groupes
        $carsByType = Car::orderBy('type')->get()->groupBy('type');
        $carsByColor = Car::orderBy('color')->get()->groupBy('color');
        $carsByModel = Car::orderBy('model')->get()->groupBy('model');
        $carsByYear = Car::orderBy('year')->get()->groupBy('year');
        $available=Car::where('available',1)->get();
        $NotAvailable=Car::where('available',0)->get();
        $all = Car::all();

        return response()->json([
            'cars' => $all,
            'distinctTypes' => $distinctTypes,
            'distinctColors'=>$distinctColors,
            'distinctModels'=>$distinctModels,
            'distinctYear'=>$distinctYear,

            'groupeTypes' => $carsByType,
            'groupeColor' => $carsByColor,
            'groupeModel' => $carsByModel,
            'groupeYear' => $carsByYear,
            'available'=> $available,
            'NotAvailable'=>$NotAvailable,

        ]);
    }

    /**
     * Store a newly created resource in storage.
     */


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $car = Car::findOrFail($id);
        return response()->json([
            'car' => $car
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $car = Car::findOrFail($id);

        $car->model = $request->input('model');
        $car->make = $request->input('make');
        $car->year = $request->input('year');
        $car->type = $request->input('type');
        $car->color = $request->input('color');
        $car->price_per_day = $request->input('price_per_day');
        $car->available = $request->input('available');

        $car->update();
        $car->save();
        return response()->json([
            'message' => 'Item updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $car = Car::findOrFail($id);
        $rentals = Rental::where('car_id', $car->id)->get();
        for ($i = 0; $i < count($rentals); $i++) {
            $rentals[$i]->delete();
        }
            $car->delete();
            return response()->json([
                'msg' => "done"
            ]);

    }
    public function BestDeals()
    {
        $topCars = Car::orderByDesc('nbrRentals')->limit(3)->get();
        return response()->json([
            'topCars' => $topCars
        ]);
    }
    public function search($key){
        $an = Car::where(function ($query) use ($key) {
            $query->where('model', 'like', '%' . $key . '%')
                  ->orWhere('make', 'like', '%' . $key . '%');
        })->get();
        return response()->json($an);
    }

}
