<?php

namespace StatisticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;

/**
 * Statistics
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="StatisticsBundle\Repository\StatisticsRepository")
 */
class Statistics
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @var integer
     *
     * @ORM\Column(name="boarded", type="integer")
     */
    private $boarded;

    /**
     * @var integer
     *
     * @ORM\Column(name="driving", type="integer")
     */
    private $driving;

    /**
     * @var integer
     *
     * @ORM\Column(name="etalonCount", type="integer")
     */
    private $etalonCount;

	/**
	 * @ORM\ManyToOne(targetEntity="Time")
	 * @ORM\JoinColumn(name="time_id", referencedColumnName="id")
	 * @Exclude
	 **/
	private $time;

	/**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Statistics
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set boarded
     *
     * @param integer $boarded
     * @return Statistics
     */
    public function setBoarded($boarded)
    {
        $this->boarded = $boarded;

        return $this;
    }

    /**
     * Get boarded
     *
     * @return integer 
     */
    public function getBoarded()
    {
        return $this->boarded;
    }

    /**
     * Set driving
     *
     * @param integer $driving
     * @return Statistics
     */
    public function setDriving($driving)
    {
        $this->driving = $driving;

        return $this;
    }

    /**
     * Get driving
     *
     * @return integer 
     */
    public function getDriving()
    {
        return $this->driving;
    }

    /**
     * Set etalonCount
     *
     * @param integer $etalonCount
     * @return Statistics
     */
    public function setEtalonCount($etalonCount)
    {
        $this->etalonCount = $etalonCount;

        return $this;
    }

    /**
     * Get etalonCount
     *
     * @return integer 
     */
    public function getEtalonCount()
    {
        return $this->etalonCount;
    }

    /**
     * Set time
     *
     * @param \StatisticsBundle\Entity\Time $time
     * @return Statistics
     */
    public function setTime(\StatisticsBundle\Entity\Time $time = null)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \StatisticsBundle\Entity\Time 
     */
    public function getTime()
    {
        return $this->time;
    }
}
