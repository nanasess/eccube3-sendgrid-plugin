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

        $app->match(
            '/'.$app['config']['admin_route'].'/plugin/SendGridLight/config_complete',
            'Plugin\SendGridLight\Controller\ConfigController::complete')
            ->bind('plugin_SendGridLight_config_complete');

        // Form
        $app['form.types'] = $app->share($app->extend('form.types', function ($types) use ($app) {
            $types[] = new \Plugin\SendGridLight\Form\Type\SendGridLightConfigType($app);
            return $types;
        }));

        // Repository
        $app['eccube.plugin.repository.sendgridlight'] = $app->share(function () use ($app) {
            return $app['orm.em']->getRepository('Plugin\SendGridLight\Entity\SendGridLight');
        });

    }
    public function boot(BaseApplication $app)
    {
    }
}
