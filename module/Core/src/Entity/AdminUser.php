<?php
declare(strict_types = 1);

namespace Core\Entity;

class AdminUser extends AbstractEntity
{
    const ROLE_USER = 'user';

    const ROLE_ADMIN = 'admin';

    const ROLE_LIST = [
        self::ROLE_USER,
        self::ROLE_ADMIN
    ];

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

    /**
     * Returns password
     *
     * @return string
     */
    public function getPassword() : string
    {
        return (string)$this->fields['password'];
    }

    /**
     * Sets the password
     *
     * @param string $value
     * @return void
     */
    public function setPassword(string $value)
    {
        $this->fields['password'] = (string)password_hash($value, PASSWORD_BCRYPT);
    }

    /**
     * Returns email
     *
     * @return string
     */
    public function getEmail() : string
    {
        return (string)$this->fields['email'];
    }

    /**
     * Sets the email
     *
     * @param string $value
     * @return void
     */
    public function setEmail(string $value)
    {
        $this->fields['email'] = (string)$value;
    }

    /**
     * Returns role
     *
     * @return string
     */
    public function getRole() : string
    {
        return (string)$this->fields['role'];
    }

    /**
     * Sets the role
     *
     * @param string $value
     * @return void
     */
    public function setRole(string $value)
    {
        if (!in_array($value, self::ROLE_LIST, true)) {
            throw new \InvalidArgumentException('Given user role (' . $value . ') is not valid');
        }
        $this->fields['username'] = (string)$value;
    }
}