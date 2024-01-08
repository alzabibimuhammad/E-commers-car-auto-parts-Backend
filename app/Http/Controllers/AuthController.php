<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\user_backup;
use App\Models\validationseller;
// use Illuminate\Support\Facafes\Cookie;

use Illuminate\Validation\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

use Hash;

use Session;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    //done
    public function __construct()
    {
    $this->middleware('auth:api', ['except' => ['login','postRegistration','create']]);
    }

    public function postRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'password' => 'required',
            'utype' => 'required',
        ]);
        $data = $request->all();
        $image = $request->file('image');
        if ($image && $image->isValid()) {
            $imageName = time().'.'.$image->getClientOriginalExtension();
            $path=$image->move(public_path('users'), $imageName);

            $data['image']='users/'.$imageName;
        }
        if($data['utype']=='1'){
            $data['utype']=1;
            $check = $this->create($data);
            $check2 = $this->create2($data);
            return response()->json( $check);
        }

        elseif($data['utype']=='2'){
            $seller = new validationseller;
            $seller->name = $request->input('name');
            $seller->email = $request->input('email');
            $seller->password = Hash::make($request->input('password'));
            $seller->phone = $request->input('phone');
            $seller->address = $request->input('address');
            $seller->utype=2;
            $seller->image = $data['image'];
            $seller->save();
            return response()->json('Done');
        }

    }
    public function create(array $data)
    {

      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'address' => $data['address'],
        'utype' => $data['utype'],
        'image' => $data['image'],
        'password' => Hash::make($data['password'])

      ]);
    }
    public function create2(array $data)
    {
      return user_backup::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'phone' => $data['phone'],
        'address' => $data['address'],
        'utype' => $data['utype'],
        'image' =>$data['image'],
        'password' => Hash::make($data['password'])
      ]);
    }

    public function login()
    {
    $checkIFBanned=User::where('email',request(['email']))->where('deleted_at')->get();

    if(count($checkIFBanned)!=0){
        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return $this->respondWithToken($token);
    }
    else
        return response()->json(['error' => 'You are Banned'], 401);
}



public function me()
{
    return response()->json(auth()->user());
}

public function logout()
{
    auth()->logout();
    return response()->json(['message' => 'Successfully logged out']);
}

protected function respondWithToken($token)
{
    return response()->json([
        'access_token' => $token,
        'token_type' => 'bearer',
        'expires_in' => JWTAuth::factory()->getTTL() * 120,
        'user'=>auth()->user()
    ]);
}



}






