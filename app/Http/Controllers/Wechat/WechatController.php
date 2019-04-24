<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\HttpFoundation\Session\Session;


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

        $session = Redis::get();

        Log::debug('请求:'.$resu);
        dd($resu);
        exit();


        Log::debug('请求:'.$request);
        //初始化“服务器配置”调用，之后就不需要了
        /*$echoStr = $this->valid($request->signature,$request->timestamp,$request->nonce);
        echo $echoStr;
        exit;*/
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
        $token = env('WECHAT_TOKEN');
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
        Log::debug('路径:'.$path);
        return $path;
    }


}
