<?php

namespace AppBundle\Entity\Course;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Coursepackage
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Coursepackage
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
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     * @Assert\NotBlank()
     * @ORM\Column(name="description", type="string", length=500)
     */
    private $description;

    /**
     * @Assert\NotBlank(message="oktothek.backend_coursetype_price_notblank")
     * @Assert\GreaterThanOrEqual(value= 0, message="oktothek.backend_coursetype_price_greaterthan")
     * precision = number of digits in total, scale = number of .digits
     * @ORM\Column(name="price", type="decimal", scale=2, precision=6, options={"default" = 0.0})
     */
    private $price;

    /**
     * @Assert\NotBlank(message="oktothek.backend_coursetype_price_reduced_notblank")
     * @Assert\GreaterThanOrEqual(value=0, message="oktothek.backend_coursetype_price_reduced_greaterthan")
     * precision = number of digits in total, scale = number of .digits
     * @ORM\Column(name="price_reduced", type="decimal", scale=2, precision=6, options={"default" = 0.0})
     */
    private $price_reduced;

    /**
     * @ORM\ManyToMany(targetEntity="Coursetype", inversedBy="coursepackages")
     * @ORM\JoinTable(name="packages_coursetypes")
     */
    private $coursetypes;

    /**
     * @ORM\Column(name="is_active", type="boolean", options={"default"=false})
     */
    private $is_active;

    public function __construct() {
        $this->courses = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
     * @return Coursepackage
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
     * @return Coursepackage
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
     * Set is_active
     *
     * @param boolean $isActive
     * @return Coursepackage
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get is_active
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->is_active;
    }

    /**
     * Add coursetypes
     *
     * @param \AppBundle\Entity\Course\Course $coursetypes
     * @return Coursepackage
     */
    public function addCoursetype($coursetypes)
    {
        $this->coursetypes[] = $coursetypes;

        return $this;
    }

    /**
     * Remove coursetypes
     *
     * @param \AppBundle\Entity\Course\Course $coursetypes
     */
    public function removeCoursetype($coursetypes)
    {
        $this->coursetypes->removeElement($coursetypes);
    }

    /**
     * Get coursetypes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoursetypes()
    {
        return $this->coursetypes;
    }
}
