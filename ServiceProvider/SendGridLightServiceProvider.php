<?php
namespace Plugin\SendGridLight\ServiceProvider;

use Eccube\Application;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;
class SendGridLightServiceProvider implements ServiceProviderInterface
{
    public function register(BaseApplication $app)
    {
        $app->match(
            '/'.$app['config']['admin_route'].'/plugin/SendGridLight/config',
            'Plugin\SendGridLight\Controller\ConfigController::index')
            ->bind('plugin_SendGridLight_config');
    }
    public function boot(BaseApplication $app)
    {
    }
}
