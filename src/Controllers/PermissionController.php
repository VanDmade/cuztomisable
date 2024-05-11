<?php

namespace VanDmade\Cuztomisable\Controllers;

use Illuminate\Http\Request;
use VanDmade\Cuztomisable\Requests\PermissionRequest;
use VanDmade\Cuztomisable\Requests\TableRequest;
use VanDmade\Cuztomisable\Models\Permission;
use Auth;
use DB;
use Exception;

class PermissionController extends Controller
{

    public function get($id)
    {
        try {
            $permission = Permission::select('id', 'name', 'slug', 'description', 'created_by')
                ->with([
                    'createdBy' => fn($query)  => $query->select('id', 'first_name as name'),
                    'roles' => fn($query) => $query->select('id', 'name', 'slug', 'description'),
                ])
                ->where('id', '=', $id)
                ->withTrashed()
                ->first();
            if (!isset($permission->id)) {
                throw new Exception(__('cuztomisable/permission.errors.not_found'), 404);
            }
            return $this->success([
                'permission' => $permission,
                'deleted' => $permission->trashed(),
            ]);
        } catch (Exception $error) {
            return $this->error($error);
        }
    }

    public function table(TableRequest $request)
    {
        try {
            $data = $request->validated();
            $query = null;
            $parameters = [];
            return $this->table($query, $data, $parameters);
        } catch (Exception $error) {
            return $this->error($error);
        }
    }

    public function save(PermissionRequest $request, $id = null)
    {
        try {
            $data = $request->validated();
            if (is_null($id)) {
                $permission = new Permission(); 
            } else {
                $permission = Permission::find($id);
                if (!isset($permission->id)) {
                    throw new Exception(__('cuztomisable/permission.errors.not_found'), 404);
                }
            }
            $permission->name = $data['name'];
            $permission->slug = $data['slug'];
            $permission->description = $data['description'];
            $permission->save();
            return $this->success([
                'message' => '',
            ]);
        } catch (Exception $error) {
            return $this->error($error);
        }
    }

    public function toggleDelete($id)
    {
        try {
            $permission = Permission::where('id', '=', $id)->withTrashed()->first();
            if (!isset($permission->id)) {
                throw new Exception(__('cuztomisable/permission.errors.not_found'), 404);
            }
            $deleted = $permission->trashed();
            // Resets OR sets the deleted at parameters for soft deletion
            $permission->deleted_by = $deleted ? null : Auth::user()->id;
            $permission->deleted_at = $deleted ? null : date('Y-m-d H:i:s');
            $permission->save();
            return $this->success([
                'message' => '',
                'deleted' => $deleted,
            ]);
        } catch (Exception $error) {
            return $this->error($error);
        }
    }

    public function list($role = null)
    {
        try {
            $permissions = [];
            if (!is_null($role)) {
                // Gets the list of permissions that are associated with the role that is given
                $permissions = Roles\Permission::select('id', 'permission_id')
                    ->where('role_id', '=', $role)
                    ->get()
                    ->pluck('permission_id');
            }
            $list = Permission::select('id', 'name', 'slug as subtitle')
                ->where(function($query) use ($role, $permissions) {
                    if (!is_null($role)) {
                        $query->whereIn('id', $permissions);
                    }
                })
                ->get();
            return $this->success([
                'list' => $list,
            ]);
        } catch (Exception $error) {
            return $this->error($error);
        }
    }

}
