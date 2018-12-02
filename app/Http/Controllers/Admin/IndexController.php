<?php

namespace App\Http\Controllers\Admin;
use App\Utility\Video;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Storage;
use DB;
class IndexController extends Controller
{
    public function index()
    {
        $admin = session('admin');
        $user_role = DB::table('admin_user_role')->where('admin_user_id',$admin->id)->first();
        $role_id = $user_role->role_id;
        $menu_list = DB::table('admin_role_menu as rm')
            ->leftJoin('admin_menu as m','m.id','=','rm.menu_id')
            ->where('rm.role_id',$role_id)
            ->where('m.pid',0)
            ->select('m.*')
            ->orderBy('m.sort','DESC')
            ->get();
        foreach ($menu_list as $k=>$v){
            $menu_list[$k]->child = DB::table('admin_role_menu as rm')
                            ->leftJoin('admin_menu as m','m.id','=','rm.menu_id')
                            ->where('rm.role_id',$role_id)
                            ->where('m.pid',$v->id)
                            ->select('m.*')
                            ->orderBy('m.sort','DESC')
                            ->get();
            if(count($menu_list[$k]->child)){
                $menu_list[$k]->has_child = true;
            }else{
                $menu_list[$k]->has_child = false;
            }
        }
        return view('admin.index',['menu'=>$menu_list]);
    }
    public function console()
    {
        return view('admin.console');
    }

    /**
     * @Desc: 后台图片上传
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @return mixed
     */
    public function upload(Request $request)
    {
        $file = $request->file('image');
        $path = $request->input('path').'/';
        if($file){
            if($file->isValid()) {
                $size = $file->getSize();
                if($size > 5000000){
                    return $this->json(500,'图片不能大于5M！');
                }
                // 获取文件相关信息
                $ext = $file->getClientOriginalExtension();     // 扩展名
                if(!in_array($ext,['png','jpg','gif','jpeg','pem']))
                {
                    return $this->json(500,'文件类型不正确！');
                }
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                // 上传文件
                $filename = $path.date('Ymd').'/'.uniqid() . '.' . $ext;
                // 使用我们新建的uploads本地存储空间（目录）
                $bool = Storage::disk('admin')->put($filename, file_get_contents($realPath));
                if($bool){
                    return $this->json(200,'上传成功',['filename'=>'/uploads/'.$filename]);
                }else{
                    return $this->json(500,'上传失败！');
                }
            }else{
                return $this->json(500,'文件类型不正确！');
            }
        }else{
            return $this->json(500,'上传失败！');
        }
    }

    /**
     * @Desc: 富文本上传图片
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     */
    public function wangeditorUpload(Request $request)
    {
        $file = $request->file('wangEditorH5File');
        if($file){
            if($file->isValid()) {
                // 获取文件相关信息
                $ext = $file->getClientOriginalExtension();     // 扩展名
                $realPath = $file->getRealPath();   //临时文件的绝对路径
                // 上传文件
                $filename = date('Ymd') . '/' . uniqid() . '.' . $ext;
                // 使用我们新建的uploads本地存储空间（目录）
                $bool = Storage::disk('admin')->put('/wangeditor/'.$filename, file_get_contents($realPath));
                if($bool){
                    echo asset('/uploads/wangeditor/'.$filename);
                }else{
                    echo 'error|上传失败';
                }
            }else{
                echo 'error|上传失败';
            }
        }else{
            echo 'error|图片类型不正确';
        }
    }

    /**
     * @Desc: 无权限界面
     * @Author: woann <304550409@qq.com>
     * @return \Illuminate\View\View
     */
    public function noPermission()
    {
        return view('base.403');
    }
}
