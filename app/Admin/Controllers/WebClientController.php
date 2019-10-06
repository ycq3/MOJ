<?php
/**
 * Created by PhpStorm.
 * User: yan
 * Date: 2018/8/17 0017
 * Time: 22:04
 */

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Model\WebClient;
use App\User;
use Encore\Admin\Widgets\Box;
use Encore\Admin\Widgets\Table;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Controllers\ModelForm;


class WebClientController extends Controller
{
    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->table());
        });
    }

    public function table()
    {

        $headers = ['客户端UID', '客户端Token', '客户端登录用户', '连接时间','最后一次连接时间','操作'];
        $user=Cache::get('workman_user');
        $rows=$user->map(function(WebClient $item){
            $user_name=$item->getUserId()?User::find($item->getUserId())->name:'';
            return [
                'cid'=>$item->getClientId(),
                $item->getToken(),
                $item->getUserId().'|'.$user_name,
                $item->getConnectTime(),
                $item->getLastConnectTime()
            ];
        })->all();

        $data=[
            'headers'=>$headers,
            'rows'=>$rows
        ];
        return view('admin.ClientManage',$data);
    }

    public function socket(Request $request)
    {
        $client=new Client();
        try{
            $response = $client->request('POST', 'http://localhost:9131', [
                'form_params' =>$request->all()
            ]);
            if ($response->getStatusCode() != 200) {
                return '或由于网络问题客户端未响应。';
            }
        }catch (ConnectException $connectException){
            return '服务器故障，请联系管理人员！并提供以下代码给管理员('.$connectException->getMessage().')';
        }

        return $response;
    }
}