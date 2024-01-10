<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    // Gets all the users
    public function index()
    {
        $users = User::select('Name', 'Surname', 'Email')->get();
        return new UsersResource($users);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    // Creates a new user
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|max:255|email',
            'password'=>'required|min:8',
        ]);
 
        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $newUser = new User();
        $newUser->Name = $request->input('firstname');
        $newUser->Surname = $request->input('lastname');
        $newUser->Email = $request->input('email');
        $newUser->Password = Hash::make($request->input('password'));

        $newUser->save();
        $newUser->makeHidden(['Password']);
        return response()->json(['message' => 'User created successfully', 'user' => $newUser], 201);
    }

    // Gets a specific user
    public function show($ID)
    {
        $user=User::find($ID);
        if($user==null)return response()->json(['message' => 'Can not find the specific user'], 404);;
        return response()->json(['user' => $user], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        //
    }

    // Updates a user
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'string|max:255',
            'lastname' => 'string|max:255',
            'email' => 'string|max:255|email',
            'password'=>'min:8',
        ]);
 
        if($validator->fails()){
            return response()->json($validator->errors());
        }

        $updateUser = User::findOrFail($id);
        if($request->input('firstname')!=null)$updateUser->Name = $request->input('firstname');
        if($request->input('lastname')!=null)$updateUser->Surname = $request->input('lastname');
        if($request->input('email')!=null)$updateUser->Email = $request->input('email');
        if($request->input('password')!=null)$updateUser->Password = Hash::make($request->input('password'));

        $updateUser->save();
        $updateUser->makeHidden(['Password']);
        return response()->json(['message' => 'User updated successfully', 'user' => $updateUser], 200);
    }

    // Deletes a user
    public function destroy($id)
    {
        try{
            $deleteUser = User::findOrFail($id);

            $deleteUser->delete();
            return response()->json(['message' => 'User deleted successfully', 'user' => $deleteUser], 200);
        }catch(\Exception $ex){
            return response()->json(['message' => 'To delete this user, first remove him from all teams'],400);
        }
        
    }
}
