<?php

namespace Mundipagg\Integrity;

class IntegrityController
{
    /** @var ISystemInfo */
    protected $systemInfo;

    public function __construct(ISystemInfo $systemInfo)
    {
        $this->systemInfo = $systemInfo;
    }

    public function getSystemInformation()
    {
        return [
            'phpVersion' => phpversion(),
            'moduleVersion' => $this->systemInfo->getModuleVersion(),
            'platformVersion' => $this->systemInfo->getPlatformVersion()
        ];
    }

}
