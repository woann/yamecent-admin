<?php

namespace App\Http\Controllers;

use App\AdminUser;
use App\Utility\Install;
use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

class InstallController extends Controller
{
    const DEFAULT_MYSQL_PORT = 3306;
    //
    public function index(Request $request)
    {
        if (Install::hasLock()) {
            return Container::getInstance()
                ->make('redirect')
                ->to('/login');
        } else {
            return view('base.install', ['errorMsg' => '']);
        }
    }

    public function setEnviroment(Request $request)
    {
        $host = $request->input('mysqlHost', 'localhost');
        if (strpos($host, ':') !== false) {
            list($host, $port) = explode(':', $host);
        } else {
            $port = self::DEFAULT_MYSQL_PORT;
        }
        $port = intval($port);
        $port = $port ? $port : self::DEFAULT_MYSQL_PORT;

        $mysqlUsername = $request->input('mysqlUsername', 'homestead');
        $mysqlPassword = $request->input('mysqlPassword', 'secret');
        $mysqlDatabase = $request->input('mysqlDatabase', 'homestead');
        $mysqlPrefix   = $request->input('mysqlPrefix', '');

        //将配置写入env
        $data = new Collection([
            'DB_PORT'     => $port,
            'DB_DATABASE' => $mysqlDatabase,
            'DB_USERNAME' => $mysqlUsername,
            'DB_PASSWORD' => $mysqlPassword,
            'DB_HOST'     => $host,
            'DB_PREFIX'   => $mysqlPrefix,
        ]);
        $contents = Install::getEnvFileDic();
        $contents = $contents->merge($data);

        Install::saveEnvFileDic($contents);

        return $this->json(200, 'success');
    }

    public function startInstall(Request $request)
    {
        $adminUsername      = $request->input('adminUsername', 'admin');
        $adminPassword      = $request->input('adminPassword', 'admin');
        $adminPasswordAgain = $request->input('adminPasswordAgain', 'admin');

        if ($adminPassword !== $adminPasswordAgain) {
            return $this->json(400, '管理员密码不一致');
        }

        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');

        // 账户信息修改
        $admin           = AdminUser::find(1);
        $admin->account  = $adminUsername;
        $admin->password = $adminPassword;
        $admin->save();

        // 安装锁
        Install::lock();
        return $this->json(200, 'success');
    }
}
