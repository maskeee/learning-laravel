<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use Auth;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth',[
            'only' => ['edit','update','destroy','followers','followings']
        ]);
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!Auth::user()->is_admin){
            session()->flash('warning','你是管理员咩？');
            return redirect()->route('users.show',Auth::user());
        }
        $users = User::where('activated',true)->paginate(20);
        return view('user.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|min:4|max:20',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6|max:16'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ]);
        $this->sendEmailConfirmationTo($user);
        session()->flash('success', '验证邮件已发送到你的注册邮箱上，请注意查收。');
        return redirect('/');
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
        $statuses = $user->statuses()->orderby('created_at','desc')->paginate('10');
        return view('user.show',compact('user','statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('update', $user);
        return view('user.edit',compact('user'));
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
        $this->validate($request,[
            'name' => 'required|min:4|max:20',
            'password' => 'confirmed|min:6|max:16'
        ]);

        $user = User::findOrFail($id);
        $this->authorize('update', $user);

        $data = array_filter([
            'name' => $request->name,
            'password' => $request->password
        ]);
        $user->update($data);

        session()->flash('success', '个人资料更新成功！');

        return redirect()->route('users.show',$id);
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
        $this->authorize('destroy',$user);
        $user->delete();
        session()->flash('success','User Deleted!');
        return back();
    }


    protected function sendEmailConfirmationTo($user)
    {
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'example@example.com';
        $name = 'Jake';
        $to = $user->email;
        $subject = "感谢注册 Sample 应用！请确认你的邮箱。";

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token)
    {
        $user = User::where('activation_token',$token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        Auth::login($user);
        $user->save();

        session()->flash('success',"激活成功");
        return redirect()->route('users.show',[$user]);
    }

    public function followers($id)
    {
        $user = User::findOrFail($id);
        $users = $user->followers()->paginate('20');
        $title = '粉丝';

        return view('user.show_follow',compact('users','title'));
    }

    public function followings($id)
    {
        $user = User::findOrFail($id);
        $users = $user->followings()->paginate('20');
        $title = '关注';

        return view('user.show_follow',compact('users','title'));
    }
}
