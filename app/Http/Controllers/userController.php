<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Rental;
use App\Models\Review;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Hash;

class userController extends Controller
{




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
            $car = User::findOrFail($id);
            $car->photo = $name;
            $car->update();
            return ['image_url' => $name];
        }

        return response()->json(['message' => 'No image uploaded.'], 400);
    }






    public function signup(Request $request)
    {

        $user = new User([
            "name" => $request->name,
            "email" => $request->email,
            "password" =>  Hash::make($request->password),
            "address" => $request->address,
            "phone" => $request->phone,


        ]);
        $user->save();
        return response()->json([
            'msg' => "great"


        ]);
    }
    public function logout()
    {
        try {
            auth()->user()->tokens->each(function ($token, $key) {
                $token->delete(); // delete the token from the database
            });

            return response()->json('Logged');
        } catch (QueryException $e) {
            // Handle the exception if something goes wrong during token deletion
            return response()->json('An error occurred while logging out', 500);
        }
    }
    public function login(Request $request)
    {
        // $request->validated($request->all());

        $user = User::where('email', $request->email)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('Api Token of ' . $user->name)->plainTextToken;
            return response()->json([
                'token' => $token,
                'status' => 'success',
                'message' => 'Login successful',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'status' => $user,

            ]);
        }
    }
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
        $image = $request->file('photo');
        $name = time() . '.' . $image->getClientOriginalExtension();
        $image->move('images/', $name);
        $User = new User([
            "name" => $request->input('name'),
            "email" => $request->input('email'),
            "password" =>  Hash::make($request->password),
            "address" => $request->input('address'),
            "phone" => $request->input('phone'),
            "photo" => $name,
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
        $rentals = Rental::where('user_id', $id)->with('car')->get();
        return response()->json([
            'user' => $User,
            'rentals' => $rentals
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
        $User = User::findOrFail($id);

        $User->name = $request->input('name');
        $User->email = $request->input('email');
        $User->password = $request->input('password');
        $User->address = $request->input('address');
        $User->phone = $request->input('phone');
        $User->photo = $request->input('photo');

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
            $reviews = Review::where('user_id', $id)->get();
            for ($i = 0; $i < count($reviews); $i++) {
                $reviews[$i]->delete();
            }
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
