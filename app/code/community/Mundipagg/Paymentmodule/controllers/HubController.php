<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use \MundipaggModuleBackend\Hub\Services\HubIntegrationService;

class Mundipagg_Paymentmodule_HubController
    extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        Mage::helper('paymentmodule/exception')->initExceptionHandler();
        Mundipagg_Paymentmodule_Model_MagentoModuleCoreSetup::bootstrap();
    }

    public function generateIntegrationTokenAction()
    {
        $installSeed = uniqid(); //@todo get seed from url
        $hubIntegrationService = new HubIntegrationService();

        return $this->setResponse(
            $hubIntegrationService->startHubIntegration($installSeed)
        );
    }

    public function validateInstallAction()
    {
        $params = Mage::app()->getRequest()->getParams();

        $installToken = $params['&install_token'];

        $authorizationCode = $params['authorization_code'];

        $webhookUrl = Mage::getUrl('paymentmodule/webhook');

        $hubCallbackUrl = Mage::getUrl('paymentmodule/hub/command');

        $hubIntegrationService = new HubIntegrationService();
        $hubIntegrationService->endHubIntegration(
            $installToken,
            $authorizationCode,
            $hubCallbackUrl,
            $webhookUrl
        );
    }

    public function statusAction()
    {
        $hubIntegrationService = new HubIntegrationService();
        return $this->setResponse(
            $hubIntegrationService->getHubStatus()
        );
    }

    public function commandAction()
    {
        $body = json_decode(file_get_contents('php://input'));

        $hubIntegrationService = new HubIntegrationService();
        $hubIntegrationService->executeCommandFromPost($body);
    }

    protected function setResponse($response, $status = 200)
    {
        return $this->getResponse()
            ->clearHeaders()
            ->setHeader('HTTP/1.0', $status , true)
            ->setHeader('Content-Type', 'text/html') // can be changed to json, xml...
            ->setBody($response);
    }

}