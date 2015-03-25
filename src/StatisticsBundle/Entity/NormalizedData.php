<?php

namespace StatisticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NormalizedData
 *
 * @ORM\Table(name="normalized_data")
 * @ORM\Entity(repositoryClass="StatisticsBundle\Repository\NormalizedDataRepository")
 */
class NormalizedData
{
	const TYPE_CRIME = 'crime';
	const TYPE_AIR_QUALITY = 'airquality';

	public static $TYPE = [
		self::TYPE_CRIME => 'Noziedzības līmenis',
		self::TYPE_AIR_QUALITY => 'Gaisa kvalitāte',
	];

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
     * @ORM\Column(name="type", type="string", length=32)
     */
    private $type;


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
     * Set type
     *
     * @param string $type
     * @return NormalizedData
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
}
