<?php

namespace Mundipagg\Recurrence\Controller;

use Mundipagg\Aggregates\Template\PlanStatusValueObject;



class Recurrence
{
    public $data;
    public $platform;
    public $language;
    public $templateDir = 'extension/payment/mundipagg/recurrence/';
    protected $recurrenceSettings;

    public function __construct($platform)
    {
        $this->platform = $platform;
    }
}