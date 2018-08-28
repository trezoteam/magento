<?php

class Mundipagg_Paymentmodule_Helper_Log extends Mage_Core_Helper_Abstract
{
    protected $level;
    protected $method;
    protected $logLabel = '';

    public function __construct($method = '')
    {
        $this->method = $method;
    }

    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    public function setLogLabel($logLabel)
    {
        $this->logLabel = $logLabel;
        return $this;
    }

    public function getLogLabel()
    {
        return $this->logLabel;
    }

    public function info($msg)
    {
        $this->level = Zend_Log::INFO;
        $this->write($msg);
    }

    public function debug($msg)
    {
        $this->level = Zend_Log::DEBUG;
        $this->write($msg);
    }

    public function warning($msg)
    {
        $this->level = Zend_Log::WARN;
        $this->write($msg);
    }

    public function error($msg, $logExceptionFile = false)
    {
        $exception = new Exception($msg);
        $this->level = Zend_Log::ERR;
        $this->write($msg);

        if ($logExceptionFile) {
            Mage::logException($exception);
        }
    }

    public function getModuleLogFilenamePrefix()
    {
        return "Mundipagg_PaymentModule_";
    }

    protected function write($msg)
    {
        $logIsEnabled = boolval(Mage::getStoreConfig('mundipagg_config/log_group/enabled'));

        if ($logIsEnabled === false) {
            return;
        }

        $metaData = Mage::helper('paymentmodule/data')->getMetaData();
        $version = $metaData['module_version'];
        $file =  $this->getModuleLogFilenamePrefix() . date('Y-m-d') . ".log";
        $method = $this->method;
        $newMsg = "v{$version} ";

        if (!empty($method)) {
            $logLabel = $this->logLabel;

            if (!empty($logLabel)) {
                $newMsg .= "[{$this->method}] {$this->logLabel} | {$msg}";
            } else {
                $newMsg .= "[{$this->method}] {$msg}";
            }
        } else {
            $newMsg .= $msg;
        }

        Mage::log($newMsg, $this->level, $file);
    }
}