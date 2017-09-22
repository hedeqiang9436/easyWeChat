<?php

namespace App\Http\Controllers;

use EasyWeChat\Foundation\Application;
use EasyWeChat\Message\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Monolog\Handler\IFTTTHandler;

class WeChatController extends Controller
{
    public function serve()
    {
        Log::info('request arrived.'); # 注意：Log 为 Laravel 组件，所以它记的日志去 Laravel 日志看，而不是 EasyWeChat 日志
        $wechat = app('wechat');
        $wechat->server->setMessageHandler(function($message) use ($wechat) {
            switch ($message->MsgType) {
                case 'event':
                    if ($message->Event =="subscribe"){
                        return '欢迎关注，编程趣事';
                    }
                    elseif ($message->Event =="unsubscribe"){
                        return '取关';
                    }
                    break;
                case 'text':
                    if ($message->Content=="李树珍"){
                        return '你好啊！珍珍，有个人非常爱你，那个人就是贺德强';
                    }
                    elseif ($message->Content=="贺德强"){
                        return '你好贺德强，有个人非常爱你，那个人就是李树珍';
                    }
                    else{
                        return '我会努力给你们带来更加精彩的内容';
                    }
                    break;
                case 'images':
                    $image=new Image(['media_id'=>'ID']);
                    $wechat->staff->message($image)->to($message->FromUserName)->send();
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }
        });

        Log::info('return response.');

        return $wechat->server->serve();
    }
}
