<?php

class Mundipagg_Paymentmodule_Model_Enum_Orderhistory
{
    private $chargeCreated  = "MP - Charge created";
    private $chargePaid  = "MP - Charge paid";

    public function __call($name, $arguments)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        return "";
    }


}