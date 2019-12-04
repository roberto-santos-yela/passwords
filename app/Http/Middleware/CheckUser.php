<?php

namespace App\Http\Middleware;

use App\Helpers\Token;
use App\User;
use Closure;

class CheckUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request_token = $request->header('Authorization');
        $token = new Token();
        $user_email = $token->decode($request_token);
        $user = User::where('email', '=', $user_email)->first();

        $request->request->add([
            
            'user' => $user,
            
            ]);

        if($user != null)
        {
            return $next($request);
        
        }
        
        //print_r($user_email); exit;
         
    }
}
