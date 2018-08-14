<?php

namespace Mundipagg\Integrity;

interface ISystemInfo
{

    public function getModuleVersion();

    public function getPlatformVersion();

    public function getPlatformRootDir();

    public function getDirectoriesIgnored();

    public function getModmanPath();

    public function getIntegrityCheckPath();

    public function getInstallType();
}
