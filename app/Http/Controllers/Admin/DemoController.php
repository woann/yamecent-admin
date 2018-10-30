<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class DemoController extends Controller
{

    /**
     * @return mixed
     * 权限列表
     */
    public function permissionList(){
        $list = DB::table('admin_permission')->get();
        if(count($list)){
            foreach ($list as $k => $v){
                $list[$k]->route = explode(',',$v->route);
            }
        }
        return view('admin.permission',['list'=>$list]);
    }


    /**
     * @param Request $request
     * @return mixed
     * 添加权限
     */
    public function permissionAdd(Request $request){

        if($request->isMethod('post')){
            //添加数据
            $data = $request->post();
            $time = date("Y-m-d H:i:s");
            $data["created_at"] = $time;
            $data["updated_at"] = $time;
            $res = DB::table('admin_permission')->insert($data);
            if(!$res){
                return $this->json(500,'添加失败');
            }
            return $this->json(200,'添加成功');
        }else{
            //渲染页面
            $app = app();
            $routes = $app->routes->getRoutes();
            foreach ($routes as $k=>$value){
                $path[$k] = $value->uri;
            }
            return view('admin.permission_add',['path'=>$path]);
        }
    }


    /**
     * @param Request $request
     * @param $id
     * @return mixed
     * 修改权限
     */
    public function permissionUpdate(Request $request,$id){
        if($request->isMethod('post')){
            $data = $request->post();
            $data["updated_at"] = date("Y-m-d H:i:s");
            $res = DB::table('admin_permission')->where('id',$id)->update($data);
            if(!$res){
                return $this->json(500,'修改失败');
            }
            return $this->json(200,'修改成功');
        }else{
            $app = app();
            $routes = $app->routes->getRoutes();
            foreach ($routes as $k=>$value){
                $path[$k] = $value->uri;
            }
            $res = DB::table('admin_permission')->find($id);
            $right_arr = explode(',',$res->route);
            $left_arr = array_diff($path,$right_arr);
            return view('admin.permission_update',['res'=>$res,'left_arr'=>$left_arr,'rignt_arr'=>$right_arr]);
        }
    }


    /**
     * @return mixed
     * 删除权限
     */
    public function permissionDel($id){
        $res = DB::table('admin_permission')->delete($id);
        DB::table('admin_role_permission')->where('permission_id',$id)->delete();
        if(!$res){
            return $this->json(500,'删除失败');
        }
        return $this->json(200,'删除成功');
    }


    /**
     * @return mixed
     * 管理员列表
     */
    public function administratorList(){
        $list = DB::table('admin_user as au')
            ->leftJoin('admin_user_role as aur','au.id','=','aur.admin_user_id')
            ->leftJoin('admin_role as ar','ar.id','=','aur.role_id')
            ->select('au.*','ar.name as role')
            ->get();
        return view('admin.administrator',['list'=>$list]);
    }


    /**
     * @param Request $request
     * @return mixed
     * 添加管理员
     */
    public function administratorAdd(Request $request){
        if($request->isMethod('post')){
            $post = $request->post();
            $count = DB::table('admin_user')->where('account',$post['account'])->count();
            if($count){
                return $this->json(500,'该账号已存在');
            }
            $data = [
              'avatar'=>$post['avatar'],
              'nickname'=>$post['nickname'],
              'account'=>$post['account'],
              'clear_password'=>$post['password'],
              'password'=>password_hash($post['password'], PASSWORD_DEFAULT),
            ];
            $id = DB::table('admin_user')->insertGetId($data);
            if(!$id){
                return $this->json(500,'管理员添加失败');
            }
            $user_role = [
                'admin_user_id'=>$id,
                'role_id'=>$post['role'],
            ];
            $res = DB::table('admin_user_role')->insert($user_role);
            if(!$res){
                return $this->json(500,'管理员角色添加失败');
            }
            return $this->json(200,'添加成功');
        }else{
            $role = DB::table('admin_role')->select('id','name')->get();
            return view('admin.administrator_add',['role'=>$role]);
        }
    }


    public function administratorUpdate(Request $request,$id){
        if($request->isMethod('post')){
            $post = $request->post();
            $count = DB::table('admin_user')
                ->where('id','!=',$id)
                ->where('account',$post['account'])
                ->count();
            if($count){
                return $this->json(500,'该账号已存在');
            }
            $data = [
                'avatar'=>$post['avatar'],
                'nickname'=>$post['nickname'],
                'account'=>$post['account'],
                'clear_password'=>$post['password'],
                'password'=>password_hash($post['password'], PASSWORD_DEFAULT),
            ];
            DB::table('admin_user')->where('id',$id)->update($data);
            $user_role = [
                'admin_user_id'=>$id,
                'role_id'=>$post['role'],
            ];
            DB::table('admin_user_role')->where('admin_user_id',$id)->update($user_role);
            return $this->json(200,'修改成功');
        }else{
            $role = DB::table('admin_role')->select('id','name')->get();
            $res = DB::table('admin_user')->find($id);
            $user_role = DB::table('admin_user_role')->select('role_id')->where('admin_user_id',$id)->first();
            if($user_role){
                $user_role_id = $user_role->role_id;
            }else{
                $user_role_id = 0;
            }

            return view('admin.administrator_update',['res'=>$res,'role'=>$role,'role_id'=>$user_role_id]);
        }
    }

    /**
     * @return mixed
     * 删除管理员
     */
    public function administratorDel($id){
        $res = DB::table('admin_user')->delete($id);
        if(!$res){
            return $this->json(500,'删除失败');
        }
        return $this->json(200,'删除成功');
    }


    /**
     * @param Request $request
     * @return mixed
     * 后台登录
     */
    public function login(Request $request){
        if($request->isMethod('post')){
            $post = $request->post();
            if(empty($post['account'])){
                return $this->json(500,'请输入账号!');
            }
            if(empty($post['password'])){
                return $this->json(500,'请输入密码!');
            }
            $admin = DB::table('admin_user')->where('account', $post['account'])->first();
            if(empty($admin)){
                return $this->json(500,'账号不存在!');
            }
            if(!password_verify ( $post['password'] , $admin->password)){
                return $this->json(500,'密码输入不正确!');
            };
            $request->session()->put('admin', $admin);
            return $this->json(200,'登录成功!');
        }else{
            return view('login');
        }
    }


    /**
     * @param Request $request
     * @param $id
     * @return mixed
     * 修改信息
     */
    public function editInfo(Request $request,$id){
        if($request->isMethod('post')){
            $post = $request->post();
            $data = [
                'avatar'=>$post['avatar'],
                'nickname'=>$post['nickname'],
                'clear_password'=>$post['password'],
                'password'=>password_hash($post['password'], PASSWORD_DEFAULT),
            ];
            $res = DB::table('admin_user')->where('id',$id)->update($data);
            if(!$res){
                return $this->json(500,'修改失败');
            }
            $admin = DB::table('admin_user')->where('id',$id)->first();
            $request->session()->put('admin', $admin);
            return $this->json(200,'修改成功');
        }else{
            $res = DB::table('admin_user')->find($id);
            return view('edit_info',['res'=>$res]);
        }
    }


    /**
     * @param Request $request
     * @return mixed
     * 退出登录
     */
    public function logout(Request $request){
        $request->session()->flush();
        return redirect('/login');
    }


}
