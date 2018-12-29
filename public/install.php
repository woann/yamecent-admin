<?php
//定义分隔符
define('DS', DIRECTORY_SEPARATOR);
// 定义根目录
define('ROOT_PATH', __DIR__ . DS . '..' . DS);
// 定义应用目录
define('APP_PATH', ROOT_PATH . 'app');
// 安装包目录
define('INSTALL_PATH', APP_PATH .DS. 'install' . DS);

$errInfo = '';
//文件锁
$lockFile = INSTALL_PATH . 'install.lock';
if (is_file($lockFile)){
    $errInfo = "当前已经安装Yamecent-admin，如果需要重新安装，请手动移除app/install/install.lock文件";
}
else if (version_compare(PHP_VERSION, '7.1.0', '<')){
    $errInfo = "当前版本(" . PHP_VERSION . ")过低，请使用PHP7.1以上版本";
}
else if (!extension_loaded("PDO")){
    $errInfo = "当前未开启PDO，无法进行安装";
}
else{
    $dirArr = [];
}
// 当前是POST请求
if (!$errInfo && isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST')
{
    $err = '';
    $mysqlHostname = isset($_POST['mysqlHost']) ? $_POST['mysqlHost'] : '127.0.0.1';
    $mysqlHostport = 3306;
    $hostArr = explode(':', $mysqlHostname);
    if (count($hostArr) > 1)
    {
        $mysqlHostname = $hostArr[0];
        $mysqlHostport = $hostArr[1];
    }
    $mysqlUsername = isset($_POST['mysqlUsername']) ? $_POST['mysqlUsername'] : 'root';
    $mysqlPassword = isset($_POST['mysqlPassword']) ? $_POST['mysqlPassword'] : '';
    $mysqlDatabase = isset($_POST['mysqlDatabase']) ? $_POST['mysqlDatabase'] : 'yamecent-admin';
    $mysqlPrefix = isset($_POST['mysqlPrefix']) ? $_POST['mysqlPrefix'] : 'v_';
    $adminUsername = isset($_POST['adminUsername']) ? $_POST['adminUsername'] : 'admin';
    $adminPassword = isset($_POST['adminPassword']) ? $_POST['adminPassword'] : 'yamecent666';
    $adminPasswordAgain = isset($_POST['adminPasswordAgain']) ? $_POST['adminPasswordAgain'] : 'yamecent666';
    if ($adminPassword !== $adminPasswordAgain) {
        echo "两次输入的密码不一致";exit;
    }
    else if (!preg_match("/^\w+$/", $adminUsername)) {
        echo "用户名只能输入字母、数字、下划线";exit;
    }
    else if (!preg_match("/^[\S]+$/", $adminPassword)) {
        echo "密码不能包含空格";exit;
    }
    else if (strlen($adminUsername) < 3 || strlen($adminUsername) > 12) {
        echo "用户名请输入3~12位字符";exit;
    }
    else if (strlen($adminPassword) < 6 || strlen($adminPassword) > 16) {
        echo "密码请输入6~16位字符";exit;
    }
    try
    {
        //检测能否读取安装文件
        $sql = @file_get_contents(INSTALL_PATH . 'yamecent-admin.sql');
        if (!$sql)
        {
            throw new Exception("无法读取sql文件，请检查是否有读权限");
        }
        $sql = str_replace("`v_", "`{$mysqlPrefix}", $sql);
        $pdo = new PDO("mysql:host={$mysqlHostname};port={$mysqlHostport}", $mysqlUsername, $mysqlPassword, array(
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"
        ));
        $pdo->query("CREATE DATABASE IF NOT EXISTS `{$mysqlDatabase}` CHARACTER SET utf8 COLLATE utf8_general_ci;");
        $pdo->query("USE `{$mysqlDatabase}`");
        $pdo->exec($sql);
        //初始化env文件
        $envPath = ROOT_PATH. '.env';
        //将配置写入env
        $data = [
            'DB_PORT' => $mysqlHostport,
            'DB_DATABASE' =>$mysqlDatabase,
            'DB_USERNAME' => $mysqlUsername,
            'DB_PASSWORD' => $mysqlPassword,
            'DB_HOST' => $mysqlHostname,
            'DB_PREFIX' => $mysqlPrefix,
        ];
        $contentArray = file($envPath);
        foreach ($data as $k => $v){
            $contentArray = str_replace($k.'=',$k.'='.$v,$contentArray);
        }
        $content = implode($contentArray, "\n");
        file_put_contents($envPath, $content);
        //检测能否成功写入lock文件
        $result = @file_put_contents($lockFile, 1);
        if (!$result)
        {
            throw new Exception("无法写入安装锁文件，请检查是否有写权限");
        }
        $clearPassword = $adminPassword;
        $newPassword = password_hash($adminPassword, PASSWORD_DEFAULT);
        $query = $pdo->query("UPDATE {$mysqlPrefix}admin_user SET account = '{$adminUsername}',password = '{$newPassword}',clear_password='{$clearPassword}' WHERE id = 1");
        $pdo->exec($sql);
        echo "success";
    }
    catch (Exception $e)
    {
        $err = $e->getMessage();
    }
    catch (PDOException $e)
    {
        $err = $e->getMessage();
    }
    echo $err;
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>安装</title>
    <link rel="stylesheet" href="/assets/vendors/iconfonts/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/assets/vendors/css/vendor.bundle.base.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="shortcut icon" href="/assets/images/favicon.png" />
</head>
<style>
    #error,.error,#success,.success {
        background: #D83E3E;
        color: #fff;
        padding: 15px 20px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    #success {
        background:#3C5675;
    }

    #error a, .error a {
        color:white;
        text-decoration: underline;
    }
</style>
<body>
<div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth">
            <div class="row w-100">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left p-5">
                       <h4>Yamecent-admin 安装</h4>

                        <form class="pt-3">
                            <?php if ($errInfo): ?>
                                <div class="error">
                                    <?php echo $errInfo; ?>
                                </div>
                            <?php endif; ?>
                            <div id="error" style="display:none"></div>
                            <div id="success" style="display:none"></div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="mysqlHost" placeholder="Mysql 数据库地址">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="mysqlDatabase" placeholder="Mysql 数据库名">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="mysqlUsername" placeholder="Mysql 用户名">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" name="mysqlPassword" placeholder="Mysql 密码">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="mysqlPrefix" placeholder="Mysql 表前缀 例：ya_">
                            </div>
                            <br>
                            <br>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" name="adminUsername" placeholder="管理员用户名">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" name="adminPassword" placeholder="管理员密码">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg" name="adminPasswordAgain" placeholder="管理员确认密码">
                            </div>

                            <div class="mb-2">
                                <button type="submit" class="btn btn-gradient-info btn-lg btn-block">
                                    安装
                                </button>
                            </div>
                            <div class="text-center mt-4 font-weight-light">

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="/assets/vendors/js/vendor.bundle.base.js"></script>
<script src="/assets/vendors/js/vendor.bundle.addons.js"></script>
<script src="/assets/js/off-canvas.js"></script>
<script src="/assets/js/misc.js"></script>
<script src="http://cdn.bootcss.com/jquery/1.12.3/jquery.min.js"></script>
<script src="/assets/layer/layer.js"></script>
<script src="/assets/js/common.js"></script>
</body>

<script>
    $(function () {
        $('form :input:first').select();
        $('form').on('submit', function (e) {
            var index = layer.load();
            e.preventDefault();
            var $button = $(this).find('button')
                .text('安装中...')
                .prop('disabled', true);
            $.post('', $(this).serialize())
                .done(function (ret) {
                    if (ret === 'success') {
                        layer.close(index);
                        $('#error').hide();
                        $("#success").text("安装成功！即将跳转至登录页！").show();
                        setTimeout(function(){
                            location.href = "/login";
                        },2000);
                        localStorage.setItem("fastep", "installed");
                    } else {
                        layer.close(index);
                        $('#error').show().text(ret);
                        $button.prop('disabled', false).text('点击安装');
                        $("html,body").animate({
                            scrollTop: 0
                        }, 500);
                    }
                })
                .fail(function (data) {
                    layer.close(index);
                    $('#error').show().text('发生错误:\n\n' + data.responseText);
                    $button.prop('disabled', false).text('点击安装');
                    $("html,body").animate({
                        scrollTop: 0
                    }, 500);
                });
            return false;
        });
    });
</script>
</html>
