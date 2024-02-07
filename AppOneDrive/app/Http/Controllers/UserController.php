<?php
 
namespace App\Http\Controllers;
 
use App\Http\Resources\UserResource;
use App\Http\Resources\UsersResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

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
            'password' => 'required|min:8',
        ]);
 
        if ($validator->fails()) {
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
        $user = User::find($ID);
        if ($user == null) return response()->json(['message' => 'Can not find the specific user'], 404);
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
            'email' => 'string|max:255|email'
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
 
        $updateUser = User::findOrFail($id);
        if ($request->input('firstname') != null) $updateUser->name = $request->input('firstname');
        if ($request->input('lastname') != null) $updateUser->surname = $request->input('lastname');
        if ($request->input('email') != null) $updateUser->email = $request->input('email');
 
        $updateUser->save();
        $updateUser->makeHidden(['Password']);
        return response()->json(['message' => 'User updated successfully', 'user' => $updateUser], 200);
    }
 
    public function changePassword(Request $request, $id){
        $validator = Validator::make($request->all(), [
            'currentPassword' => 'min:8|required',
            'newPassword' => 'min:8|required'
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
 
        $user = User::findOrFail($id);
        if ($request->input('currentPassword') != null && Hash::check($request->input('currentPassword'), $user->password)){
            $user->password = Hash::make($request->input('newPassword'));
            $user->save();
            return response()->json(['message' => 'Password updated successfully'], 200);
        }
        return response()->json(['message' => 'Invalid password'], 400);
    }
 
    // Deletes a user
    public function destroy($id)
    {
        try {
            $deleteUser = User::findOrFail($id);
 
            $deleteUser->delete();
            return response()->json(['message' => 'User deleted successfully', 'user' => $deleteUser], 200);
        } catch (\Exception $ex) {
            return response()->json(['message' => 'To delete this user, first remove him from all teams'], 400);
        }
    }
    // Sends reset link for the password
    public function sendResetLink(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|max:255|email',
            ]);
 
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }
 
            $user = User::where('email', $request->input('email'))->first();
 
            if (!$user) {
                return response()->json(['message' => 'User doesnt exist.'], 404);
            }
 
            $userEmail = $request->input('email');
 
            // Generate reset token
            $token = Password::createToken(User::where('email', $userEmail)->first());
 
            // Construct reset link
            $resetLink = url("http://localhost:3000/resetPassword?email={$userEmail}&token={$token}");
 
            // Construct DSN
            $dsn = sprintf(
                '%s://%s:%s@%s:%s',
                env('MAIL_MAILER'),
                urlencode(env('MAIL_USERNAME')),
                urlencode(env('MAIL_PASSWORD')),
                env('MAIL_HOST'),
                env('MAIL_PORT')
            );
 
            // Compose email
            $email = (new Email())
                ->from(env('MAIL_FROM_ADDRESS'))
                ->to($userEmail)
                ->subject('Password Reset Request')
                ->text("Click the link to reset your password:\n$resetLink");
 
            // Create Symfony Mailer instance
            $mailer = new Mailer(Transport::fromDsn($dsn));
 
            // Send email
            $mailer->send($email);
 
            return response()->json(['message' => 'Password reset email sent successfully.'], 200);
        } catch (\Throwable $e) {
            // Log the error
            Log::error('Error sending password reset email: ' . $e->getMessage());
 
            return response()->json(['error' => 'An error occurred while processing your request.'], 500);
        }
    }
 
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'token' => 'required|string',
            'password' => 'required|string|min:8',
        ]);
 
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
 
        $updateUser = User::where('email', $request->input('email'))->first();
        if (!$updateUser) return response()->json(['message' => 'User doesnt exist'], 404);

        $resetTokenBase=DB::table('password_reset_tokens')->where('email',$updateUser->email)->first()->token;
 
        if(!Hash::check($request->input('token'), $resetTokenBase)){
            error_log("Nije isto");
            return response()->json(['message' => 'Error'], 404);
        }

        $updateUser->password = Hash::make($request->input('password'));
        $updateUser->save();
        return response()->json(['message' => 'Password reset successfully'], 200);
    }
}