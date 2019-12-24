<?php

namespace Chuwei\Cardpay;

use Illuminate\Support\ServiceProvider;

class LakalaServiceProvider extends ServiceProvider
{
    /**
     * 服务提供者加是否延迟加载.
     *
     * @var bool
     */
    protected $defer = true; // 延迟加载服务

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/lakala.php' => config_path('lakala.php'), // 发布配置文件到 laravel 的config 下
        ], 'config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_lakala_tables.php.stub' => $this->app->databasePath()."/migrations/".date('Y_m_d_His')."_create_lakala_tables.php",
        ], 'migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // 单例绑定服务
        $this->app->singleton('lakala', function ($app) {
            return new Lakala();
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        // 因为延迟加载 所以要定义 provides 函数 具体参考laravel 文档
        return ['lakala'];
    }
}
