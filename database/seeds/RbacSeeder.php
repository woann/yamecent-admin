<?php

use App\AdminPermission;
use App\AdminRole;
use App\AdminUser;
use App\Utility\Rbac;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

class RbacSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $adminUser = new AdminUser();
        $adminUser->fill([
            'avatar'   => '/uploads/avatar/20181031/5bd90252493d1.jpg',
            'nickname' => '最牛逼的程序员',
            'account'  => 'admin',
            'password' => 'admin',
        ]);
        $adminUser->save();

        $adminRole = new AdminRole();
        $adminRole->fill([
            'name'        => '超级管理员',
            'description' => '系统最高权限',
        ]);

        $adminUser->roles()->save($adminRole);

        $routes = Rbac::getAllRoutes()
            ->map(function ($route) {
                return $route->rbacRule;
            });
        $adminPerm = new AdminPermission();
        $adminPerm->fill([
            'name'   => '所有权限',
            'routes' => $routes,
        ]);

        $adminRole->permissions()->save($adminPerm);
    }
}
