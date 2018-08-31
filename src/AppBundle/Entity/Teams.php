<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Teams
 *
 * @ORM\Table(name="teams")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TeamsRepository")
 */
class Teams {

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")

     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="strip", type="integer")
     * @ORM\ManyToOne(targetEntity="League", inversedBy="id")
     * @ORM\JoinColumn(name="strip", referencedColumnName="id")

     */
    private $strip;

    /**
     * Get id
     *
     * @return int
     */

    
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Teams
     */
    public function setName($name) {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set strip
     *
     * @param string $strip
     *
     * @return Teams
     */
    public function setStrip($strip) {
        $this->strip = $strip;

        return $this;
    }

    /**
     * Get strip
     *
     * @return string
     */
    public function getStrip() {
        return $this->strip;
    }

    /**
     * Add item
     *
     * @param League $item
     */
    public function addItem(League $item) {
        $this->items->add($item);
    }

}
