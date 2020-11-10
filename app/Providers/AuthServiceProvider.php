<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
		 \App\Models\Bill::class => \App\Policies\BillPolicy::class,
		 \App\Models\Reply::class => \App\Policies\ReplyPolicy::class,
		 \App\Models\Topic::class => \App\Policies\TopicPolicy::class,
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
//        // Passport 的路由
//        Password::routes();
//        // access_token 过期时间
//        Passport::tokensExpireIn(now()->addDays(15));
//        // refreshTokens 过期时间
//        Passport::refreshTokensExpireIn(now()->addDays(30));


        $this->registerPolicies();

        Gate::guessPolicyNamesUsing(function($modelClass) {
            // 动态返回模型对应的策略名称，如：// 'App\Model\User' => 'App\Policies\UserPolicy',
            return 'App\Policies\\'.class_basename($modelClass).'Policy';
        });

        //
    }
}
