<?php

use App\AdminPermission;
use App\AdminRole;
use App\AdminUser;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

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

        $routes = (new Collection(app()->routes->getRoutes()))
            ->filter(function ($route) {
                $actions = $route->getAction();
                return isset($actions['as']) && $actions['as'] === 'rbac';
            })
            ->map(function ($route) {
                return $route->uri;
            });
        $adminPerm = new AdminPermission();
        $adminPerm->fill([
            'name'   => '所有权限',
            'routes' => $routes,
        ]);

        $adminRole->permissions()->save($adminPerm);
    }
}
