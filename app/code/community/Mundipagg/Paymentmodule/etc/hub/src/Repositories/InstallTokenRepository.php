<?php

namespace Mundipagg\HubIntegration\Repositories;

use Mundipagg\Core\Interfaces\AggregateRootInterface;
use Mundipagg\HubIntegration\Aggregates\InstallToken;
use Mundipagg\HubIntegration\Factories\InstallTokenFactory;
use Mundipagg\Core\Repositories\AbstractRep;

class InstallTokenRepository extends AbstractRep
{

    /**
     * @param InstallToken $object
     * @throws \Exception
     */
    protected function create(AggregateRootInterface &$installToken)
    {
        /**
         * @var InstallToken $installToken
         */
        $token = $installToken->getToken();
        $used = $installToken->isUsed() ? 'true' : 'false';
        $created_at_timestamp = $installToken->getCreatedAtTimestamp();
        $expire_at_timestamp = $installToken->getExpireAtTimestamp();

        $query = "
             INSERT INTO `" . $this->db->getTable('HUB_INSTALL_TOKEN') .  "`" .
            " (token, used, created_at_timestamp, expire_at_timestamp) " .
            " VALUES ('$token',$used,$created_at_timestamp,$expire_at_timestamp)
        ";

        $this->db->query($query);
    }

    protected function update(AggregateRootInterface &$installToken)
    {
        /**
         * @var InstallToken $installToken
         */
        $token = $installToken->getToken();
        $used = $installToken->isUsed() ? 'true' : 'false';
        $created_at_timestamp = $installToken->getCreatedAtTimestamp();
        $expire_at_timestamp = $installToken->getExpireAtTimestamp();

        $query = "
             UPDATE `" . $this->db->getTable('HUB_INSTALL_TOKEN') . "`" .
            " SET " .
            "
                token = '$token' ,
                used = $used ,
                created_at_timestamp = $created_at_timestamp , 
                expire_at_timestamp = $expire_at_timestamp
            " .
            " WHERE id = {$installToken->getId()}"
        ;

        $this->db->query($query);
    }

    public function delete(AggregateRootInterface $object)
    {
        // TODO: Implement delete() method.
    }

    public function find($objectId)
    {
        // TODO: Implement find() method.
    }

    /**
     * @param $limit
     * @param $listDisabled
     *
     * @return InstallToken[]
     */
    public function listEntities($limit = 0, $listDisabled = false)
    {
        $query = "SELECT * FROM `" . $this->db->getTable('HUB_INSTALL_TOKEN') . "` as t";

        if (!$listDisabled) {
            $query .= " WHERE t.expire_at_timestamp > " . time();
        }

        if ($limit !== 0) {
            $limit = intval($limit);
            $query .= " LIMIT $limit";
        }

        $result = $this->db->query($query . ";");

        $factory = new InstallTokenFactory();
        $installTokens = [];

        foreach ($result->rows as $row) {
            $installToken = $factory->createFromDBData($row);
            $installTokens[] = $installToken;
        }

        return $installTokens;
    }

    /**
     * @param string $token
     * @return InstallToken|null
     */
    public function findByToken($token)
    {
        $query = "SELECT * FROM `" . $this->db->getTable('HUB_INSTALL_TOKEN') . "` as t ";
        $query .= "WHERE t.token = '$token';";

        $result = $this->db->query($query );

        if ($result->num_rows > 0) {
            $factory = new InstallTokenFactory();
            return $factory->createFromDBData($result->row);
        }

        return null;
    }
}