<?php

namespace ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity;

/**
 * Performance
 */
class Performance
{
    /**
     * @var string|null
     */
    private $venue;

    /**
     * @var string|null
     */
    private $city;

    /**
     * @var string|null
     */
    private $state;

    /**
     * @var \DateTime
     */
    private $performanceDate;

    /**
     * @var int
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $recordings;

    /**
     * @var \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Artist
     */
    private $artist;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->recordings = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set venue.
     *
     * @param string|null $venue
     *
     * @return Performance
     */
    public function setVenue($venue = null)
    {
        $this->venue = $venue;

        return $this;
    }

    /**
     * Get venue.
     *
     * @return string|null
     */
    public function getVenue()
    {
        return $this->venue;
    }

    /**
     * Set city.
     *
     * @param string|null $city
     *
     * @return Performance
     */
    public function setCity($city = null)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string|null
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state.
     *
     * @param string|null $state
     *
     * @return Performance
     */
    public function setState($state = null)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state.
     *
     * @return string|null
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set performanceDate.
     *
     * @param \DateTime $performanceDate
     *
     * @return Performance
     */
    public function setPerformanceDate($performanceDate)
    {
        $this->performanceDate = $performanceDate;

        return $this;
    }

    /**
     * Get performanceDate.
     *
     * @return \DateTime
     */
    public function getPerformanceDate()
    {
        return $this->performanceDate;
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
     * Add recording.
     *
     * @param \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Recording $recording
     *
     * @return Performance
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

    /**
     * Set artist.
     *
     * @param \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Artist $artist
     *
     * @return Performance
     */
    public function setArtist(\ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Artist $artist)
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * Get artist.
     *
     * @return \ApiSkeletonsTest\Laravel\HAL\Doctrine\Entity\Artist
     */
    public function getArtist()
    {
        return $this->artist;
    }
}
