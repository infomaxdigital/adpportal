<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use App\Mail\UserCredentialsMail;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('permission:View User', ['only' => ['index']]);
        $this->middleware('permission:Create User', ['only' => ['create', 'store']]);
        $this->middleware('permission:Edit User', ['only' => ['edit', 'update']]);
        $this->middleware('permission:Delete User', ['only' => ['destroy']]);
    }
    public function index()
    {
        $users = User::get();
        // return $users; 
        return view('roles-permission.users.index', compact('users'));
    }
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('roles-permission.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|max:255|unique:users,email',
            // 'roles'=> 'required'
        ]);

        // Additional validation for static form
        if ($request->role_type === 'static') {
            $request->validate([
                'mobile' => 'required|string|max:15',
                'dancestyle' => 'required',
                'dancelevel' => 'required',
                'membership' => 'required',
            ]);
        } else {
            $request->validate([
                'roles' => 'required',
            ]);
        }

        // Generate a random password
        $password = Str::random(12);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'contact' => $request->mobile ?? null,
            'dancestyle' => $request->dancestyle ? implode(', ', $request->dancestyle) : null,
            'dancelevel' => $request->dancelevel ?? null,
            'membershipid' => $request->membership ?? null,
        ]);
        if ($request->role_type === 'static') {
            $user->syncRoles('Student');
        } else {
            $user->syncRoles($request->roles);
        }
        // Send email to user with credentials
        //Mail::to($request->email)->send(new UserCredentialsMail($request->name, $request->email, $password));
        return redirect('/users')->with('status', 'User Created successfully');
    }

    public function edit($userid)
    {
        $user = User::find($userid);
        //return $user;
        $userRoles = $user->roles()->pluck('name', 'name')->all();
        $roles = Role::pluck('name', 'name')->all();
        return view('roles-permission.users.edit', compact('user', 'roles', 'userRoles'));
    }

    public function update(Request $request, $userid)
    {
        $user = User::find($userid);
        $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string',
            'roles' => 'required'
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        if (!empty(Hash::make($request->password))) {
            $data += [
                'password' => Hash::make($request->password)
            ];
        }
        $user->update($data);
        $user->syncRoles($request->roles);
        return redirect('/users')->with('status', 'User Updated successfully');
    }

    public function destroy($userid)
    {
        $user = User::find($userid);
        $user->delete();
        return redirect('/users')->with('status', 'User Deleted successfully');
    }
}
