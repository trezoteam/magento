<?php

namespace Mundipagg\Core\Repositories;

use Mundipagg\Core\Interfaces\AggregateRootInterface;
use Mundipagg\Core\Factories\Configuration as ConfigurationFactory;

class Configuration extends AbstractRep
{
    protected function create(AggregateRootInterface &$object)
    {
        $jsonEncoded = json_encode($object);

        $query = "
             INSERT INTO `" . $this->db->getTable('CONFIGURATION_TABLE') . "` (data)" .
            "VALUES ('$jsonEncoded')
        ";

        $this->db->query($query);
    }

    protected function update(AggregateRootInterface &$object)
    {
        $query = "
            SELECT * FROM `" . $this->db->getTable('CONFIGURATION_TABLE') . "`;
        ";

        $result = $this->db->query($query);

        if ($result->num_rows == 0) {
            return $this->create($object);
        }

        $jsonEncoded = json_encode($object);
        $query = "
            UPDATE `" . $this->db->getTable('CONFIGURATION_TABLE') . "` set data = '{$jsonEncoded}';
        ";
        
        return $this->db->query($query);
    }

    public function delete(AggregateRootInterface $object)
    {
        // TODO: Implement delete() method.
    }

    public function find($objectId)
    {
        $query = "SELECT data FROM `" . $this->db->getTable('CONFIGURATION_TABLE') . "`";
        $query .= "WHERE id = 1;";

        $result = $this->db->query($query);

        $factory = new ConfigurationFactory();

        if (empty($result->row)) {
            return $factory->createEmpty();
        }

        return $factory->createFromJsonData($result->row['data']);
    }

    public function listEntities($limit, $listDisabled)
    {
        // TODO: Implement listEntities() method.
    }
}