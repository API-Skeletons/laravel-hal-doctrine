<?php

namespace ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity;

/**
 * Address
 */
class Address
{
    /**
     * @var string
     */
    private $address;

    /**
     * @var int
     */
    private $id;

    /**
     * @var \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\User
     */
    private $user;


    /**
     * Set address.
     *
     * @param string $address
     *
     * @return Address
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user.
     *
     * @param \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\User|null $user
     *
     * @return Address
     */
    public function setUser(\ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }
}
