<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function custom(){
        $reviews = Review::with('user')->limit(3)->get();

        return response()->json($reviews);
    }
    public function index()
    {
        $reviews = Review::with('user')->get();

        return response()->json($reviews);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $Rental = new Review([
            "user_id" => $request->input('user_id'),
            "rating" => $request->input('rating'),
            "comment" => $request->input('comment'),

        ]);
        $Rental->save();
        return response()->json([
            'message' => 'yes'
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function userReviews(string $userId)
    {
        $reviews = Review::where('user_id', $userId)->with('user')->get();
        return response()->json($reviews);

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $Review = Review::findOrFail($id);

        $Review->delete();
        return response()->json([
            'msg' => "done"
        ]);
    }
}
