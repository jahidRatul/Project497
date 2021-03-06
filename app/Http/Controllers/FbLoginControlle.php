<?php

namespace App\Http\Controllers;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Socialite;
class FbLoginControlle extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('facebook')->redirect();
    }
    public function handleProviderCallback()
    {
        try{
            $socialUser = Socialite::driver('facebook')->user();
            $user=User::where('facebook_id',$socialUser->getId())->first();
            $user2=User::where('email',$socialUser->getEmail())->first();
           /* $a=(string)$socialUser->getEmail();*/
            if($user2==null && $user==null){
                $userObj = new User();
                $userObj->facebook_id=$socialUser->getId();
                $userObj->name=$socialUser->getName();
                $userObj->email=$socialUser->getEmail();
                $userObj->gender='';
                $userObj->phone='';
                $userObj->dateOfBirth='';
                $userObj->password=bcrypt(123456);
                $userObj->save();
                Auth::login($userObj);
                return redirect()->intended('/home');
            }
            /*elseif ($socialUser->getEmail().toString()==''){
                $userObj = new User();
                $userObj->facebook_id=$socialUser->getId();
                $userObj->name=$socialUser->getName();
                $userObj->email=$socialUser->getId().'@ownNote.com';
                $userObj->gender='';
                $userObj->phone='';
                $userObj->dateOfBirth='';
                $userObj->password=bcrypt(123456);
                $userObj->save();
                Auth::login($userObj);
                return redirect()->intended('/home');
            }*/
           else{
                Auth::login($user2);
                return redirect()->intended('/home');
            }

        }
        catch (\Exception $e){
            //return dd($socialUser);
            return redirect('/login');
        }
        //return dd($user);

    }
}
