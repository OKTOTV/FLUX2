<?php

namespace AppBundle\Entity\Course;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Coursepackage
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\CoursepackageRepository")
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

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Asset",fetch="EAGER", cascade={"remove"})
     * @ORM\JoinColumn(name="image", referencedColumnName="id")
     */
    private $image;

    /**
     * @var string
     * @Gedmo\Slug(fields={"name"}, updatable=false, separator="_")
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    public function __construct() {
        $this->coursetypes = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Set price
     *
     * @param string $price
     * @return Coursepackage
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set price_reduced
     *
     * @param string $priceReduced
     * @return Coursepackage
     */
    public function setPriceReduced($priceReduced)
    {
        $this->price_reduced = $priceReduced;

        return $this;
    }

    /**
     * Get price_reduced
     *
     * @return string
     */
    public function getPriceReduced()
    {
        return $this->price_reduced;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Coursepackage
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Set image
     *
     * @param \stdClass $image
     * @return Courstype
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \stdClass
     */
    public function getImage()
    {
        return $this->image;
    }
}
