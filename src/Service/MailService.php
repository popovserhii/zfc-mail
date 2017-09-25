<?php
namespace Popov\ZfcMail\Service;

use Zend\Mail\Address;
use Zend\Mail\Message;
use Agere\Access\Service\AccessServiceAwareTrait;
use Agere\Access\Service\AccessServiceAwareInterface;
use Popov\ZfcCore\Service\ServiceManagerAwareTrait;
use Popov\ZfcCore\Service\DomainServiceAbstract;
use Popov\ZfcCore\Service\ConfigAwareInterface;
use Popov\ZfcCore\Service\ConfigAwareTrait;
use Popov\Status\Model\Status;
use Popov\ZfcMail\Model\Mail;
use Popov\ZfcMail\Service\MailRenderer;
use Popov\ZfcUser\Model\User;
use Popov\ZfcUser\Service\UserService as UserService;

class MailService extends DomainServiceAbstract implements ConfigAwareInterface
{
    use ServiceManagerAwareTrait;
    use ConfigAwareTrait;

    protected $entity = Mail::class;

    /**
     * @var array
     * @deprecated
     */
    protected $_fields = [
        'id'			=> '№',
        'type'			=> 'Тип',
        'status'		=> 'Статус',
        'emailTo'		=> 'Email кому',
        'theme'			=> 'Тема',
        'body'			=> 'Письмо',
        'accessDocument'=> 'По доступу к документу',
    ];

    /**
     * @var []
     */
    protected $config;

    /**
     * @return array
     * @deprecated
     */
    public function getFields()
    {
        return $this->_fields;
    }

    public function getTypeOptions()
    {
        return ['mail', 'reminder'];
    }

    public function setConfig($config)
    {
        $this->config = $config;

        return $this;
    }

    public function getConfig()
    {
        if (!$this->config) {
            $sm = $this->getServiceManager();
            $this->config = $sm->get('Config');
        }
        return $this->config;
    }

    /**
     * @param Status $status
     * @param object $item
     * @param array $variables
     * @return mixed
     */
    public function sendByStatus($status, $item, array $variables = [])
    {
        $sm = $this->getServiceManager();
        $apm = $sm->get('AccessPluginManager');

        /** @var Mail $mail */
        $mail = $this->getRepository()
            ->findOneBy(['statusId' => $status->getId(), 'type' => Mail::TYPE_MAIL]);

        if (!$mail) {
            // hasn't mail
            return false;
        }

        $recipients = [];
        //$recipients = new \Zend\Mail\AddressList();
        foreach ($mail->getMailRoles() as $mailRole) {
            foreach ($mailRole->getRole()->getUsers() as $user) {
                // if have additional settings check them
                /** @var \Doctrine\ORM\PersistentCollection $settings */
                if (($settings = $mailRole->getPermissionSettings()) && !$settings->isEmpty()) {
                    foreach ($settings as $setting) {
                        $filter = $apm->get($setting->getMnemo());
                        $filter->setUser($user);
                        if ($filter->check($item)) {
                            //$recipients[] = $user;
                            $recipients[] = new Address($user->getEmail()/*, $user->getName()*/);
                        }
                    }
                } else {
                    $recipients[] = new Address($user->getEmail());
                }
            }
        }

        return $this->send($mail, $recipients, array_merge($variables, ['item' => $item, 'status' => $status]));
    }

    /**
     * @param Mail $mail
     * @param Address[] $recipients
     * @param $variables
     * @return mixed
     */
    public function send(Mail $mail, array $recipients, array $variables = [])
    {
        $sm = $this->getServiceManager();

        $subject = $mail->getTheme();
        $body = $mail->getBody();

        /** @var MailRenderer $renderer */
        $renderer = $sm->get(MailRenderer::class)->addVariables($variables);

        $renderedSubject = $renderer->setContent($subject)->render();
        $renderedBody = $renderer->setContent($body)->render();

        // Create mail
        $message = new Message();
        $headers = $message->getHeaders();
        $headers->removeHeader('Content-Type');
        $headers->addHeaderLine('Content-Type', 'text/html; charset=UTF-8');

        // Config
        $config = $this->getConfig()['mail'];
        $message->setFrom($config['from'])
            //->setSubject('=?utf-8?B?' . base64_encode($renderedSubject) . '?=')
            ->setSubject($renderedSubject)
            ->setBody($renderedBody)
            ->setEncoding('UTF-8')
        ;

        //$transport = TransportFactory::create(['type' => $config['type'], 'options' => $config['smtpOptions']]);
        $transport = $sm->get('MailTransport');

        if (isset($recipients[0])) {
            try {
                $message->addTo($recipients);
                return $transport->send($message);
            } catch (\Exception $e) {
                \Zend\Debug\Debug::dump(iconv("CP1251//IGNORE", "UTF-8", $e->getMessage()));
                \Zend\Debug\Debug::dump($e->getTraceAsString());
                die(__METHOD__);
            }
        }

        //\Zend\Debug\Debug::dump([count($recipients)/*, count($users)*/]); die(__METHOD__);
    }

}