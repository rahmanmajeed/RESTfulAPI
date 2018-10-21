<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users =User::all();
        return response()->json(['users'=>$users],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $request->validate([
          'name' => 'required',
          'email' => 'email|required|unique:users',
          'password' => 'required|min:6|confirmed'
      ]);
      $user = new User;
      $user->name =$request->name;
      $user->email =$request->email;
      $user->password=bcrypt($request->password);
      $user->verified= User::NOTVERIFIED_USER;
      $user->verification_token=User::generateVerificationCode();
      $user->admin=User::REGULAR_USER;

      $user->save();

        return response()->json(['user'=>$user],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json(['user'=>$user],200);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);   
        $request->validate([
            'email'    => 'email|unique:users,email'.$user->id,
            'password' => 'min:6|confirmed',
            'admin'    => 'in:'.User::ADMIN_USER.','. User::REGULAR_USER,
        ]);
        if ($request->has('name')) {
            $user->name=$request->name;
        }

        if($request->has('email') && $user->email != $request->email){
            $user->verified = User::NOTVERIFIED_USER;
            $user->verification_token= User::generateVerificationCode();
            $user->email =$request->email;
        }

        if($request->has('password')){
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            if(!$user->isVerified()){
                return response()->json(['error' => 'Only verified users can modify','code'=>409],409);
            }
            $user->admin = $request->admin;
        }

        if(!$user->isDirty()){
            return response()->json(['error' => 'You Need to specify a different','code'=>422],422);
        }
        $user->save();

        return response()->json(['data'=>$user],200);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(['user'=>$user],200);
    }
}
