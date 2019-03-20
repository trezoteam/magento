<?php

require_once Mage::getBaseDir('lib') . '/autoload.php';

use Mundipagg\Core\Kernel\Aggregates\Configuration;
use Mundipagg\Core\Kernel\Factories\ConfigurationFactory;
use Mundipagg\Magento\Concrete\MagentoModuleCoreSetup as MPSetup;
use Mundipagg\Core\Kernel\Repositories\ConfigurationRepository;


class Mundipagg_Paymentmodule_Model_Observer extends Varien_Event_Observer
{
    public function addAccountCreditcardWalletMenuItem(Varien_Event_Observer $observer)
    {
        $savedCreditCardsHelper = Mage::helper('paymentmodule/savedcreditcard');

        if ($savedCreditCardsHelper->isSavedCreditCardsEnabled()) {
            $update = $observer->getEvent()->getLayout()->getUpdate();
            $update->addHandle('creditcard_wallet_menu_item_handle');
        }
    }

    public function addAdditionalInformationToCheckout()
    {
        $block = Mage::app()->getLayout()->createBlock(
            'Mundipagg_Paymentmodule_Block_Checkout_Information'
        );

        $checkoutSuccessBlock =
            Mage::app()->getLayout()->getBlock('head');

        if ($checkoutSuccessBlock) {
            $checkoutSuccessBlock->append($block);
        }
    }

    public function injectTabs($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($this->isOrderPageView($block)) {
            $blockName = 'paymentmodule/adminhtml_order_charge';
            $block->addTabAfter(
                'order_charges',
                array(
                    'label'     => Mage::helper('paymentmodule')->__('Charges'),
                    'title'     => Mage::helper('paymentmodule')->__('Charges'),
                    'content'   => Mage::app()
                                    ->getLayout()
                                    ->createBlock($blockName)
                                    ->toHtml(),
                ),
                'order_transactions'
            );
        }
    }

    public function setActiveTab($observer)
    {
        $block = $observer->getEvent()->getBlock();
        if ($this->isOrderPageView($block)) {
            $block->setActiveTab('order_info');
        }
    }

    protected function isOrderPageView($block)
    {
        return $block instanceof Mage_Adminhtml_Block_Sales_Order_View_Tabs
            && $this->_getRequest()->getActionName() == 'view';
    }

    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }

    public function adminLoginChecks()
    {
        //check integrity
        $this->checkModuleIntegrity();


        //@todo check version
    }

    private function checkModuleIntegrity()
    {
        $integrityBlock = Mage::getBlockSingleton('paymentmodule/adminhtml_notification_integrityviolation');
        if ($integrityBlock->isViolated()) {
            $notificationId = $this->insertIntegrityViolationNotification();
            $notification = mage::getModel("adminnotification/inbox");
            $notification->load($notificationId -1);
        }
    }

    private function insertIntegrityViolationNotification()
    {
        $data = array(
            'severity'      => Mage_AdminNotification_Model_Inbox::SEVERITY_CRITICAL,
            'title'         => 'Mundipagg Module Integrity Violated!',
            'description'   => 'Foram detectadas alterações no módulo de pagamentos Mundipagg.',
            //'url'           => 'https://www.github.com/mundipagg/magento'
        );
        return $this->insertNotification($data);
    }

    private function insertNotification($data)
    {
        $data = array_merge(
            $data,
            array(
                'mp' =>'test',
                'is_read'       => 0,
                'is_remove'     => 0,
                'data_added'    => now()
            )
        );
        $notification = mage::getModel("adminnotification/inbox");
        $notification->setData($data);

        $notification->save();
        return $notification->getNotificationId();
    }

    private function checkModuleVersion()
    {
        //@todo
    }

    public function saveConfigurations($event)
    {
        MPSetup::bootstrap();

        $params = Mage::app()->getRequest()->getParams();

        /** @var Mundipagg_Paymentmodule_Model_Config_General $generalConfig */
        $generalConfig = Mage::getModel('paymentmodule/config_general');

        $config = MPSetup::getModuleConfiguration();

        /** @todo Set all configurations */
        $config->setEnabled($generalConfig->isEnabled());

        $defaultConfig = $this->getDefaultConfigBySaveConfigurations($config, $params['groups']);

        $config->setDefaultConfiguration($defaultConfig->configuration);

        $config->addDefaultAttributes($defaultConfig->attributes);

        $configRepo = new ConfigurationRepository();
        $configRepo->save($config);
    }

    protected function getDefaultConfigBySaveConfigurations(Configuration $config, $params)
    {
        $defaultConfig = MPSetup::loadModuleConfigurationByStore(0);

        $attributes = []; // Atributos que usarao o default
        $defaultValues = []; // Valores dos attributos da config default


        $oReflectionClass = new ReflectionClass(Configuration::class);
        foreach ( $oReflectionClass->getProperties() as $item) {
            $attributes[$item->getName()] = false;
        };

        // get general config
        $generalConfig = $params['general_group']['fields'];
        foreach ($generalConfig as $key => $value) {
            if ($key == 'hub_integration') {

                // definindo o atributo para usar a config default
                $attributes['getSecretKey'] = true;
                $attributes['getPublicKey'] = true;
                $attributes['getHubInstallId'] = true;
                $attributes['isHubEnabled'] = true;

                // pegando o valor da config default para criar um objeto de configuração default
                $defaultValues['keys'][Configuration::KEY_SECRET] = $defaultConfig->getSecretKey();
                $defaultValues['keys'][Configuration::KEY_PUBLIC] = $defaultConfig->getPublicKey();
                $defaultValues['hubInstallId'] = $defaultConfig->getHubInstallId();
            }
        }

        //Criar configuração default para a store
        $configFactory = new ConfigurationFactory();
        $configuration = $configFactory->createFromJsonData(json_encode($defaultValues));

        $result = new \StdClass();
        $result->attributes = $attributes;
        $result->configuration = $configuration;


        return $result;
    }
}
