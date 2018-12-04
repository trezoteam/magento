<?php

namespace Mundipagg\HubIntegration\Aggregates;

use Mundipagg\Core\AggregateRootInterface;

final class InstallToken implements AggregateRootInterface
{
    const LIFE_SPAN = 1800; //time in seconds

    /** @var int */
    private $id;

    /** @var string */
    private $token;
    /** @var bool */
    private $used;
    /** @var int */
    private $createdAtTimestamp;
    /** @var int */
    private $expireAtTimestamp;

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return InstallToken
     */
    public function setToken($token)
    {
        $this->token = strval($token);
        return $this;
    }

    /**
     * @return bool
     */
    public function isUsed()
    {
        return $this->used;
    }

    /**
     * @param bool $used
     * @return InstallToken
     */
    public function setUsed($used)
    {
        $this->used = filter_var($used, FILTER_VALIDATE_BOOLEAN);
        return $this;
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return !(time() < $this->expireAtTimestamp);
    }

    /**
     * @return int
     */
    public function getCreatedAtTimestamp()
    {
        return $this->createdAtTimestamp;
    }

    /**
     * @param int $createdAtTimestamp
     * @return InstallToken
     */
    public function setCreatedAtTimestamp($createdAtTimestamp)
    {
        $this->createdAtTimestamp = intval($createdAtTimestamp);
        return $this;
    }

    /**
     * @return int
     */
    public function getExpireAtTimestamp()
    {
        return $this->expireAtTimestamp;
    }

    /**
     * @param int $expireAtTimestamp
     * @return InstallToken
     */
    public function setExpireAtTimestamp($expireAtTimestamp)
    {
        $this->expireAtTimestamp = intval($expireAtTimestamp);
        return $this;
    }

    /**
     * @return bool
     */
    public function isDisabled()
    {
        return $this->isExpired();
    }

    public function setDisabled($isDisabled)
    {
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = intval($id);
        return $this;
    }

    /**
     * @return array|mixed
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'token' => $this->token,
            'used' => $this->used,
            'expired' => $this->isExpired(),
            'createdAtTimestamp' => $this->createdAtTimestamp,
            'expireAtTimestamp' => $this->expireAtTimestamp
        ];
    }
}