<?php

namespace StatisticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\Exclude;

/**
 * Route
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="StatisticsBundle\Repository\RouteRepository")
 */
class Route
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

	/**
	 * @ORM\ManyToOne(targetEntity="Transport")
	 * @ORM\JoinColumn(name="transport_id", referencedColumnName="id")
	 * @Exclude
	 **/
	private $transport;

	/**
	 * @ORM\OneToMany(targetEntity="Time", mappedBy="route")
	 * @Exclude
	 **/
	private $times;

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
     * @return Route
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
     * Set transport
     *
     * @param \StatisticsBundle\Entity\Transport $transport
     * @return Route
     */
    public function setTransport(\StatisticsBundle\Entity\Transport $transport = null)
    {
        $this->transport = $transport;

        return $this;
    }

    /**
     * Get transport
     *
     * @return \StatisticsBundle\Entity\Transport 
     */
    public function getTransport()
    {
        return $this->transport;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->times = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add times
     *
     * @param \StatisticsBundle\Entity\Time $times
     * @return Route
     */
    public function addTime(\StatisticsBundle\Entity\Time $times)
    {
        $this->times[] = $times;

        return $this;
    }

    /**
     * Remove times
     *
     * @param \StatisticsBundle\Entity\Time $times
     */
    public function removeTime(\StatisticsBundle\Entity\Time $times)
    {
        $this->times->removeElement($times);
    }

    /**
     * Get times
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getTimes()
    {
        return $this->times;
    }
}
