<?php
/**
 * Created by PhpStorm.
 * Author: woann <304550409@qq.com>
 * Date: 18-10-26下午1:23
 * Desc: 管理员
 */
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class AdministratorController extends Controller
{
    /**
     * @Desc: 菜单列表
     * @Author: woann <304550409@qq.com>
     * @return \Illuminate\View\View
     */
    public function menuList()
    {
        //获取一级菜单
        $list = DB::table('admin_menu')
            ->where('pid',0)
            ->orderBy('sort','DESC')
            ->get();
        foreach ($list as $k=>$v){
            $list[$k]->role = $this->getMenuRole($v->id);
            $list[$k]->child = DB::table('admin_menu')
                ->where('pid',$v->id)
                ->orderBy('sort','DESC')
                ->get();
            foreach ($list[$k]->child as $key=>$val){
                $list[$k]->child[$key]->role = $this->getMenuRole($val->id);
            }
        }
        return view('admin.menu',['list'=>$list]);
    }

    public function getMenuRole($menu_id){
        $list = DB::table('admin_role_menu as rm')
            ->leftJoin('admin_role as r','r.id','=','rm.role_id')
            ->select('r.name')
            ->where('rm.menu_id',$menu_id)
            ->get();
        return $list;
    }

    /**
     * @Desc: 添加菜单
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function menuAdd(Request $request)
    {
        if($request->isMethod('post')){
            $data = $request->except(['role','s']);
            $roles = $request->input('role');
            if(!count($roles)){
                return $this->json(500,'未选择任何角色');
            }
            $data["created_at"] = date("Y-m-d H:i:s");
            $data["updated_at"] = date("Y-m-d H:i:s");
            $menu_id = DB::table('admin_menu')->insertGetId($data);
            if(!$menu_id){
                return $this->json(500,'添加失败');
            }
            $data = [];
            foreach ($roles as $k=>$v){
                $data[$k]["menu_id"] = $menu_id;
                $data[$k]["role_id"] = $v;
            }
            $res = DB::table('admin_role_menu')->insert($data);
            if(!$res){
                DB::table('admin_menu')->where('id',$menu_id)->delete();
                return $this->json(500,'添加失败');
            }
            return $this->json(200,'添加成功');
        }else{
            $role_list = DB::table('admin_role')->get();
            $parent_menu = DB::table('admin_menu')->where('pid',0)->orderBy('sort','DESC')->get();
            return view('admin.menu_add',['role_list'=>$role_list,'parent_menu'=>$parent_menu]);
        }
    }

    /**
     * @Desc: 修改菜单
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @param $id
     * @return \Illuminate\View\View
     */
    public function menuUpdate(Request $request,$id)
    {
        if($request->isMethod('post')){
            $data = $request->except(['role','s']);
            $roles = $request->input('role');
            if(!count($roles)){
                return $this->json(500,'未选择任何角色');
            }
            $data["updated_at"] = date("Y-m-d H:i:s");
            DB::table('admin_menu')->where('id',$id)->update($data);
            //删除原有关联数据
            DB::table('admin_role_menu')->where('menu_id',$id)->delete();
            $data = [];
            foreach ($roles as $k=>$v){
                $data[$k]["menu_id"] = $id;
                $data[$k]["role_id"] = $v;
            }
            //更新关联数据
            $res = DB::table('admin_role_menu')->insert($data);
            if(!$res){
                return $this->json(500,'修改失败');
            }
            return $this->json(200,'修改成功');
        }else{
            $role_list = DB::table('admin_role')->get();
            $my_role = DB::table('admin_role_menu')->where('menu_id',$id)->get();
            $my_role_ids = [];
            foreach ($my_role as $k=>$v){
                $my_role_ids[] = $v->role_id;
            }
            foreach ($role_list as $k=>$v){
                if(in_array($v->id,$my_role_ids)){
                    $role_list[$k]->checked = true;
                }else{
                    $role_list[$k]->checked = false;
                }
            }
            $parent_menu = DB::table('admin_menu')->where('pid',0)->orderBy('sort','DESC')->get();
            $res = DB::table('admin_menu')->find($id);
            return view('admin.menu_update',['role_list'=>$role_list,'parent_menu'=>$parent_menu,'res'=>$res]);
        }
    }

    /**
     * @Desc: 删除菜单
     * @Author: woann <304550409@qq.com>
     * @param $id
     * @return mixed
     */
    public function menuDel($id)
    {
        $res = DB::table('admin_menu')->delete($id);
        if(!$res){
            return $this->json(500,'删除失败');
        }
        DB::table('admin_role_menu')->where('menu_id',$id)->delete();
        return $this->json(200,'删除成功');
    }

    public function roleList()
    {
        $list = DB::table('admin_role')->paginate(10);
        return view('admin.role',['list'=>$list]);
    }

    /**
     * @Desc: 添加角色
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function roleAdd(Request $request)
    {
        if($request->isMethod("POST")){
            $param = $request->post();
            $data = [];
            $data["name"] = $param['name'];
            $data["des"] = $param['des'];
            $data["created_at"] = date("Y-m-d H:i:s");
            $data["updated_at"] = date("Y-m-d H:i:s");
            $role_id = DB::table('admin_role')->insertGetId($data);
            if(!$role_id){
                return $this->json(500,"添加失败");
            }
            $data = [];
            foreach ($param["permission"] as $k=>$v){
                $data[$k]["role_id"] = $role_id;
                $data[$k]["permission_id"] = $v;
            }
            $res = DB::table('admin_role_permission')->insert($data);
            if(!$res){
                DB::table('admin_role')->delete($role_id);
                return $this->json(500,"添加失败");
            }
            return $this->json(200,"添加成功");
        }else{
            $permission_list = DB::table('admin_permission')->get();
            return view('admin.role_add',['permission_list'=>$permission_list]);
        }
    }

    /**
     * @Desc: 修改角色
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @param $id
     * @return \Illuminate\View\View
     */
    public function roleUpdate(Request $request,$id)
    {
        if($request->isMethod("POST")){
            $param = $request->post();
            $data = [];
            $data["name"] = $param['name'];
            $data["des"] = $param['des'];
            $data["updated_at"] = date("Y-m-d H:i:s");
            DB::table('admin_role')->where('id',$id)->update($data);
            $data = [];
            foreach ($param["permission"] as $k=>$v){
                $data[$k]["role_id"] = $id;
                $data[$k]["permission_id"] = $v;
            }
            DB::table('admin_role_permission')->where('role_id',$id)->delete();
            $res = DB::table('admin_role_permission')->insert($data);
            if(!$res){
                return $this->json(500,"修改失败");
            }
            return $this->json(200,"修改成功");
        }else{
            $res = DB::table('admin_role')->find($id);
            $my_permission = DB::table('admin_role_permission')->select('permission_id')->where('role_id',$id)->get();
            $permission_list = DB::table('admin_permission')->get();
            $my_permission_ids = [];
            foreach ($my_permission as $k=>$v){
                $my_permission_ids[] = $v->permission_id;
            }
            foreach ($permission_list as $k=>$v){
               if(in_array($v->id,$my_permission_ids)){
                   $permission_list[$k]->checked = true;
               }else{
                   $permission_list[$k]->checked = false;
               }
            }
            return view('admin.role_update',['res'=>$res,'permission_list'=>$permission_list]);
        }
    }

    /**
     * @Desc: 删除角色
     * @Author: woann <304550409@qq.com>
     * @param $id
     * @return mixed
     */
    public function roleDel($id)
    {
        if($id == 1){
            return $this->json(500,'超级管理员不可删除');
        }
        $res = DB::table('admin_role')->delete($id);
        if(!$res){
            //删除该角色和权限的关联
            DB::table('admin_role_permission')->where('role_id',$id)->delete();
            //删除角色和管理员的关联
            DB::table('admin_user_role')->where('role_id',$id)->delete();
            return $this->json(500,'删除失败');
        }
        return $this->json(200,'删除成功');
    }
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
        if(!$res){
            return $this->json(500,'删除失败');
        }
        DB::table('admin_role_permission')->where('permission_id',$id)->delete();
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
        //删除关联
        DB::table('admin_user_role')->where('admin_user_id',$id)->delete();
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
            return view('admin.login');
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
            return view('admin.edit_info',['res'=>$res]);
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