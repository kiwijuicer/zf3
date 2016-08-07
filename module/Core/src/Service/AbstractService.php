<?php
declare(strict_types = 1);

namespace Core\Service;

use Core\Entity\AbstractEntity;
use Zend\Db\TableGateway\TableGateway;

class AbstractService {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {echo 'haaaaaaaaaaaaaaaaaaaaaaaaallo';
        $this->tableGateway = $tableGateway;
    }

    protected function getGateway() : TableGateway
    {
        return $this->tableGateway;
    }

    public function get(int $primaryKey)
    {
        $rowset = $this->tableGateway->select(['id' => $primaryKey]);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception('Could not find entity with primary ke' . $primaryKey);
        }
        return $row;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function save(AbstractEntity $entity)
    {
        if (0 === $entity->getId()) {
            $this->tableGateway->insert($entity->getFields());
        } else {
            if ($this->get($entity->getId())) {
                $this->tableGateway->update($entity->getFields(), ['id' => $entity->getId()]);
            } else {
                throw new \Exception('Entity not found');
            }
        }
    }

    public function delete(int $primaryKey)
    {
        $this->tableGateway->delete(['id' => $primaryKey]);
    }
}