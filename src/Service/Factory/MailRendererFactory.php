<?php
/**
 * @category Agere
 * @package Agere_Mail
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 10.08.2016 13:32
 */
namespace Popov\ZfcMail\Service\Factory;

use Interop\Container\ContainerInterface;
use Popov\ZfcMail\Service\MailRenderer;

class MailRendererFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $cpm = $container->get('ControllerPluginManager');
        $userPlugin = $cpm->get('user');
        //$entityPlugin = $cpm->get('entity');
        $simpler = $container->get('ControllerPluginManager')->get('simpler');

        $service = new MailRenderer($simpler);
        $service->setVariables([
            'user' => $userPlugin->current(),
        ]);

        return $service;
    }
}