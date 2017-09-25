<?php
namespace Popov\ZfcMail\Controller;

use Popov\ZfcMail\Model\Mail;
use Popov\ZfcMail\Service\MailService;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Popov\ZfcMail\Form\Mail as MailForm;
use Zend\Console\Request as ConsoleRequest;
use Doctrine\Common\Collections\Criteria;

class MailController extends AbstractActionController {

    public $serviceName = 'MailService';
    public $controllerRedirect = 'mail';
    public $actionRedirect = 'index';


    public function indexAction()
    {
        $locator = $this->getServiceLocator();
        /** @var \Popov\ZfcMail\Service\MailService $service */
        $service = $locator->get($this->serviceName);

        $this->layout('layout/home');

        return [
            'fields'	=> $service->getFields(),
            'items'		=> $service->getItemsCollection('0', ''),
        ];
    }

    public function addAction()
    {
        $this->layout('layout/home');

        $viewModel = new ViewModel();
        $viewModel->setVariables($this->editAction());
        return $viewModel->setTemplate('magere/mail/edit.phtml');
    }

    public function editAction()
    {
        $request = $this->getRequest();
        $route = $this->getEvent()->getRouteMatch();
        $locator = $this->getServiceLocator();
        /** @var \Popov\ZfcMail\Service\MailService $service */
        $service = $locator->get($this->serviceName);
        $serviceOption = $locator->get('MailOptionService');
        $serviceOptionRole = $locator->get('MailOptionRoleService');
        $id = (int) $route->getParam('id');

        $item = $service->getOneItem($id, 'id', '');

        $form = new MailForm($id, $locator->get('Zend\Db\Adapter\Adapter'));
        $fields = ['type', 'emailTo', 'theme', 'body', 'statusId', 'accessDocument'];
        foreach ($fields as $field) {
            $method = 'get' . ucfirst($field);
            if ($field == 'emailTo') {
                $val = $serviceOption->getItemsByFieldToString($item->getMailOption(), $field);
            } else {
                $val = $item->$method();
            }
            $form->get($field)->setValue($route->getParam($field, $val));
        }
        if ($request->isPost()) {
            $post = $request->getPost()->toArray();
            $form->setData($post);
            if ($form->isValid()) {
                $postForm = $form->getData();
                $dataSave = [];
                $emailTo = '';
                foreach ($fields as $field) {
                    if (isset($postForm[$field])) {
                        if ($field == 'emailTo') {
                            $emailTo = $postForm[$field];
                        } else {
                            $dataSave[$field] = $postForm[$field];
                        }
                    }
                }

                if ($dataSave) {
                    // Table mail
                    $dataSave['id'] = $id;
                    $dataSave['hidden'] = '0';
                    $insertItemMail = $service->save($dataSave);

                    // Table mail_option
                    $dataSaveOption['period'] = 1;
                    $dataSaveOption['step'] = 1;
                    $serviceOption->saveData($insertItemMail, $dataSaveOption, $emailTo);

                    // Table mail_option_role
                    $dataSaveOptionRole = [];

                    // Save data
                    foreach ($post['mailOptionRoleId'] as $key => $value)
                    {
                        if (isset($post['mailOptionRole'][$key]))
                        {
                            $dataSaveOptionRole[$key] = [
                                'id'			=> $value,
                                'cityCreator'	=> (int) isset($post['cityCreator'][$key]),
                                'byBrand'		=> (int) isset($post['byBrand'][$key]),
                                'cityIn'		=> (int) isset($post['cityIn'][$key]),
                            ];

                            unset($post['mailOptionRoleId'][$key]);
                        }
                    }

                    $serviceOptionRole->saveData($insertItemMail, $dataSaveOptionRole);

                    // Delete data
                    $dataSaveOptionRole = array_diff($post['mailOptionRoleId'], [null]);
                    $serviceOptionRole->deleteData($dataSaveOptionRole);
                }

                $this->redirect()->toRoute('default', [
                    'controller'	=> $this->controllerRedirect,
                    'action'		=> $this->actionRedirect,
                ]);
            }
        }

        // Roles
        $serviceRoles = $locator->get('RolesService');

        // Table mail_option_role to array
        $itemsOptionRole = $service->toArrayKeyField('roleId', $item->getMailOptionRole());

        $this->layout('layout/home');

        return [
            'id'				=> $item->getId(),
            'fields'			=> $service->getFields(),
            'form'				=> $form,
            'roles'				=> $serviceRoles->getItemsCollection(),
            'itemsOptionRole'	=> $itemsOptionRole,
            'notation'			=> $item->getInfo(),
        ];
    }

    /**
     * Console mail send action
     */
    public function sendAction()
    {
        /**
         * @var ConsoleRequest $request
         * @var MailService $mailService
         * @var Mail $mail
         */
        $request = $this->getRequest();
        $mailService = $this->getServiceLocator()->get('MailService');
        $mailRepository = $mailService->getRepository();

        $criteria = new Criteria();
        // Add a not equals parameter to your criteria
        $criteria->where($criteria->expr()->gte('id', 0));

        // Find all from the repository matching your criteria
        /** @var \Doctrine\ORM\LazyCriteriaCollection $result */
        $mail = $mailRepository->matching($criteria)->first();

        $config = $mailService->getConfig();

        // Check both alternatives
        if ($from = $request->getParam('from', false) ? : $request->getParam('f', false)) {
            $config['mail']['from'] = $from;
        }
        if ($to = $request->getParam('to', false) ? : $request->getParam('t', false)) {
            $recipients = [$to];
        }
        if ($message = $request->getParam('message', false) ? : $request->getParam('m', false)) {
            $mail->setBody($message);
        }

        if ($to) {
            $mailService->setConfig($config);
            $mailService->send($mail, $recipients);
        }
    }

}