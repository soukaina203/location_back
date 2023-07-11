<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rental;

class userController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = User::all();

        return response()->json($all);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $User = new User([
            "name" => $request->input('name'),
            "email" => $request->input('email'),
            "password" => $request->input('password'),
            "address" => $request->input('address'),
            "phone" => $request->input('phone'),
        ]);
        $User->save();
        return response()->json([
            'message' => 'Item added successfully'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $User = User::findOrFail($id);
        return response()->json([
            'User' => $User
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $User = User::findOrFail($id);
        return response()->json([
            'User' => $User
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $User = User::findOrFail($id);

        $User->name = $request->input('name');
        $User->email = $request->input('email');
        $User->password = $request->input('password');
        $User->address = $request->input('address');
        $User->phone = $request->input('phone');

        $User->update();
        $User->save();
        return response()->json([
            'message' => 'Item updated successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $User = User::findOrFail($id);
        $rental = Rental::where('user_id', $User->id)->get();
        if ($rental->isEmpty()) {

            $User->delete();
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
}
