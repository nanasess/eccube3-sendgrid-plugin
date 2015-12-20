<?php

namespace Plugin\SendGridLight\Controller;

use Eccube\Application;
use Eccube\Controller\AbstractController;
use Plugin\SendGridLight\Entity\SendGridLight;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigController extends AbstractController
{
    public function index(Application $app, Request $request)
    {
        $SendGridLight = $app['eccube.plugin.repository.sendgridlight']->find(1);
        if (!is_object($SendGridLight)) {
            $SendGridLight = new SendGridLight();
        }
        $form = $app['form.factory']->createBuilder('sendgridlight_config', $SendGridLight)->getForm();

        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);

            if ($form->isValid()) {

                $SendGridLight = $form->getData();
                $SendGridLight->setId(1);
                $app['orm.em']->persist($SendGridLight);
                $app['orm.em']->flush();
                return $app->redirect($app->url('plugin_SendGridLight_config_complete'));
            }
        }
        return $app->render('SendGridLight/Resource/template/admin/config.twig', array(
            'form' => $form->createView(),
            'SendGrid' => $SendGridLight,
        ));
    }

    public function complete(Application $app)
    {
        return $app->render('SendGridLight/Resource/template/admin/config_complete.twig');
    }
}
