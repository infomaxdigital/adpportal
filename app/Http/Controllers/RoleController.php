<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;

class RoleController extends Controller
{
    //
    function __construct()
    {
        $this->middleware('permission:View Role', ['only' => ['index']]);
        $this->middleware('permission:Create Role', ['only' => ['create','store']]);
        $this->middleware('permission:Edit Role', ['only' => ['edit','update']]);
        $this->middleware('permission:Delete Role', ['only' => ['destroy']]);
    }
    public function index(Request $request){
        $roles = Role::get();
        return view('roles-permission.roles.index',['roles'=>$roles]);
    }
    public function create(){
        return view('roles-permission.roles.create');
    }
    public function store(Request $request){
        $request->validate([
            'name'=> [
                'required',
                'string',
                'unique:roles,name',
            ]
        ]);
        Role::create(['name' => $request->name]);
        return redirect()->route('roles')->with('status','Role created succesfully!');
    }
    public function edit($roleid){
        $role = Role::find($roleid);
        return view('roles-permission.roles.edit', compact('role'));
    }
   
    public function update(Request $request , $roleid){
        $this->validate($request, [
            'name' => 'required'
        ]);
    
        $role = Role::find($roleid);
        $role->update($request->all());
        return redirect()->route('roles')->with('status','Role Updated succesfully!');
    }
    public function destroy($roleid){
        $role = Role::find($roleid);
        $role->delete();
        return redirect()->route('roles')->with('status','Role Deleted succesfully!');
    }

    public function addPermissionToRole($roleid){
        $permissions = Permission::get();
        $role = Role::find($roleid);
        $rolePermissions = DB::table('role_has_permissions')
                                ->where('role_has_permissions.role_id', $role->id)
                                ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')
                                ->all();

        return view('roles-permission.roles.add-permissions', compact('role','permissions','rolePermissions'));
    }

    public function givePermissionToRole(Request $request , $roleid){
        $request->validate([
            'permission' => 'required'
        ]);
        $role = Role::find($roleid);
        $role->syncPermissions($request->permission);

        return redirect()->back()->with('status','Permissions added to role');

    }

}
