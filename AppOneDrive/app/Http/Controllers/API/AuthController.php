<?php
 
namespace App\Http\Controllers\API;
 
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
 
 
 
class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
 
        $user = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);
 
        $token = $user->createToken('auth_token')->plainTextToken;
 
        return response()->json(['message' => 'User created successfully.', 'userId' => $user->id, 'userName' => $user->name, 'auth_token' => $token, 'token_type' => 'Bearer'], 200);
    }
 
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|max:255'
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
 
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid email or password.'], 401);
        }
 
        $user = User::where('email', $request['email'])->firstOrFail();
 
        DB::table('personal_access_tokens')->where('tokenable_id', $user->id)->delete();
        $token = $user->createToken('auth_token')->plainTextToken;
 
        return response()->json(['message' => 'Welcome ' . $user->name, 'userId' => $user->id, 'userName' => $user->name, 'auth_token' => $token, 'token_type' => 'Bearer'], 200);
    }
 
    public function logout()
    {
        $user = User::find(auth()->id());
        DB::table('personal_access_tokens')->where('tokenable_id', auth()->id())->delete();
        return response()->json(['message' => 'Goodbye ' . $user->name]);
    }
}