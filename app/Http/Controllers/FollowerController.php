<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use Auth;

class FollowerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth',[
            'only' => ['store','destroy']
        ]);
    }

    public function store($id)
    {
        $user = User::findOrFail($id);

        if(Auth::user()->id === $user->id){
            session()->flash('danger','关注自己干嘛？');
            return redirect()->route('users.show',Auth::user());
        }

        if(Auth::user()->isFollowing($id)){
            session()->flash('danger','关注过了');
            return redirect()->route('users.show',Auth::user());
        }

        Auth::user()->follow($id);
        session()->flash('success','关注了');

        return back();
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if(Auth::user()->id === $user->id){
            session()->flash('danger','取关自己干嘛？');
            return redirect()->route('users.show',Auth::user());
        }

        if(!Auth::user()->isFollowing($id)){
            session()->flash('danger','还没关注呢');
            return redirect()->route('users.show',Auth::user());
        }


        Auth::user()->unfollow($id);
        session()->flash('success','成功取消');

        return back();
    }
}
