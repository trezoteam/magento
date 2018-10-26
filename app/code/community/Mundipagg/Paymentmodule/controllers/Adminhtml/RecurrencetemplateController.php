<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use Mundipagg\Recurrence\Controller\Templates;
use Mundipagg\Recurrence\Factories\TemplateRootFactory;
use Mundipagg\Recurrence\Repositories\Decorators\MagentoPlatformDatabaseDecorator;
use Mundipagg\Recurrence\Repositories\TemplateRepository;

class Mundipagg_Paymentmodule_Adminhtml_RecurrencetemplateController extends Mage_Adminhtml_Controller_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        Mage::helper('paymentmodule/exception')->initExceptionHandler();
    }

    public function indexAction()
    {
        $this->loadLayout();
        $this->_setActiveMenu('mundipagg/recurrencetemplate');
        $this->_addContent($this->getLayout()->createBlock('paymentmodule/adminhtml_recurrence_template'));
        $this->renderLayout();
    }

    public function newAction()
    {
        $this->_title($this->__('Mundipagg'))
            ->_title($this->__('Recurrence templates'))
            ->_title($this->__('Edit'));

        /**
         * @todo remove this actions from here
         */
        //$this->createAction();
        //$this->updateAction();
        //$this->saveTemplateAction();
        $this->deleteAction();

        $this->loadLayout();
        $this->renderLayout();
    }

    public function createAction()
    {
        /**
         * @todo remove this mock
         */
        //$postData = $this->getRequest()->getParams();
        $postData = [
            'allow_installment' => "1",
             'description' => "descricao",
             'expiry_date' => "9",
             'expiry_type' => "X",
             'installments' => "1,4,7",
             'intervals' => [
                 [
                     'cycles'=> '1',
                     'frequency' => '4',
                     'type' => 'M'
                 ]
             ],
             'name' => "template para plano 2",
             'payment_method' => [
                 0 => 'credit_card',
                 1 => 'boleto'
             ],
             'trial' => "12"
        ];

        $resource = Mage::getSingleton('core/resource');

        $templates = new Templates($resource);
        $templates->saveTemplate($postData);
    }

    public function updateAction()
    {
        /**
         * @todo remove this mock
         */
        //$getData = $this->getRequest()->getParams('templateId);
        $getData['templateId'] = 1;

        if (!isset($getData['templateId'])) {
            return $this->createAction();
        }

        $resource = Mage::getSingleton('core/resource');

        $templateRepository =
            new TemplateRepository(
                new MagentoPlatformDatabaseDecorator($resource)
            );

        $templateRoot = $templateRepository->find($getData['templateId']);

        $this->setBaseCreationFormData();
    }

    protected function setBaseCreationFormData()
    {
        /**
         * @todo Implement this method
         */
    }

    protected function deleteAction()
    {
        /**
         * @todo remove this mock
         */
        //$getData = $this->getRequest()->getParams('templateId);
        $getData['templateId'] = 2;

        if (isset($getData['templateId'])) {
            $resource = Mage::getSingleton('core/resource');

            $templateRepository =
                new TemplateRepository(
                    new MagentoPlatformDatabaseDecorator($resource)
                );
            $templateRoot = $templateRepository->find($getData['templateId']);
            if ($templateRoot !== null) {
                $templateRepository->delete($templateRoot);
            }
        }

        /**
         * @todo redirect
         */
    }

    protected function saveTemplateAction()
    {
        /**
         * @todo remove this mock
         */
        //$postData = $this->getRequest()->getParams();
        $postData = [
            'template-id' => 1,
            'allow_installment' => "1",
            'description' => "descricao",
            'expiry_date' => "9",
            'expiry_type' => "X",
            'installments' => "1,4,7",
            'intervals' => [
                [
                    'cycles'=> '1',
                    'frequency' => '4',
                    'type' => 'M'
                ]
            ],
            'name' => "template para plano atualizado",
            'payment_method' => [
                0 => 'credit_card',
                1 => 'boleto'
            ],
            'trial' => "12"
        ];

        $templateRootFactory = new TemplateRootFactory();
        if (!$this->validatePostData($postData)) {
            return $this->handleFormError();
        }

        try {
            $templateRoot = $templateRootFactory->createFromPostData($postData);
            $resource = Mage::getSingleton('core/resource');

            $templateRepository =
                new TemplateRepository(
                    new MagentoPlatformDatabaseDecorator($resource)
                );

            if (isset($postData['template-id'])) {
                $templateRoot->getTemplate()->setId($postData['template-id']);
                $updateChildren = $this->getRequest()->getParam('updateChildren');

                if (isset($updateChildren)) {
                    $this->updateTemplate($updateChildren, $templateRoot);
                }
            }

            $templateRepository->save($templateRoot);
        }catch(Exception $e) {
            $e->getMessage();
            throw $e;
        }

        /**
         * @todo Redirect
         */
    }

    protected function validatePostData($postData)
    {
        $errors = [];
        try {
            //creating a templateRoot from json_data just to validate the input.
            (new TemplateRootFactory)->createFromPostData($postData);
        } catch (\Exception $exception) {
            $errors['recurrency_plan_input_error'] = $exception->getMessage();
        }

        if (count($errors)) {
            /**
             * @todo Do something with errors
             */
            return false;
        };

        return true;
    }
}
