<?php
namespace Popov\ZfcMail\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Popov\ZfcMail\Service\MailService;

class MailHelper extends AbstractHelper
{
    /**
     * @var MailService
     */
    protected $mailService;

    public function __construct($mailService)
    {
        $this->mailService = $mailService;
    }
}