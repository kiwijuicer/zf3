<?php
declare(strict_types = 1);

namespace Core\Entity;

/**
 * AbstractEntity
 *
 * @package Core\Entity
 */
class AbstractEntity
{
    /**
     * Member fields
     *
     * @var array
     */
    protected $fields = [];

    /**
     * Hydrates the fields with the values
     *
     * @param array $data
     */
    public function exchangeArray(array $data)
    {
        foreach ($data as $field => $value) {

            if (array_key_exists($field, $this->fields)) {
                $this->fields[$field] = $value;
            }

        }
    }

    /**
     * Returns the fields
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }
}