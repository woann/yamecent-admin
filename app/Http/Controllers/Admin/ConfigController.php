<?php
namespace App\Http\Controllers\Admin;

use App\AdminConfig;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        $wd   = $request->input('wd');
        $list = AdminConfig::searchCondition($wd)->paginate(10);
        return view('admin.config', ['list' => $list, 'wd' => $wd]);
    }

    /**
     * @Desc: 添加配置
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function configAddView(Request $request)
    {
        return view('admin.config_add');
    }

    public function configAdd(Request $request)
    {
        $data   = $request->post();
        $config = new AdminConfig();
        $config->fill($data)->save();
        return $this->json(200, '添加成功');
    }

    /**
     * @Desc: 修改配置信息
     * @Author: woann <304550409@qq.com>
     * @param Request $request
     * @param $id
     * @return \Illuminate\View\View
     */
    public function configUpdateView(Request $request, $id)
    {
        return view('admin.config_update', ['config' => AdminConfig::findOrFail($id)]);
    }

    public function configUpdate(Request $request, $id)
    {
        $config = AdminConfig::findOrFail($id);
        $data   = $request->post();
        $config->fill($data);
        $config->save();
        return $this->json(200, '修改成功');
    }

    /**
     * @Desc: 删除配置
     * @Author: woann <304550409@qq.com>
     * @param $id
     * @return mixed
     */
    public function configDel($id)
    {
        AdminConfig::findOrFail($id)->delete();
        return $this->json(200, '删除成功');
    }
}
