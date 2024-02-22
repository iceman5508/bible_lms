<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoleRequest;
use App\Traits\ApiStatusTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use DB;
use Auth;

class RoleController extends Controller
{
    use ApiStatusTrait;

    public function index()
    {
        if (!Auth::user()->hasPermissionTo('user_management', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['roles'] = Role::paginate(25);
       
        return $this->success($data);
    }

    public function create()
    {
        if (!Auth::user()->hasPermissionTo('user_management', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['roles'] = Role::all();
        $data['permissions'] = Permission::all();
        return $this->success($data);
    }

    public function store(RoleRequest $request)
    {
        if (!Auth::user()->hasPermissionTo('user_management', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $role = Role::create([
            'name' => $request->name,
            'guard_name' => 'web'
        ]);
        $role->givePermissionTo($request->permissions);

        return $this->success([], __("Role created successfully"));
    }

    public function edit($id)
    {
        if (!Auth::user()->hasPermissionTo('user_management', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
        } // end permission checking

        $data['role'] = Role::find($id);
        $data['permissions'] = Permission::all();
        $data['selected_permissions'] = DB::table('role_has_permissions')->where('role_id', $id)->select('permission_id')->pluck('permission_id')->toArray();
        return $this->success($data);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()->hasPermissionTo('user_management', 'web')) {
            return $this->error([], 'Unauthorize access', 403);
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

        return $this->success([], __("Role updated successfully"));
    }
}
