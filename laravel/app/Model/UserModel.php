<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class UserModel extends Model
{
    public $table='p_wx_users';
    public $timestamps=false;
    public $primaryKey="id";
    public static$redis_weixin_access_token = 'str:weixin_access_token';
    public  static function getAccessToken(){
        $token=Redis::get(self::$redis_weixin_access_token);
        if(!$token){
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . env('WEIXIN_APPID') . '&secret=' . env('WEIXIN_APPSECRET');
            $data=json_decode(file_get_contents($url),true);
//            print_r($data);die;
            $token=$data['access_token'];
            Redis::set(self::$redis_weixin_access_token,$token);
            Redis::setTimeout(self::$redis_weixin_access_token,3600);
        }
        return $token;
    }
}
