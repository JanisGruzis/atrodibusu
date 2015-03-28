<?php

namespace StatisticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;

/**
 * Transport
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="StatisticsBundle\Repository\TransportRepository")
 */
class Transport
{
	const TYPE_BUS = 'Autobuss';
	const TYPE_TROLLEYBUS = 'Troleibuss';
	const TYPE_TRAM = 'Tramvajs';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=64)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=64)
     */
    private $type;

	/**
	 * @ORM\OneToMany(targetEntity="Route", mappedBy="transport")
	 **/
	private $routes;

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
     * Set name
     *
     * @param string $name
     * @return Transport
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set number
     *
     * @param string $number
     * @return Transport
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string 
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Transport
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->routes = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add routes
     *
     * @param \StatisticsBundle\Entity\Route $routes
     * @return Transport
     */
    public function addRoute(\StatisticsBundle\Entity\Route $routes)
    {
        $this->routes[] = $routes;

        return $this;
    }

    /**
     * Remove routes
     *
     * @param \StatisticsBundle\Entity\Route $routes
     */
    public function removeRoute(\StatisticsBundle\Entity\Route $routes)
    {
        $this->routes->removeElement($routes);
    }

    /**
     * Get routes
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}
