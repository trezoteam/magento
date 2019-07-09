<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use Mundipagg\Core\Hub\Services\HubIntegrationService;
use Mundipagg\Magento\Concrete\MagentoModuleCoreSetup as MPSetup;

class Mundipagg_Paymentmodule_HubController
    extends Mage_Core_Controller_Front_Action
{
    public function preDispatch()
    {
        parent::preDispatch();
        Mage::helper('paymentmodule/exception')->initExceptionHandler();

        $params = Mage::app()->getRequest()->getParams();
        if (isset($params['storeId'])) {
            Mage::app()->setCurrentStore($params['storeId']);
        }

        MPSetup::bootstrap();
    }

    public function generateIntegrationTokenAction()
    {
        $installSeed = uniqid(); //@todo get seed from url
        $hubIntegrationService = new HubIntegrationService();
        $installToken = $hubIntegrationService->startHubIntegration($installSeed);

        return $this->setResponse(
            $installToken->getValue()
        );
    }

    public function validateInstallAction()
    {
        $params = Mage::app()->getRequest()->getParams();

        $installToken = $params['&install_token'];

        $authorizationCode = $params['authorization_code'];

        $storeUrlHelper =  Mage::helper('paymentmodule/storeUrl');

        $webhookUrl = $storeUrlHelper->getBaseUrlByWebsiteId($params['storeId'], 'paymentmodule/webhook');
        $webhookUrl .= "?storeId=" . $params['storeId'];

        $hubCallbackUrl = $storeUrlHelper->getBaseUrlByWebsiteId($params['storeId'], 'paymentmodule/hub/command');
        $hubCallbackUrl .= "?storeId=" . $params['storeId'];

        $helperLog = Mage::helper('paymentmodule/log');
        $helperLog->info("WebhookUrl: " . $webhookUrl);
        $helperLog->info("HubUrl: " . $hubCallbackUrl);

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