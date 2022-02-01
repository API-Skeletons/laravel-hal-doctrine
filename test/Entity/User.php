<?php

namespace ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity;

/**
 * User
 */
class User
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $email;

    /**
     * @var int
     */
    private $id;

    /**
     * @var \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Address
     */
    private $address;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $recordings;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recordings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return User
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set email.
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
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
     * Set address.
     *
     * @param \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Address|null $address
     *
     * @return User
     */
    public function setAddress(\ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Address $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Address|null
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Add recording.
     *
     * @param \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Recording $recording
     *
     * @return User
     */
    public function addRecording(\ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Recording $recording)
    {
        $this->recordings[] = $recording;

        return $this;
    }

    /**
     * Remove recording.
     *
     * @param \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Recording $recording
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRecording(\ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Recording $recording)
    {
        return $this->recordings->removeElement($recording);
    }

    /**
     * Get recordings.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecordings()
    {
        return $this->recordings;
    }
}
