<?php
namespace Popov\ZfcMail;

use Zend\ModuleManager\ModuleManager,
	Zend\EventManager\Event,
	Zend\Mvc\MvcEvent,
	Zend\Mail as MailZF,
	Zend\Mail\Message as MailMessage,
	Zend\Mime\Message as MimeMessage,
	Zend\Mime\Part as MimePart,
	Zend\Mime\Mime,
	Popov\ZfcFileUpload\Transfer\Adapter\Http,
	Popov\ZfcMail\Service\MailOptionService;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;
use Zend\Console\Adapter\AdapterInterface as Console;
use Zend\ModuleManager\Feature\ConsoleBannerProviderInterface;


class Module implements ConfigProviderInterface, ConsoleUsageProviderInterface, ConsoleBannerProviderInterface
{
	/**
	 * @param MvcEvent $e
	 */
	public function onBootstrap(MvcEvent $e)
	{
		$eventManager = $e->getTarget()->getEventManager();
		$sm = $e->getApplication()->getServiceManager();
		#$eventManager->attach((new Listener\StatusListener())->setServiceLocator($sm));
	}


    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getConsoleBanner(Console $console)
    {
        return 'Mail Module';
    }

    public function getConsoleUsage(Console $console)
    {
        //description command
        return [
            'Usage:',
            'mail [<command>] [options]' => '',

            'Command:',
            #'Flags:',
            #['--env', 							'Prepare environment for all projects'],

            ['create', 							'Send message'],
            ['--from|-f', 						'Mail From'],
            ['--to|-t', 						'Mail To'],
            ['--message|-m', 					'Mail Message'],
        ];
    }
}
