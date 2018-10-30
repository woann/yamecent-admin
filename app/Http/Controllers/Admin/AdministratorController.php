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
            ->get();
        foreach ($list as $k=>$v){
            $list[$k]->role = $this->getMenuRole($v->id);
            $list[$k]->child = DB::table('admin_menu')
                ->where('pid',$v->id)
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
            $parent_menu = DB::table('admin_menu')->where('pid',0)->get();
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
            $parent_menu = DB::table('admin_menu')->where('pid',0)->get();
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
            $my_permission = DB::table('admin_role_permission')->select('id')->where('role_id',$id)->get();
            $permission_list = DB::table('admin_permission')->get();
            $my_permission_ids = [];
            foreach ($my_permission as $k=>$v){
                $my_permission_ids[] = $v->id;
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
            return $this->json(500,'删除失败');
        }
        return $this->json(200,'删除成功');
    }
    /**
     * @Desc: 登录
     * @Author: woann <304550409@qq.com>
     */
    public function login()
    {

    }

    /**
     * @Desc: 添加管理员
     * @Author: woann <304550409@qq.com>
     */
    public function addAdministrator()
    {

    }

}