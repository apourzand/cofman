<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserEquipment
 *
 * @ORM\Table(name="user_equipment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserEquipmentRepository")
 */
class UserEquipment
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
     * @var \AppBundle\Entity\Accessprofile
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Accessprofile", inversedBy="userEquipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="accessprofile_id", referencedColumnName="id")
     * })
     */
    private $accessprofile;

    /**
     * @var \AppBundle\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="userEquipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * })
     */
    private $user;

    /**
     * @var \AppBundle\Entity\Equipment
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Equipment", inversedBy="userEquipment")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="equipment_id", referencedColumnName="id")
     * })
     */
    private $equipment;



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
     * Set accessprofile
     *
     * @param \AppBundle\Entity\Accessprofile $accessprofile
     *
     * @return UserEquipment
     */
    public function setAccessprofile(\AppBundle\Entity\Accessprofile $accessprofile = null)
    {
        $this->accessprofile = $accessprofile;

        return $this;
    }

    /**
     * Get accessprofile
     *
     * @return \AppBundle\Entity\Accessprofile
     */
    public function getAccessprofile()
    {
        return $this->accessprofile;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return UserEquipment
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set equipment
     *
     * @param \AppBundle\Entity\Equipment $equipment
     *
     * @return UserEquipment
     */

    /**
     * Get equipment
     *
     * @return \AppBundle\Entity\Equipment
     */
    public function getEquipment()
    {
        return $this->equipment;
    }

    /**
     * Set equipment
     *
     * @param \AppBundle\Entity\Equipment $equipment
     *
     * @return UserEquipment
     */
    public function setEquipment(\AppBundle\Entity\Equipment $equipment = null)
    {
        $this->equipment = $equipment;

        return $this;
    }
}
