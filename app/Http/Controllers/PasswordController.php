<?php

namespace App\Http\Controllers;

use App\Password;
use App\Category;
use App\User;
use App\Helpers\Token;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $passwords = Password::all();

        return response()->json([

            "all_passwords" => $passwords,

        ], 200);
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
           
        $request_token = $request->header('Authorization');
        $token = new Token();
        $user_email = $token->decode($request_token);
        $user = User::where('email', '=', $user_email)->first();

        $category = Category::where('name','=', $request->category)
        ->where('user_id', '=', $user->id)
        ->first(); 

        if($category != null)
        {
            $password = new Password();
            $password->title = $request->title;
            $password->password = $request->password;
            $password->category_id = $category->id;             
            $password->save();

            return response()->json([

                "message" => "password $request->title created.",

            ], 201 );

        }else{

            return response()->json([

                "message" => "$request->category category was not found in database, please create a new category to continue.",

            ], 400);

        }

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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $password = Password::find($id);
        $password->delete();

    }

    public function show_passwords(Request $request)
    {

        $request_token = $request->header('Authorization');
        $token = new Token();
        $user_email = $token->decode($request_token);
        $user = User::where('email', '=', $user_email)->first();
        
        return response()->json([

            "passwords created by this user" => $category->category,

        ], 200);
   
    }
}
