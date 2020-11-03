<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use Twilio\Exceptions\HttpException;
use Str;

class VerificationCodesController extends Controller
{
    //
    public function store(VerificationCodeRequest $request)
    {


        $phone = "+44" . $request->phone;
        // 生成4位随机数，左侧补0
        $code = str_pad(random_int(1, 9999), 4, 0, STR_PAD_LEFT);
        try {

            $client = app('sms');
            $client->messages->create(
            // Where to send a text message (your cell phone?)
                $phone,
                array(
                    'from' => config('twilio.from'),
                    'body' => 'verify code : ' . $code,
                )
            );

        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            abort(500, $message ? '2': '短信发送异常');
        }

        $key = 'verificationCode_'.Str::random(15);
        $expiredAt = now()->addMinutes(5);
        // 缓存验证码 5 分钟过期。
        \Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return response()->json([
            'key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
