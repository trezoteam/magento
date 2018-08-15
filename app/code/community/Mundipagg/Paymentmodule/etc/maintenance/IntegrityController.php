<?php

namespace Mundipagg\Integrity;

class IntegrityController
{
    /** @var SystemInfoInterface */
    protected $systemInfo;

    public function __construct(SystemInfoInterface $systemInfo)
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
            'directoriesIgnored' => $this->systemInfo->getDirectoriesIgnored(),
            'installType' => $this->systemInfo->getInstallType()
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

    public function getLogInfo()
    {
        $logs = $this->listLogFiles();
        return [
            'files' => $logs,
            'logConfigs' => [
                'includes' => $this->systemInfo->getDefaultLogFiles(),
                'moduleFilenamePrefix' => $this->systemInfo->getModulePrefixLogFile()
            ],
            'magentoLogsDirectory' => $this->systemInfo->getLogsDir()
        ];
    }

    public function checkMaintenanceRouteAccessPermition()
    {
        $secretKey = $this->systemInfo->getSecretKey();
        $secretKeyHashEncoded = base64_encode(hash('sha512',$secretKey));

        $urlParams = $this->systemInfo->getRequestParams();
        if (!isset($urlParams['token'])) {
            return false;
        }

        return $urlParams['token'] !== $secretKeyHashEncoded || strlen($secretKey) < 1;
    }

    public function compactFile($file)
    {
        $compactor = new FileCompactor($file);
        return $compactor->compact();
    }

    public function listLogFiles()
    {
        $integrityEngine = new IntegrityEngine();
        return $integrityEngine->listFilesOnDir($this->systemInfo->getLogsDir());
    }

    public function showGeneralInfo($title, $info)
    {
        echo "<h3>$title</h3>";
        echo '<pre>';
        print_r($info);
        echo '</pre>';
        echo json_encode($info);
    }

    public function showNonEmptyInfo($message, $info)
    {
        if (count($info) > 0) {
            echo "<h3 style='color:red'>$message</h3>";
            echo '<pre>';
            print_r($info);
            echo '</pre>';
            echo json_encode($info);
        }
    }

    public function showLogInfo()
    {
        $params = $this->systemInfo->getRequestParams();

        echo '<h3>Logs ('.count($this->listLogFiles()).')</h3><pre>';
        foreach($this->listLogFiles() as $logFile) {
            $link = "<strong style='color:red'>$logFile</strong><br />";
            if (is_readable($logFile)) {
                $fileRoute =  $this->systemInfo->getDownloadRouter();
                $fileRoute .= '?token=';
                $fileRoute .= isset($params['token']) ? $params['token'] : '';
                $fileRoute .= '&file=' . base64_encode($logFile);

                $link =
                    '<a href="'.$fileRoute.'" target="_self">' .
                    $logFile . ' (' . filesize($logFile) . ' bytes)'.
                    '</a><br />';
            }
            echo $link;
        }
    }
}
