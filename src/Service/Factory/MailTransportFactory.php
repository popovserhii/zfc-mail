<?php
/**
 * @category Agere
 * @package Agere_Mail
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 10.08.2016 13:32
 */
namespace Popov\ZfcMail\Service\Factory;

use Interop\Container\ContainerInterface;
use Zend\Mail\Transport\Factory as TransportFactory;
use Zend\Mail\Transport\Exception;
use Zend\Mail\Transport\TransportInterface;

class MailTransportFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('Config')['mail'];

        if (class_exists('Zend\\Mail\\Transport\\' . ucfirst($config['type']))) {
            $transport = TransportFactory::create(['type' => $config['type'], 'options' => $config['options']]);
        } else {
            $transport = $container->get($config['type'] . 'Transport');
            if (!$transport instanceof TransportInterface) {
                throw new Exception\DomainException(sprintf(
                    '%s expects the "type" attribute to resolve to a valid'
                    . ' Zend\Mail\Transport\TransportInterface instance; received "%s"',
                    __METHOD__,
                    $config['type']
                ));
            }
            $transport->setOptions($config['options']);
        }

        return $transport;
    }
}