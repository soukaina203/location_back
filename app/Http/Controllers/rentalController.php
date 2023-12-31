<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Models\Rental;
use App\Models\User;

class rentalController extends Controller
{
    public function selecteData()
    {
        $users = User::all();
        $cars = Car::all();
        return response()->json([
            'users' => $users,
            'cars' => $cars

        ]);
    }
    public function processed(Request $request)
    {
        $array = $request->input('table');
        foreach ($array as $item) {
            $rental = Rental::findOrFail($item['id']); // Use square brackets to access the 'id' property
            $rental->processed = 1;
            $rental->update();
        }
    }


    public function index()
    {
        $notProcessed = Rental::with('car', 'user')->where('processed', 0)->get();
        $Processed = Rental::with('car', 'user')->where('processed', 1)->get();

        return response()->json([
            'np' => $notProcessed,
            'p' => $Processed
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Rental = new Rental([
            "user_id" => $request->input('user_id'),
            "car_id" => $request->input('car_id'),
            "rental_start" => $request->input('rental_start'),
            "rental_end" => $request->input('rental_end'),
            "total_price" => $request->input('total_price'),
            "hourStart" => $request->input('hourStart'),
            "hourFinish" => $request->input('hourFinish'),
        ]);
        $Rental->save();
        return response()->json([
            'message' => 'Item added successfully'
        ]);
    }

    public function RentalsOfAUser(string $id)
    {
        $Rental = Rental::with('car')->where('user_id', $id)->get();
        return response()->json($Rental);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Rental = Rental::with('car', 'user')->findOrFail($id);
        $users = User::all();
        $cars = Car::all();
        return response()->json([
            'Rental' => $Rental,
            'Users' => $users,
            'Cars' => $cars

        ]);
    }

    public function NotProcessed()
    {
        $rentals = Rental::where('processed', 0)->get();
        return response()->json($rentals);
    }
    /**
     * Show the form for editing the specified resource.
     */

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $Rental = Rental::findOrFail($id);

        $Rental->user_id = $request->input('user_id');
        $Rental->car_id = $request->input('car_id');
        $Rental->rental_start = $request->input('rental_start');
        $Rental->rental_end = $request->input('rental_end');
        $Rental->total_price = $request->input('total_price');
        $Rental->hourStart = $request->input('hourStart');
        $Rental->hourFinish = $request->input('hourFinish');

        $Rental->update();
        $Rental->save();
        return response()->json([
            'message' => 'Item updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Rental = Rental::findOrFail($id);

        $Rental->delete();
        return response()->json([
            'msg' => "done"
        ]);
    }
}
