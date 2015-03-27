<?php

namespace StatisticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RouteStop
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="StatisticsBundle\Repository\RouteStopRepository")
 */
class RouteStop
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
     * @ORM\Column(name="position", type="integer")
     */
    private $position;

	/**
	 * @ORM\ManyToOne(targetEntity="Route")
	 * @ORM\JoinColumn(name="route_id", referencedColumnName="id")
	 **/
	private $route;

	/**
	 * @ORM\ManyToOne(targetEntity="Stop")
	 * @ORM\JoinColumn(name="stop_id", referencedColumnName="id")
	 **/
	private $stop;

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
     * Set position
     *
     * @param integer $position
     * @return RouteStop
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return integer 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set route
     *
     * @param \StatisticsBundle\Entity\Route $route
     * @return RouteStop
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
     * Set stop
     *
     * @param \StatisticsBundle\Entity\Stop $stop
     * @return RouteStop
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
}
