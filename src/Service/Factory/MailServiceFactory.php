<?php
/**
 * Mail Service Factory
 *
 * @category Agere
 * @package Agere_Mail
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 06.09.2016 0:23
 */
namespace Popov\ZfcMail\Service\Factory;

use Popov\ZfcMail\Service\MailService;

class MailServiceFactory
{
    public function __invoke($sm)
    {
        //$em = $sm->get('Doctrine\ORM\EntityManager');
        $service = new MailService();
        $service->setServiceManager($sm);
        //$service->setEntityManager($em);

        return $service;
    }
}