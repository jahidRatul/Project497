<?php

namespace App\Http\Controllers;
use Auth;
use App\User;
use Illuminate\Http\Request;
use Socialite;
class GmailLoginController extends Controller
{
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */

    public function handleProviderCallback()
    {
        try{
            $socialUser = Socialite::driver('google')->stateless()->user();
            $user=User::where('google_id',$socialUser->getId())->first();
            $user2=User::where('email',$socialUser->getEmail())->first();
            /* $a=(string)$socialUser->getEmail();*/
            if($user2==null && $user==null){
                $userObj = new User();
                $userObj->google_id=$socialUser->getId();
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
           // return dd($socialUser);
            return redirect('/login');
        }
        //return dd($user);

    }
}
