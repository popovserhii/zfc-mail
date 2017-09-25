<?php
/**
 * The MIT License (MIT)
 * Copyright (c) 2017 Serhii Popov
 * This source file is subject to The MIT License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/MIT
 *
 * @category Popov
 * @package Popov_<package>
 * @author Serhii Popov <popow.sergiy@gmail.com>
 * @license https://opensource.org/licenses/MIT The MIT License (MIT)
 */
namespace Popov\ZfcMail\View\Helper\Factory;

use Interop\Container\ContainerInterface;
use Popov\ZfcMail\View\Helper\MailHelper;

class MailHelperFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $sm = $container->getServiceLocator();
        $mailService = $sm->get('MailService');

        return new MailHelper($mailService);
    }
}