<?php
/**
 * Status subscriber for send mail
 *
 * @category Agere
 * @package Agere_Mail
 * @author Popov Sergiy <popov@agere.com.ua>
 * @datetime: 17.04.2016 22:50
 */
namespace Popov\ZfcMail\Listener;

use Zend\ServiceManager\ServiceLocatorAwareTrait;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Popov\Status\Controller\StatusController;
use Popov\ZfcMail\Service\MailService;

class StatusListener implements ListenerAggregateInterface
{
	use ListenerAggregateTrait;
	use ServiceLocatorAwareTrait;

	public function attach(EventManagerInterface $events)
    {
		$sm = $this->getServiceLocator();
		$sem = $events->getSharedManager(); // shared events manager

		// or little change with use anonymous function
		$this->listeners[] = $sem->attach(StatusController::class, 'change.post', function($e) use($sm) {
			$item = $e->getTarget();
			$oldStatus = $e->getParam('oldStatus');
			$newStatus = $e->getParam('newStatus');

            /** @var MailService $mailService */
            $mailService = $sm->get('MailService');
            $mailService->sendByStatus($newStatus, $item, ['oldStatus' => $oldStatus]);
		}, 100);
	}

}