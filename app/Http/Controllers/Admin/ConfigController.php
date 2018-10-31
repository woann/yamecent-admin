<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class ConfigController extends Controller
{
    /**
     * @Desc: 配置列表
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function configList(Request $request)
    {
        $list = DB::table('admin_config')->paginate(10);
        return view('admin.config',['list'=>$list]);
    }

    /**
     * @Desc: 添加配置
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function configAdd(Request $request)
    {
        if($request->isMethod("POST")){
            $data = $request->post();
            $data['created_at'] = date("Y-m-d H:i:s");
            $data['updated_at'] = date("Y-m-d H:i:s");
            $res = DB::table('admin_config')->insert($data);
            if(!$res){
                return $this->json(500,'添加失败');
            }
            return $this->json(200,'添加成功');
        }else{
            return view("admin.config_add");
        }
    }

    /**
     * @Desc: 修改配置信息
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @param $id
     * @return \Illuminate\View\View
     */
    public function configUpdate(Request $request,$id)
    {
        if($request->isMethod("POST")){
            $data = $request->post();
            $data['updated_at'] = date("Y-m-d H:i:s");
            $res = DB::table('admin_config')->where('id',$id)->update($data);
            if(!$res){
                return $this->json(500,'修改失败');
            }
            return $this->json(200,'修改成功');
        }else{
            $res = DB::table("admin_config")->find($id);
            return view("admin.config_update",["res"=>$res]);
        }
    }

    /**
     * @Desc: 删除配置
     * @Author: woann <304550409@qq.com>
     * @param $id
     * @return mixed
     */
    public function configDel($id)
    {
        $res = DB::table('admin_config')->delete($id);
        if(!$res){
            return $this->json(500,'删除失败');
        }
        return $this->json(200,'删除成功');
    }
}