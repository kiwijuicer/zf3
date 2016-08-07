<?php
declare(strict_types = 1);

namespace Core\Service;

use Core\Entity\AbstractEntity;
use Zend\Db\TableGateway\TableGateway;

/**
 * Abstract Service
 *
 * @package Core\Service
 */
class AbstractService {

    /**
     * The table gateway
     * @var \Zend\Db\TableGateway\TableGateway
     */
    protected $tableGateway;

    /**
     * Constructor for Abstract Service
     * @param \Zend\Db\TableGateway\TableGateway $tableGateway
     */
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    /**
     * Returns the table gateway
     *
     * @return \Zend\Db\TableGateway\TableGateway
     */
    protected function getGateway() : TableGateway
    {
        return $this->tableGateway;
    }

    /**
     * Returns the entity
     *
     * @param int $primaryKey
     * @return array|\ArrayObject|null
     * @throws \Exception
     */
    public function get(int $primaryKey)
    {
        $rowset = $this->tableGateway->select(['id' => $primaryKey]);
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception('Could not find entity with primary ke' . $primaryKey);
        }
        return $row;
    }

    /**
     * Returns all
     *
     * @return \Zend\Db\ResultSet\ResultSet
     */
    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    /**
     * Saves the entity
     *
     * @param \Core\Entity\AbstractEntity $entity
     * @throws \Exception
     */
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

    /**
     * Deletes the entity
     *
     * @param int $primaryKey
     */
    public function delete(int $primaryKey)
    {
        $this->tableGateway->delete(['id' => $primaryKey]);
    }
}