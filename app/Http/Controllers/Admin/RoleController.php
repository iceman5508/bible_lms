<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Traits\General;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;
use Auth;

class RoleController extends Controller
{
    use General;

    public function index()
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking


        $data['title'] = 'Manage Roles';
        $data['roles'] = Role::paginate(25);
        $data['navUserParentActiveClass'] = 'mm-active';
        $data['navUserParentShowClass'] = 'mm-show';
        $data['subNavUserRoleActiveClass'] = 'mm-active';
        return view('admin.user.role.index', $data);
    }

    public function create()
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Add Role';
        $data['roles'] = Role::all();
        $data['permissions'] = Permission::all();
        $data['navUserParentActiveClass'] = 'mm-active';
        $data['navUserParentShowClass'] = 'mm-show';
        $data['subNavUserRoleActiveClass'] = 'mm-active';
        return view('admin.user.role.create', $data);
    }

    public function store(RoleRequest $request)
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);
        $role->givePermissionTo($request->permissions);
        return $this->controlRedirection($request, 'role', 'Role');
    }

    public function edit($id)
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $data['title'] = 'Edit Role';
        $data['role'] = Role::find($id);
        $data['permissions'] = Permission::all();
        $data['selected_permissions'] = DB::table('role_has_permissions')->where('role_id', $id)->select('permission_id')->pluck('permission_id')->toArray();
        $data['navUserParentActiveClass'] = 'mm-active';
        $data['navUserParentShowClass'] = 'mm-show';
        $data['subNavUserRoleActiveClass'] = 'mm-active';
        return view('admin.user.role.edit', $data);

    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
            'permissions' => ['required', 'array', 'min:1'],
        ]);

        $role = Role::find($id);
        $role->name = $request->name;
        $role->save();

        DB::table('role_has_permissions')->where('role_id', $id)->delete();
        $role->givePermissionTo($request->permissions);
        Artisan::call('cache:clear');

        return $this->controlRedirection($request, 'role', 'Role');
    }

    public function delete($id)
    {
        if (!Auth::user()->can('user_management')) {
            abort('403');
        } // end permission checking

        $role = Role::find($id);
        DB::table('role_has_permissions')->where('role_id', $id)->delete();
        $role->delete();

        $this->showToastrMessage('error', __('Role has been deleted'));
        return redirect()->back();
    }


}
