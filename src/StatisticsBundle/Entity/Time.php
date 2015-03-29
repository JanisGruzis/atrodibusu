<?php

namespace StatisticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;
use JMS\Serializer\Annotation\Type;

/**
 * Time
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="StatisticsBundle\Repository\TimeRepository")
 */
class Time
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
     * @var integer
     *
     * @ORM\Column(name="reissId", type="integer")
     */
    private $reissId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="weekday", type="boolean")
     */
    private $weekday;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="time", type="time")
	 * @Type("DateTime<'Y-m-d H:i:s'>")
     */
    private $time;

	/**
	 * @ORM\ManyToOne(targetEntity="Stop", inversedBy="times")
	 * @ORM\JoinColumn(name="stop_id", referencedColumnName="id")
	 * @Exclude
	 **/
	private $stop;

	/**
	 * @ORM\ManyToOne(targetEntity="Route", inversedBy="times")
	 * @ORM\JoinColumn(name="route_id", referencedColumnName="id")
	 **/
	private $route;

	/**
	 * @ORM\OneToMany(targetEntity="Statistics", mappedBy="time")
	 **/
	private $statistics;


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
     * Set reissId
     *
     * @param integer $reissId
     * @return Time
     */
    public function setReissId($reissId)
    {
        $this->reissId = $reissId;

        return $this;
    }

    /**
     * Get reissId
     *
     * @return integer 
     */
    public function getReissId()
    {
        return $this->reissId;
    }

    /**
     * Set weekday
     *
     * @param boolean $weekday
     * @return Time
     */
    public function setWeekday($weekday)
    {
        $this->weekday = $weekday;

        return $this;
    }

    /**
     * Get weekday
     *
     * @return boolean 
     */
    public function getWeekday()
    {
        return $this->weekday;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     * @return Time
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set stop
     *
     * @param \StatisticsBundle\Entity\Stop $stop
     * @return Time
     */
    public function setStop(\StatisticsBundle\Entity\Stop $stop = null)
    {
        $this->stop = $stop;

        return $this;
    }

    /**
     * Get stop
     *
     * @return \StatisticsBundle\Entity\Stop 
     */
    public function getStop()
    {
        return $this->stop;
    }

    /**
     * Set route
     *
     * @param \StatisticsBundle\Entity\Route $route
     * @return Time
     */
    public function setRoute(\StatisticsBundle\Entity\Route $route = null)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * Get route
     *
     * @return \StatisticsBundle\Entity\Route 
     */
    public function getRoute()
    {
        return $this->route;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->statistics = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add statistics
     *
     * @param \StatisticsBundle\Entity\Statistics $statistics
     * @return Time
     */
    public function addStatistic(\StatisticsBundle\Entity\Statistics $statistics)
    {
        $this->statistics[] = $statistics;

        return $this;
    }

    /**
     * Remove statistics
     *
     * @param \StatisticsBundle\Entity\Statistics $statistics
     */
    public function removeStatistic(\StatisticsBundle\Entity\Statistics $statistics)
    {
        $this->statistics->removeElement($statistics);
    }

    /**
     * Get statistics
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getStatistics()
    {
        return $this->statistics;
    }
}
