<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Equipment
 *
 * @ORM\Table(name="equipment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EquipmentRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Equipment
{
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
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_active", type="boolean")
     */
    private $isActive;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="UserEquipment", mappedBy="equipment")
     */
    private $userEquipment;

    /**
     * @var array
     *
     * @ORM\Column(name="slots", type="json_array")
     */
    private $slots;

    /**
     * Get id
     *
     * @return int
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
     * @return Equipment
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
     * Set description
     *
     * @param string $description
     *
     * @return Equipment
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Equipment
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return bool
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Equipment
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Equipment
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @ORM\PrePersist
     */
    public function prePersist()
    {
      $this->createdAt = new \DateTime();
      $this->updatedAt = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function PreUpdate()
    {
      $this->updatedAt = new \DateTime();
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userEquipment = new ArrayCollection();
    }

    /**
     * Add userEquipment
     *
     * @param UserEquipment $userEquipment
     *
     * @return Equipment
     */
    public function addUserEquipment(\AppBundle\Entity\UserEquipment $userEquipment)
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

    /**
     * Set slots
     *
     * @param array $slots
     *
     * @return Equipment
     */
    public function setSlots($slots)
    {
        $this->slots = $slots;

        return $this;
    }

    /**
     * Get slots
     *
     * @return array
     */
    public function getSlots()
    {
        return $this->slots;
    }
}
