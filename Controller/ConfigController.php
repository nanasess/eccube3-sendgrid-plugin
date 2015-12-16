<?php

namespace Plugin\SendGridLight\Controller;

use Eccube\Application;
use Eccube\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfigController extends AbstractController
{
    public function index(Application $app, Request $request)
    {
        return $app->render('SendGridLight/Resource/template/admin/config.twig', array(
            // 'form' => $form->createView(),
            // 'SendGrid' => $SendGrid,
        ));
    }
}
