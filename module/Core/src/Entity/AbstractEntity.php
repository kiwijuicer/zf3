<?php
declare(strict_types = 1);

namespace Core\Entity;

class AbstractEntity
{
    protected $fields = [];

    public function exchangeArray($data)
    {
        foreach ($data as $field => $value) {

            if (array_key_exists($field, $this->fields)) {
                $this->fields[$field] = $value;
            }

        }
    }

    public function getFields()
    {
        return $this->fields;
    }
}