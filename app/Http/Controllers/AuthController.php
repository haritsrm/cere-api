<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\User;
use Hash;
use Auth;
use Socialite;

class AuthController extends Controller
{
    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);
        $user = new User([
            'name' => $request->name,
            'gender' => $request->gender,
            'address' => $request->address,
            'phone' => $request->phone,
            'birth_place' => $request->birth_place,
            'birth_date' => $request->birth_date,
            'parrent_name' => $request->parrent_name,
            'parrent_phone' => $request->parrent_phone,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);
        $user->save();
        $user->attachRole(2);
        return response()->json([
            'message' => 'Successfully created user!'
        ], 201);
    }
  
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'remember_me' => 'boolean'
        ]);
        $credentials = request(['email', 'password']);
        if(!Auth::attempt($credentials))
            return response()->json([
                'message' => 'Unauthorized'
            ], 401);
        $user = $request->user();
        $tokenResult = $user->createToken('Personal Access Token');
        $token = $tokenResult->token;
        if ($request->remember_me)
            $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();
        return response()->json([
            'access_token' => $tokenResult->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::parse(
                $tokenResult->token->expires_at
            )->toDateTimeString()
        ]);
    }
  
    /**
     * Logout user (Revoke the token)
     *
     * @return [string] message
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
  
    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function user(Request $request)
    {
        return response()->json($request->user());
    }

    public function changePhotoProfile(Request $request, $id){
        $request->validate([
            'photo' => 'image|required|mimes:jpeg,png,jpg,gif,svg'
          ]);
    	$data = User::where('id',$id)->first();
        $image = $request->file('photo');
        if(empty($image)){
           $namaFile = "null";
        }else{
            $namaFile = $id;
            $request->file('photo')->move('images/', $namaFile);
        }
        $data->photo_url = $namaFile;
        $data->save();
        return response()->json([
            'message' => 'Successfully changed photo user!'
        ], 201);
    }

    public function changeProfile(Request $request, $id){
    	$data =  User::where('id',$id)->first();
        $data->name = $request->name;
        $data->gender = $request->gender;
        $data->address = $request->address;
        $data->phone = $request->phone;
        $data->birth_place = $request->birth_place;
        $data->birth_date = $request->birth_date;
        $data->parrent_name = $request->parrent_name;
        $data->parrent_phone = $request->parrent_phone;
        $data->save();

        return response()->json([
            'message' => 'Successfully changed user!'
        ], 201);
    }

    public function changePassword(Request $request, $id){
        $data =  User::where('id',$id)->first();
        $data->password = bcrypt($request->newPassword);
        $data->save();

        return response()->json([
            'message' => 'Successfully changed password user!'
        ], 201);
    }

    public function getPhotoProfile($id){
        $data =  User::where('id',$id)->first();
        $pathToFile = public_path().'/images/'.$data->photo_url;
        return response()->download($pathToFile);        
    }

    public function redirectToProvider($service)
    {
        return Socialite::driver($service)->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($service)
    {
        $user = Socialite::driver($service)->stateless()->user();
        $findUser = User::where('email', $user->getEmail())->first();

        if($findUser){
            $tokenResult = $findUser->createToken($user->token);
            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $user->expiresIn
                )->toDateTimeString()
            ], 201);
        }
        else{
            $user_local = new User([
                'name' => $user->getName(),
                'gender' => "Woman",
                'address' => "Indonesia",
                'phone' => "0877777777",
                'birth_place' => "Bandung",
                'birth_date' => "1998-09-04",
                'parrent_name' => "Sukirman",
                'parrent_phone' => "087777777777",
                'email' => $user->getEmail(),
                'password' => bcrypt('12345678')
            ]);
            $user_local->save();
            $user_local->attachRole(2);

            $tokenResult = $user_local->createToken($user->token);
            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $user->expiresIn
                )->toDateTimeString()
            ], 201);
        }
    }
}
