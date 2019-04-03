<?php
/**
 * Created by PhpStorm.
 * Author: woann <304550409@qq.com>
 * Date: 18-10-26下午1:23
 * Desc: 管理员
 */
namespace App\Http\Controllers\Admin;

use App\AdminMenu;
use App\AdminPermission;
use App\AdminRole;
use App\AdminUser;
use App\Http\Controllers\Controller;
use App\Utility\Rbac;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class AdministratorController extends Controller
{
    /**
     * @Desc: 菜单列表
     * @Author: woann <304550409@qq.com>
     * @return \Illuminate\View\View
     */
    public function menuList()
    {
        // 获取一级菜单
        return view('admin.menu', ['list' => AdminMenu::where('pid', 0)->get()]);
    }

    /**
     * @Desc: 添加菜单
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function menuAddView(Request $request)
    {
        $roles   = AdminRole::get();
        $topMenu = AdminMenu::where('pid', 0)->get();
        return view('admin.menu_add', ['roles' => $roles, 'top_menu' => $topMenu]);
    }

    public function menuAdd(Request $request)
    {
        $data  = $request->except(['role', 's']);
        $roles = new Collection($request->input('roles'));
        if ($roles->isEmpty()) {
            return $this->json(500, '未选择任何角色');
        }
        $menu = new AdminMenu();
        $menu->fill($data);
        $menu->save();
        // 保存菜单所属角色
        $roles->map(function ($roleId) use ($menu) {
            $role = AdminRole::find($roleId);
            $menu->roles()->attach($role);
        });
        return $this->json(200, '添加成功');
    }

    /**
     * @Desc: 修改菜单
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @param $id
     * @return \Illuminate\View\View
     */
    public function menuUpdateView(Request $request, $id)
    {
        $roles = AdminRole::get();
        $menu  = AdminMenu::findOrFail($id);
        $roles->map(function ($role) use ($menu) {
            $menu->roles->each(function ($mRole) use (&$role) {
                if ($mRole->id === $role->id) {
                    $role->checked = true;
                }
            });
            return $role;
        });
        $topMenu = AdminMenu::where('pid', 0)->get();
        return view('admin.menu_update', [
            'roles'    => $roles,
            'top_menu' => $topMenu,
            'menu'     => $menu,
        ]);
    }

    public function menuUpdate(Request $request, $id)
    {
        $menu  = AdminMenu::findOrFail($id);
        $roles = new Collection($request->input('roles'));
        if ($roles->isEmpty()) {
            return $this->json(500, '未选择任何角色');
        }
        // 基础信息更新
        $data = $request->except(['role', 's']);
        $menu->fill($data)->save();
        // 删除原有关联数据
        $menu->roles()->detach();
        // 重新关联数据
        $roles->each(function ($roleId) use ($menu) {
            $role = AdminRole::find($roleId);
            $menu->roles()->attach($role);
        });
        return $this->json(200, '修改成功');
    }

    /**
     * @Desc: 删除菜单
     * @Author: woann <304550409@qq.com>
     * @param $id
     * @return mixed
     */
    public function menuDel($id)
    {
        $menu = AdminMenu::findOrFail($id);
        $menu->roles()->detach();
        $menu->delete();
        return $this->json(200, '删除成功');
    }

    public function roleList()
    {
        return view('admin.role', [
            'list' => AdminRole::paginate(10),
        ]);
    }

    /**
     * @Desc: 添加角色
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function roleAddView(Request $request)
    {
        return view('admin.role_add', [
            'permissions' => AdminPermission::get(),
        ]);
    }

    public function roleAdd(Request $request)
    {
        $param = $request->post();
        $role  = new AdminRole();
        $role->fill($param);
        $role->save();
        if (isset($param['permissions'])) {
            (new Collection($param['permissions']))->map(function ($permissionId) use ($role) {
                $permission = AdminPermission::find($permissionId);
                $role->permissions()->attach($permission);
            });
        }
        return $this->json(200, "添加成功");
    }

    /**
     * @Desc: 修改角色
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @param $id
     * @return \Illuminate\View\View
     */
    public function roleUpdateView(Request $request, $id)
    {
        $role        = AdminRole::findOrFail($id);
        $permissions = AdminPermission::get();
        $permissions->map(function ($permission) use ($role) {
            $permission->checked = false;
            $role->permissions->each(function ($rPermission) use ($role, &$permission) {
                if ($rPermission->id === $permission->id) {
                    $permission->checked = true;
                    return false;
                }
            });
            return $permission;
        });
        return view('admin.role_update', ['role' => $role, 'permissions' => $permissions]);
    }

    public function roleUpdate(Request $request, $id)
    {
        $param = $request->post();
        $role  = AdminRole::findOrFail($id);
        $role->fill($param);
        $role->save();
        // 删除所有权限关联
        $role->permissions()->detach();
        // 录入权限关联
        if (isset($param['permissions'])) {
            (new Collection($param['permissions']))->map(function ($permissionId) use ($role) {
                $permission = AdminPermission::find($permissionId);
                $role->permissions()->attach($permission);
            });
        }
        return $this->json(200, "修改成功");

    }

    /**
     * @Desc: 删除角色
     * @Author: woann <304550409@qq.com>
     * @param $id
     * @return mixed
     */
    public function roleDel($id)
    {
        if ($id == 1) {
            return $this->json(500, '超级管理员不可删除');
        }
        $role = AdminRole::findOrFail($id);
        // 删除所有多对多关系
        $role->users()->detach();
        $role->menus()->detach();
        $role->permissions()->detach();
        $role->delete();
        return $this->json(200, '删除成功');
    }
    /**
     * @return mixed
     * 权限列表
     */
    public function permissionList()
    {
        return view('admin.permission', [
            'list' => AdminPermission::get(),
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     * 添加权限
     */
    public function permissionAddView(Request $request)
    {
        //渲染页面
        $routes = Rbac::getAllRoutes();
        return view('admin.permission_add', ['routes' => $routes]);
    }

    public function permissionAdd(Request $request)
    {
        $data       = $request->post();
        $permission = new AdminPermission();
        $permission->fill($data);
        $permission->save();
        return $this->json(200, '添加成功');
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     * 修改权限
     */
    public function permissionUpdateView(Request $request, $id)
    {
        $permission  = AdminPermission::findOrFail($id);
        $rbacRoutes  = Rbac::getAllRoutes();
        $checkRoutes = $permission->routes->map(function ($route) {
            $routeObj           = new \StdClass();
            $routeObj->rbacRule = $route;
            return $routeObj;
        });
        $uncheckRoutes = new Collection();
        $rbacRoutes->each(function ($route) use ($permission, $checkRoutes, &$uncheckRoutes) {
            $uncheckFlag = true;
            $checkRoutes->each(function ($checkRoute) use ($route, &$uncheckFlag) {
                if ($route->rbacRule === $checkRoute->rbacRule) {
                    $uncheckFlag = false;
                }
            });
            if ($uncheckFlag) {
                $uncheckRoutes->push($route);
            }
        });
        return view('admin.permission_update', [
            'permission'     => $permission,
            'uncheck_routes' => $uncheckRoutes,
            'check_routes'   => $checkRoutes,
        ]);
    }

    public function permissionUpdate(Request $request, $id)
    {
        $data       = $request->post();
        $permission = AdminPermission::findOrFail($id);
        $permission->fill($data);
        $permission->save();
        return $this->json(200, '修改成功');

    }

    /**
     * @return mixed
     * 删除权限
     */
    public function permissionDel($id)
    {
        $permission = AdminPermission::findOrFail($id);
        // 解除所有多对多关系
        $permission->roles()->detach();
        $permission->delete();
        return $this->json(200, '删除成功');
    }

    /**
     * @return mixed
     * 管理员列表
     */
    public function administratorList()
    {
        return view('admin.administrator', [
            'admins' => AdminUser::paginate(10),
        ]);
    }

    /**
     * @param Request $request
     * @return mixed
     * 添加管理员
     */
    public function administratorAddView(Request $request)
    {
        $roles = AdminRole::select('id', 'name')->get();
        return view('admin.administrator_add', ['roles' => $roles]);
    }
    public function administratorAdd(Request $request)
    {
        $post  = $request->post();
        $roles = (new Collection($request->post('roles')));
        if (AdminUser::isExist($post['account'])) {
            return $this->json(500, '该账号已存在');
        }
        $admin = new AdminUser();
        $admin->fill($post);
        $admin->save();
        $roles->map(function ($roleId) use ($admin) {
            $role = AdminRole::find($roleId);
            $admin->roles()->attach($role);
        });
        return $this->json(200, '添加成功');
    }

    public function administratorUpdateView(Request $request, $id)
    {
        $roles           = AdminRole::select('id', 'name')->get();
        $admin           = AdminUser::findOrFail($id);
        $selectRoleIdArr = [];
        $admin->roles->map(function ($role) use (&$selectRoleIdArr) {
            $selectRoleIdArr[] = $role->id;
        });
        return view('admin.administrator_update', [
            'admin'         => $admin,
            'roles'         => $roles,
            's_role_id_arr' => $selectRoleIdArr,
        ]);
    }

    public function administratorUpdate(Request $request, $id)
    {
        $post  = $request->post();
        $roles = (new Collection($request->post('roles')));
        $admin = AdminUser::findOrFail($id);
        if ($admin->isExistForUpdate($post['account'])) {
            return $this->json(500, '该账号已存在');
        }
        $admin->fill($post)->save();
        // 删除用户的所有关联角色
        $admin->roles()->detach();
        $roles->map(function ($roleId) use ($admin) {
            $role = AdminRole::find($roleId);
            $admin->roles()->attach($role);
        });
        return $this->json(200, '修改成功');

    }

    /**
     * @return mixed
     * 删除管理员
     */
    public function administratorDel($id)
    {
        $admin = AdminUser::findOrFail($id);
        // 解除管理员角色多对多关系
        $admin->roles()->detach();
        $admin->delete();
        return $this->json(200, '删除成功');
    }

    /**
     * @param Request $request
     * @return mixed
     * 后台登录
     */
    public function login(Request $request)
    {
        return view('admin.login');
    }

    public function checkLogin(Request $request)
    {
        $post = $request->post();
        if (empty($post['account'])) {
            return $this->json(500, '请输入账号!');
        }
        if (empty($post['password'])) {
            return $this->json(500, '请输入密码!');
        }
        $admin = AdminUser::where('account', $post['account'])->first();
        if (empty($admin)) {
            return $this->json(500, '账号不存在!');
        }
        if (!password_verify($post['password'], $admin->password)) {
            return $this->json(500, '密码输入不正确!');
        }
        $request->session()->put('admin', $admin);
        return $this->json(200, '登录成功!');

    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     * 修改信息
     */
    public function editInfoView(Request $request, $id)
    {
        return view('admin.edit_info', ['admin' => AdminUser::findOrFail($id)]);
    }

    public function editInfo(Request $request, $id)
    {
        $post  = $request->post();
        $admin = AdminUser::findOrFail($id);
        $admin->fill($post);
        $admin->save();
        $request->session()->put('admin', $admin);
        return $this->json(200, '修改成功');
    }

    /**
     * @param Request $request
     * @return mixed
     * 退出登录
     */
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect('/login');
    }

}
