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
             
        $user = $request->user;
       
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
        $user = $request->user;        
        $category = Category::find($id);                 
        
        if($user->id == $category->user_id)
        {            
            $category->name = $request->name;       
            $category->save();

            return response()->json([

                "message" => "category updated",
    
            ], 200);

        }else{

            return response()->json([

                "message" => "can't change other user's category",
    
            ], 401);
  
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
        $user_request = $request->user;
        $category = Category::find($id);
        $user_category = $category->user;
        
        if($user_request == $user_category)
        {
            $category->delete();

            return response()->json([

                "message" => "category deleted",

            ], 200);
            
        }else{

            return response()->json([

                "message" => "can't delete categories that belong to other users",

            ], 200);

        }
 
    }

    public function show_categories(Request $request)
    {

        $user = $request->user;        
        return response()->json($user->categories, 200);
   
    }
    
    public function show_passwords_with_categories(Request $request)
    {

        $user = $request->user; 
        $user_categories_and_passwords = Category::where('user_id', '=', $user->id)->with('passwords')->get();
       
        return response()->json($user_categories_and_passwords, 200);

    }

}
