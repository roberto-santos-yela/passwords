<?php

namespace App\Http\Controllers;

use App\Category;
use App\User;
use App\Helpers\Token;
use App\Helpers\ParseInputStream;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::all();

        return response()->json([

            "all_categories" => $categories,

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

        $repeated_category = Category::where('name', '=', $request->name)
        ->where('user_id', '=', $user->id)
        ->first();

        if($repeated_category == null)
        {
            $category = new Category();
            $category->name = $request->name;
            $category->user_id = $user->id;
            $category->save();

            return response()->json([

                "name" => "$category->name created",
                
            ], 200);

        }else{

            return response()->json([

                "message" => "$request->name category already created, please create another category",
                
            ], 200);

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

        $category = Category::find($id);
              
        $params = [];
        new ParseInputStream($params);
             
        $category->name = $params['name'];
        $category->save();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $category = Category::find($id);
        $category->delete();

    }

    public function show_categories(Request $request)
    {

        $request_token = $request->header('Authorization');
        $token = new Token();
        $user_email = $token->decode($request_token);
        $user = User::where('email', '=', $user_email)->first();

        return response()->json([

            "categories created by this user" => $user->categories,

        ], 200);
   
    }

}
