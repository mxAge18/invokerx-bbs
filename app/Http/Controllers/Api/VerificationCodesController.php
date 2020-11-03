<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Auth\AuthenticationException;
use Twilio\Exceptions\HttpException;
use Str;

class VerificationCodesController extends Controller
{
    //
    public function store(VerificationCodeRequest $request)
    {

        $captchaData = \Cache::get($request->captcha_key);
        if (!$captchaData) {
            abort(403, '图片验证码已失效');
        }

        if (!hash_equals($captchaData['code'], $request->captcha_code)) {
            // 验证错误就清除缓存
            \Cache::forget($request->captcha_key);
            throw new AuthenticationException('验证码错误');
        }

        $phone = $request->phone;

        // 生成4位随机数，左侧补0
        $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
        try {

            $client = app('sms');
            $client->messages->create(
            // Where to send a text message (your cell phone?)
                $phone,
                array(
                    'from' => config('twilio.from'),
                    'body' => 'huyanan shi ge dasha zi hahaha.verify code : ' . $code,
                )
            );

        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            abort(500, $message ? : '短信发送异常');
        }

        $key = 'verificationCode_'.Str::random(15);
        $expiredAt = now()->addMinutes(5);
        // 缓存验证码 5 分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        // 清除图片验证码缓存
        // https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx5ac392aa789c59ed&redirect_uri=http://www.bbs.com&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
        // https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx0b2351d55a52131b&redirect_uri=http://www.bbs.com&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect
        // https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx0b2351d55a52131b&secret=5e58d9eafd87d8371dc6c8c00f9a8cfe&code=091RrQFa1ISpUz0KWFJa1F0z6j2RrQFi&grant_type=authorization_code

        \Cache::forget($request->captcha_key);

        return response()->json([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
