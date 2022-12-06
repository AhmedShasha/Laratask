<?php

namespace App\Http\Controllers\LAratask;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        //
        $data['allUsers'] = User::get();

        return view('Laratask.users.index', compact('data'));
    }

    public function create()
    {
        //
        return view('Laratask.users.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'email'=>'required|unique:users',
            'password'=>'required|confirmed',
            'role'=>'required',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        $user->password = bcrypt($request->password);

        $user->save();

        toast('Successfully Added', 'success');
        return redirect()->route('allUsers');
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('Laratask.users.edit',compact('user'));
    }

    public function update(Request $request, $id)
    {
        
        $user  = User::Find($id);
        $request->validate([
            'name'=>'required',
            'email'=>'required',
            'role'=>'required',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        $user->update();

        toast('Successfully updated', 'success');
        return redirect()->route('allUsers');
    }

    public function destroy($id)
    {
        //
        $user = User::find($id);

        $user->delete();

        toast('Deleted Successfully' , 'success');
        return redirect()->back();
    }
}
