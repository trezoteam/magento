<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use Mundipagg\Recurrence\Aggregates\Template\TemplateRoot;
use Mundipagg\Recurrence\Controller\Templates;
use Mundipagg\Recurrence\Factories\TemplateRootFactory;
use Mundipagg\Recurrence\Repositories\Decorators\MagentoPlatformDatabaseDecorator;
use Mundipagg\Recurrence\Repositories\TemplateRepository;

class Mundipagg_Paymentmodule_Adminhtml_RecurrencetemplateController extends Mage_Adminhtml_Controller_Action
{
    protected $errors;
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

    public function editAction()
    {
        $resource = Mage::getSingleton('core/resource');

        $templateRepository =
            new TemplateRepository(
                new MagentoPlatformDatabaseDecorator($resource)
            );

        $id  = $this->getRequest()->getParam('id');
        $templateRoot = $templateRepository->find($id);

        if ($templateRoot && $templateRoot->getId()) {

            $tab = $templateRoot->getTemplate()->isSingle() ? 'single' : 'plan';
            $this->getRequest()->setParam('tab', $tab);

            $templateData = $this->formatTemplateData($templateRoot);
            Mage::register('template_data', $templateData);

            $this->loadLayout();
            $this->_setActiveMenu('mundipagg/recurrencetemplate');
            $this->_addContent($this->getLayout()->createBlock('paymentmodule/adminhtml_recurrence_edit'))
                ->_addLeft($this->getLayout()->createBlock('paymentmodule/adminhtml_recurrence_edit_tabs'));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError('Template does not exist');
            $this->_redirect('adminhtml/recurrencetemplate', ['_secure' => true]);
        }

    }

    /**
     * @todo Move to another class
     */
    public function formatTemplateData(TemplateRoot $template)
    {
        return [
            'name' => $template->getTemplate()->getName(),
            'description' => $template->getTemplate()->getDescription(),
            'installments' => $this->formatInstallments($template->getTemplate()->getInstallments()),
            'expiry_type'   => $template->getDueAt()->getType(),
            'expiry_date'   => $template->getDueAt()->getValue(),
            'cycle' => $template->getRepetitions()[0]->getCycles(),
            'frequency' => $template->getRepetitions()[0]->getFrequency(),
            'type' => $template->getRepetitions()[0]->getIntervalType(),
            'trial' => $template->getTemplate()->getTrial(),
        ];
    }

    /**
     * @todo Move to another class
     */
    public function formatInstallments($installments)
    {
        $result = array_map(function ($installment) {
            return $installment->getValue();
        }, $installments);

        return implode(',', $result);
    }

    public function newAction()
    {
        $this->_title($this->__('Mundipagg'))
            ->_title($this->__('Recurrence templates'))
            ->_title($this->__('New Template'));

        $this->loadLayout();
        $this->_addContent($this->getLayout()
                ->createBlock('paymentmodule/adminhtml_recurrence_edit'));
        $this->_addLeft($this->getLayout()
                ->createBlock('paymentmodule/adminhtml_recurrence_edit_tabs'));
        $this->renderLayout();
    }

    public function deleteAction()
    {
        $getData = $this->getRequest()->getParams();

        if (isset($getData['id'])) {
            $resource = Mage::getSingleton('core/resource');

            $templateRepository =
                new TemplateRepository(
                    new MagentoPlatformDatabaseDecorator($resource)
                );

            $templateRoot = $templateRepository->find($getData['id']);
            if ($templateRoot !== null) {
                $templateRepository->delete($templateRoot);
            }
        }

        $this->_redirect('adminhtml/recurrencetemplate', ['_secure' => true]);
    }

    public function savePlanAction()
    {
        $this->_forward('save');
    }

    public function saveSingleAction()
    {
        $this->_forward('save', null, null, ['single' => true]);
    }

    public function saveAction()
    {
        $postData = $this->getRequest()->getParams();

        $allowInstallment = [
            'allow_installment' => empty($postData['installments']) ? false : true
        ];

        $postData = array_merge($postData, $allowInstallment);

        $templateRootFactory = new TemplateRootFactory();
        if (!$this->validatePostData($postData)) {
            Mage::getSingleton('adminhtml/session')
                ->addError($this->errors['recurrency_plan_input_error']);
            $this->_redirect('adminhtml/recurrencetemplate', ['_secure' => true]);
        }

        try {
            $templateRoot = $templateRootFactory->createFromPostData($postData);
            $resource = Mage::getSingleton('core/resource');

            $templateRepository =
                new TemplateRepository(
                    new MagentoPlatformDatabaseDecorator($resource)
                );

            if (isset($postData['id'])) {
                $templateRoot->getTemplate()->setId($postData['id']);
                $updateChildren = $this->getRequest()->getParam('updateChildren');

                if (isset($updateChildren)) {
                    $this->updateTemplate($updateChildren, $templateRoot);
                }
            }

            $templateRepository->save($templateRoot);
        }catch(Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            $this->_redirect('adminhtml/recurrencetemplate', ['_secure' => true]);
        }

        $this->_redirect('adminhtml/recurrencetemplate', ['_secure' => true]);
    }

    protected function validatePostData($postData)
    {
        try {
            //creating a templateRoot from json_data just to validate the input.
            (new TemplateRootFactory)->createFromPostData($postData);
        } catch (\Exception $exception) {
            $this->errors['recurrency_plan_input_error'] = $exception->getMessage();
        }

        if (count($this->errors)) {
            /**
             * @todo Do something with errors
             */
            return false;
        };

        return true;
    }
}
