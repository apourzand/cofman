<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;


/**
 * Accessprofile
 *
 * @ORM\Table(name="accessprofile")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AccessprofileRepository")
 */
class Accessprofile
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="time")
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="time")
     */
    private $endTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_weekend", type="boolean")
     */
    private $isWeekend;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserEquipment", mappedBy="accessprofile")
     */
    private $userEquipment;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userEquipment = new ArrayCollection();
    }


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
     *
     * @return Accessprofile
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
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return Accessprofile
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param \DateTime $endTime
     *
     * @return Accessprofile
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set isWeekend
     *
     * @param boolean $isWeekend
     *
     * @return Accessprofile
     */
    public function setIsWeekend($isWeekend)
    {
        $this->isWeekend = $isWeekend;

        return $this;
    }

    /**
     * Get isWeekend
     *
     * @return boolean
     */
    public function getIsWeekend()
    {
        return $this->isWeekend;
    }

    /**
     * Add userEquipment
     *
     * @param UserEquipment $userEquipment
     *
     * @return Accessprofile
     */
    public function addUserEquipment(UserEquipment $userEquipment)
    {
        $this->userEquipment[] = $userEquipment;

        return $this;
    }

    /**
     * Remove userEquipment
     *
     * @param UserEquipment $userEquipment
     */
    public function removeUserEquipment(UserEquipment $userEquipment)
    {
        $this->userEquipment->removeElement($userEquipment);
    }

    /**
     * Get userEquipment
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUserEquipment()
    {
        return $this->userEquipment;
    }

    public function __toString()
    {
        return (string) $this->getName();
    }
}
