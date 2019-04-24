<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;




define("TOKEN", "blogWechat");
/*define("TOKEN", "blogWechat");    //定义TOKEN, “peng”是自己随便定义，这一句很重要！！！
$wechatObj = new WechatController();

if (!isset($_GET['echostr'])) {
    $wechatObj->LogicAction();    //后续的有实质功能的function(此篇不用管）
}else{
    $wechatObj->valid();    //调用valid函数进行基本配置
}*/


class WechatController extends Controller
{
    private $access_token;    //定义一个access_token，用于后续调用微信接口（此篇用不到）

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    //微信服务器地址
    public function wechatDefault(Request $request)
    {


        $resu = doGet($this->wechatTokenManages());
        Log::debug('请求:'.$resu);
        dd($resu);
        exit();


        Log::debug('请求:'.$request);

        $echoStr = $this->valid($request->signature,$request->timestamp,$request->nonce);
        echo $echoStr;
        exit;
    }

    public function valid($signature,$timestamp,$nonce){    //用于基本配置的函数
        $echoStr = $_GET["echostr"];
        if($this->checkSignature($signature,$timestamp,$nonce)){
            return $echoStr;
        }
    }

    private function checkSignature($signature,$timestamp,$nonce)
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }


    public function wechatTokenManages()
    {
        $path = env('WECHAT_URL').'cgi-bin/token?grant_type=client_credential&appid='.env('WECHAT_APPID').'&secret='.env('WECHAT_SECRET');
        return $path;
    }


}
