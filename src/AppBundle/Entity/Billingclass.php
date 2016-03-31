<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Billingclass
 *
 * @ORM\Table(name="billingclass")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BillingclassRepository")
 */
class Billingclass
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
     * @ORM\Column(name="coeff", type="decimal", precision=10, scale=2)
     */
    private $coeff;


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
     * @return Billingclass
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
     * Set coeff
     *
     * @param string $coeff
     *
     * @return Billingclass
     */
    public function setCoeff($coeff)
    {
        $this->coeff = $coeff;

        return $this;
    }

    /**
     * Get coeff
     *
     * @return string
     */
    public function getCoeff()
    {
        return $this->coeff;
    }

    public function __toString()
    {
        return (string) $this->getName() . ' (' . $this->getCoeff() . ')';
    }
}
