<?php
/**
 * Class RoboFile
 * doesn't allow 'get' usage in method names
 *
 */
class RoboFile extends \Robo\Tasks
{
    private $configXml = 'app/code/community/Mundipagg/Paymentmodule/etc/config.xml';
    private $mundipaggPaymentModuleXml = 'mundipagg_payment_module.xml';
    private $currentVersion;

    public function __construct()
    {
        $this->currentVersion = $this->versionGet();
    }

    public function versionBump($param = '')
    {
        $version = explode('.', $this->currentVersion);

        switch ($param) {
            case 'major' :
                $newVersion = ($version[0] + 1) . '.' . 0 . '.' . 0;

                break;
            case 'minor' :
                $newVersion = $version[0] . '.' . ($version[1] + 1) . '.' . 0;
                break;
            case 'patch' :
                $newVersion =
                    $version[0] . '.' . $version[1] . '.' . ($version[2] + 1);
                break;
            default:
                throw new Exception("Missing param. " .
                    "Allowed values: 'major', 'minor' or 'patch'");
        }

        $this->versionUpdate($newVersion);
    }

    /**
     * Update module version
     * @param $newVersion
     */
    public function versionUpdate($newVersion = null)
    {
        if (!$newVersion) {
            throw new Exception("Missing param: version number");
        }

        $currentVersion = $this->currentVersion;

        $this->taskReplaceInFile($this->configXml)
            ->from('<version>' . $currentVersion . '</version>')
            ->to('<version>' . $newVersion . '</version>')
            ->run();

        $this->taskReplaceInFile($this->mundipaggPaymentModuleXml)
            ->from('<version>' . $currentVersion . '</version>')
            ->to('<version>' . $newVersion . '</version>')
            ->run();

        $this->setReleaseChanges();

        echo "Version updated from: " . $currentVersion . ' to: ' . $newVersion;
        echo "\n";
    }

    public function versionGet()
    {
        $xml = file_get_contents($this->configXml);
        preg_match(
            '/<version>(?P<currentVersion>.*)<\/version>/',
            $xml,
            $matches
        );
        return $matches['currentVersion'];
    }

    private function getReleaseChanges()
    {
        $gitLog = `git log --pretty=format:"%b"`;
        return preg_replace("#[\n]+#", "\n-", $gitLog);
    }

    private function setReleaseChanges()
    {
        $this->taskReplaceInFile($this->mundipaggPaymentModuleXml)
            ->regex("/<notes>[\s\S]*?<\/notes>/")
            ->to('<notes>' . $this->getReleaseChanges() . '</notes>')
            ->run();
    }
}