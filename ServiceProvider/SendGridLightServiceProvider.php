<?php

namespace Plugin\SendGridLight\ServiceProvider;

require_once __DIR__.'/../vendor/autoload.php';

use Eccube\Application;
use Silex\Application as BaseApplication;
use Silex\ServiceProviderInterface;
use Plugin\SendGridLight\Form\Type;
use SendGrid\Email;
use SendGrid;

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
        $app['mailer'] = $app->share(function ($app) {
            return new SendGridWrapper($app);
        });
    }
    public function boot(BaseApplication $app)
    {
    }
}
class SendGridWrapper
{
    protected $app;
    public function __construct($app)
    {
        $this->app = $app;
    }
    public function send($message)
    {
        $SendGridLight = $this->app['eccube.plugin.repository.sendgridlight']->find(1);
        if (!$SendGridLight) {
            throw new \Exception('SendGrid not found.');
        }
        $sendgrid = new SendGrid(
            $SendGridLight->getApiUser(),
            $SendGridLight->getApiKey()
        );
        $email = new Email();
        //$name = array('Elmer');
        $to = $message->getTo();
        $to_emails = array_keys($to);
        $from = $message->getFrom();
        $from_emails = array_keys($from);

        $email
            ->addTo($to_emails[0]) // 配列数分設定する
            ->setFrom($from_emails[0])
            ->setSubject($message->getSubject())
            ->setText($message->getBody())
            ->setBcc($message->getBcc())
            ->setReplyTo($message->getReplyTo())
            // ->setReturnPath($message->getRealPath())
            // ->setHtml('<strong>I\'m HTML!</strong>')
            // ->addFilter('templates', 'enabled', 1)
            // ->addFilter('templates', 'template_id', $templateId)
            // ->addSubstitution(":name", $name)
            ;
        $sendgrid->send($email);
    }
}
