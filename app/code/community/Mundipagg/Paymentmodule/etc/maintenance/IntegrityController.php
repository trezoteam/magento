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
            'integrityScript' => __FILE__,
            'modmanFilePath' => $this->systemInfo->getModmanPath(),
            'integrityCheckFile' => $this->systemInfo->getIntegrityCheckPath(),
            'phpVersion' => phpversion(),
            'moduleVersion' => $this->systemInfo->getModuleVersion(),
            'platformVersion' => $this->systemInfo->getPlatformVersion(),
            'platformRootDir' => $this->systemInfo->getPlatformRootDir(),
            'directoriesIgnored' => $this->systemInfo->getDirectoriesIgnored()
        ];
    }

    public function getIntegrityCheck()
    {
        $modmanPath = $this->systemInfo->getModmanPath();
        $integrityPath = $this->systemInfo->getIntegrityCheckPath();
        $directoriesIgnored = $this->systemInfo->getDirectoriesIgnored();

        $integrityEngine = new IntegrityEngine();
        $integrityCheck= $integrityEngine->verifyIntegrity($modmanPath, $integrityPath, $directoriesIgnored);

        return $integrityCheck;
    }

}
