<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rental;

class rentalController extends Controller
{
    public function index()
    {
        $all = Rental::with('car', 'user')->get();

        return response()->json($all);
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $Rental = Rental::with('car', 'user')->findOrFail($id);
        return response()->json([
            'Rental' => $Rental
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
