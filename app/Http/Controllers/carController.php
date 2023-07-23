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
    public function getDistinctTypes()
    {
        $carsByType = Car::orderBy('type')->get()->groupBy('type');


        return  response()->json($carsByType);
    }
    public function index()
    {
        $distinctTypes = Car::distinct()->pluck('type');
        $carsByType = Car::orderBy('type')->get()->groupBy('type');

        $all = Car::all();
        $cars = [];
        for ($i = 0; $i < count($all); $i++) {
            if ($all[$i]->available === 1) {
                array_push($cars, $all[$i]);
            }
        }
        return response()->json([
            'cars'=>$cars,
            'Type'=>$distinctTypes,
            'groupes'=>$carsByType
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
    public function edit(string $id)
    {
        $car = Car::findOrFail($id);
        return response()->json([
            'car' => $car
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $car = Car::findOrFail($id);

        $car->model = $request->input('model');
        $car->make = $request->input('make');
        $car->year = $request->input('year');
        $car->photo = $request->input('photo');
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
        $rental = Rental::where('client_id', $car->id)->get();
        if ($rental->isEmpty()) {

            $car->delete();
            return response()->json([
                'msg' => "done"
            ]);
        } else {

            return response()->json([
                'msg' => "can't do this "
            ]);
        }
        // }
    }
    public function BestDeals()
    {
        $topCars = Car::orderByDesc('nbrRentals')->limit(3)->get();
        return response()->json([
            'topCars' => $topCars
        ]);
    }
}
