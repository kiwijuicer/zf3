<?php
declare(strict_types = 1);

namespace Core\Entity;

class AdminUser extends AbstractEntity
{
    protected $fields = [
        'id' => null,
        'username' => null,
        'password' => null,
        'email' => null,
        'first_name' => null,
        'last_name' => null,
        'status' => null,
        'updated' => null
    ];

    /**
     * Returns id
     *
     * @return int
     */
    public function getId() : int
    {
        return (int)$this->fields['id'];
    }

    /**
     * Sets the id
     *
     * @param int $value
     * @return void
     */
    public function setId(int $value)
    {
        $this->fields['id'] = (int)$value;
    }

    /**
     * Returns username
     *
     * @return string
     */
    public function getUsername() : string
    {
        return (string)$this->fields['username'];
    }

    /**
     * Sets the username
     *
     * @param string $value
     * @return void
     */
    public function setUsername(string $value)
    {
        $this->fields['username'] = (string)$value;
    }

}