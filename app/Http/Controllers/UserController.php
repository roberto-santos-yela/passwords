<?php

namespace App\Http\Controllers;

use App\User;
use App\Helpers\Token;
use Firebase\JWT\JWT;
use App\Helpers\ParseInputStream;
use Illuminate\Http\Request;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        $users = User::all();        
        return response()->json($users, 200);
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
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = $request->password;
        $user->save();

        $token = new Token($user->email);
        $coded_token = $token->encode();

        return response()->json([

            "token" => $coded_token,

        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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

        $request_user = $request->user;   

        $user = User::find($id);
        
        if($request_user->id == $user->id)
        {            
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();   
            
            response()->json([

                "message" => "user modified",

            ], 200);

        }else{

            response()->json([

                "message" => "can't modify another user",

            ], 200);

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {        
        $user = $request->user;             
        
        if($user->id == $id)
        {
            $user->delete();  

            return response()->json([

                "message" => "user deleted",

            ], 200);


        }else{

            return response()->json([

                "message" => "you can't delete other users",

            ], 401);

        }
    
    }

    public function login(Request $request)
    {

        $user = User::where('email', '=', $request->email)->first();

        $token = new Token($user->email);
        $coded_token = $token->encode();

        if($user->password == $request->password)
        {
            return response()->json([

                "token" => $coded_token,

            ], 200 );
        
        }else{

            return response()->json([

                "message" => "unauthorized",

            ], 401);

        }
                
    }

}
