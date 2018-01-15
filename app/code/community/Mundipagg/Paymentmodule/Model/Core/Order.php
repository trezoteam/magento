<?php

class Mundipagg_Paymentmodule_Model_Core_Order  extends Mundipagg_Paymentmodule_Model_Core_Base
{
    public function __call($name, $arguments)
    {
        if (!method_exists($this, $this->fromSnakeToCamel($name))) {
            throw new \Exception('UNKNOWN WEBHOOK ACTION');
        }

        return $this->{$name}($arguments[0]);
    }
}
