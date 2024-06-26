<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    // function __construct()
    // {
    //      $this->middleware('permission:Create Permission|Edit Permission|Delete Permission', ['only' => ['index','store','destroy']]);
    //      $this->middleware('permission:Create Permission', ['only' => ['create','store']]);
    //      $this->middleware('permission:Edit Permission', ['only' => ['edit','update']]);
    //      $this->middleware('permission:Delete Permission', ['only' => ['destroy']]);
    // }
    function __construct()
    {
        $this->middleware('permission:View Permission', ['only' => ['index']]);
        $this->middleware('permission:Create Permission', ['only' => ['create','store']]);
        $this->middleware('permission:Edit Permission', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Permission', ['only' => ['destroy']]);
    }
    public function index(Request $request){
        $permissions = Permission::get();
        return view('roles-permission.permissions.index',['permissions'=>$permissions]);
    }
    public function create(){
        return view('roles-permission.permissions.create');
    }
    public function store(Request $request){
        $request->validate([
            'name'=> [
                'required',
                'string',
                'unique:permissions,name',
            ]
        ]);
        Permission::create(['name' => $request->name]);
        return redirect()->route('permissions')->with('status','permission created succesfully!');
    }
    public function edit($permissonid){
        $permission = Permission::find($permissonid);
        return view('roles-permission.permissions.edit', compact('permission'));
    }
   
    public function update(Request $request , $permissonid){
        $this->validate($request, [
            'name' => 'required'
        ]);
    
        $role = Permission::find($permissonid);
        $role->update($request->all());
        return redirect()->route('permissions')->with('status','Permission Updated succesfully!');
    }
    public function destroy($permissonid){
        $role = Permission::find($permissonid);
        $role->delete();
        return redirect()->route('permissions')->with('status','Permission Deleted succesfully!');
    }

}
