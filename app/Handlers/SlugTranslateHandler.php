<?php

namespace App\Handlers;

use GuzzleHttp\Client;
use Overtrue\Pinyin\Pinyin;

class SlugTranslateHandler
{

    public function translate($text)
    {

        $http = new Client();

        // api 配置
        $api = "http://api.fanyi.baidu.com/api/trans/vip/translate?";

        $appID = config('services.baidu_translate.appid');

        $key = config('services.baidu_translate.key');

        $salt = time();

        // 如果没有配置百度翻译，自动使用兼容的拼音方案
        if (empty($appID) || empty($key)) {
            return $this->pinyin($text);
        }

        // 根据文档，生成 sign
        // http://api.fanyi.baidu.com/api/trans/product/apidoc
        // appid+q+salt+密钥 的MD5值
        $sign = md5($appID. $text . $salt . $key);

        // 构建请求参数
        $query = http_build_query([
            'q' => $text,
                'from' => 'zh',
                'to' => 'en',
                'appid' => $appID,
                'salt' => $salt,
                'sign' => $sign,
        ]);

        $response = $http->get($api . $query);

        $result = json_decode($response->getBody(), true);

        if ($result['trans_result'][0]['dst']) {
            return \Str::slug($result['trans_result'][0]['dst']);
        } else {
            return $this->pinyin($text);
        }
    }


    public function pinyin($text)
    {
        return \Str::slug(app(Pinyin::class)->permalink($text));
    }
}
