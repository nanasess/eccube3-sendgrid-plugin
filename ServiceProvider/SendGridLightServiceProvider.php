<?php
namespace Plugin\SendGridLight\ServiceProvider;

use Eccube\Application;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;
use Plugin\SendGridLight\Form\Type;

class SendGridLightServiceProvider implements ServiceProviderInterface
{
    public function register(BaseApplication $app)
    {
        $app->match(
            '/'.$app['config']['admin_route'].'/plugin/SendGridLight/config',
            'Plugin\SendGridLight\Controller\ConfigController::index')
            ->bind('plugin_SendGridLight_config');

        // Form
        $app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
            $types[] = new \Plugin\SendGridLight\Form\Type\SendGridLightConfigType($app);
            return $types;
        }));

    }
    public function boot(BaseApplication $app)
    {
    }
}
