<?php

namespace AppBundle\Entity\Course;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Coursetype
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\CoursetypeRepository")
 */
class Coursetype
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
     * @Assert\NotBlank(message="oktothek.backend_coursetype_title_notblank")
     * @Assert\Length(max=255, maxMessage="oktothek.backend_coursetype_title_maxLength")
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @var string
     * @Assert\Length(max=230, maxMessage="oktothek.backend_coursetype_teaser_maxLength")
     * @ORM\Column(name="teaser", type="string", length=230)
     */
    private $teaser;

    /**
     * @Assert\NotBlank(message="oktothek.backend_coursetype_description_notblank")
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @Assert\NotBlank(message="oktothek.backend_coursetype_price_notblank")
     * @Assert\GreaterThanOrEqual(value= 0, message="oktothek.backend_coursetype_price_greaterthan")
     * precision = number of digits in total, scale = number of .digits
     * @ORM\Column(name="price", type="decimal", scale=2, precision=6)
     */
    private $price;

    /**
     * @Assert\NotBlank(message="oktothek.backend_coursetype_price_reduced_notblank")
     * @Assert\GreaterThanOrEqual(value=0, message="oktothek.backend_coursetype_price_reduced_greaterthan")
     * precision = number of digits in total, scale = number of .digits
     * @ORM\Column(name="price_reduced", type="decimal", scale=2, precision=6)
     */
    private $price_reduced;

    /**
     * @ORM\Column(name="highlight", type="boolean", options={"default"=false})
     */
    private $highlight;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Asset",fetch="EAGER", cascade={"remove"})
     * @ORM\JoinColumn(name="image", referencedColumnName="id")
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="Coursepackage", mappedBy="coursetypes")
     */
    private $coursepackages;

    /**
     * downloadable documents for this course
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Asset", cascade={"remove"})
     * @ORM\JoinTable(name="coursetype_asset",
     *      joinColumns={@ORM\JoinColumn(name="coursetype_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="asset_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $assets;

    /**
     * @var string
     * @Gedmo\Slug(fields={"title"}, updatable=false, separator="_")
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Course", mappedBy="coursetype", cascade={"remove"})
     */
    private $courses;

    /**
     * @ORM\Column(name="is_active", type="boolean", options={"default"=false})
     */
    private $is_active;

    public function __construct() {
        $this->is_active = false;
        $this->features = new \Doctrine\Common\Collections\ArrayCollection();
        $this->coursepackages = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __toString()
    {
        return $this->title;
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
     * Set title
     *
     * @param string $title
     * @return Courstype
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Courstype
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

    /**
     * Set files
     *
     * @param \stdClass $files
     * @return Courstype
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * Get files
     *
     * @return \stdClass
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Coursetype
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
     * Set price
     *
     * @param string $price
     * @return Coursetype
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
     * @return Coursetype
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
     * Set highlight
     *
     * @param boolean $highlight
     * @return Coursetype
     */
    public function setHighlight($highlight)
    {
        $this->highlight = $highlight;

        return $this;
    }

    /**
     * Get highlight
     *
     * @return boolean
     */
    public function getHighlight()
    {
        return $this->highlight;
    }

    /**
     * Add courses
     *
     * @param \AppBundle\Entity\Course $courses
     * @return Coursetype
     */
    public function addCourse($courses)
    {
        $this->courses[] = $courses;

        return $this;
    }

    /**
     * Remove courses
     *
     * @param \AppBundle\Entity\Course $courses
     */
    public function removeCourse($courses)
    {
        $this->courses->removeElement($courses);
    }

    /**
     * Get courses
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCourses()
    {
        return $this->courses;
    }

    /**
     * Add assets
     *
     * @param \AppBundle\Entity\Course\AppBundle:Asset $assets
     * @return Coursetype
     */
    public function addAsset($assets)
    {
        $this->assets[] = $assets;

        return $this;
    }

    /**
     * Remove assets
     *
     * @param \AppBundle\Entity\Course\AppBundle:Asset $assets
     */
    public function removeAsset($assets)
    {
        $this->assets->removeElement($assets);
    }

    /**
     * Get assets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAssets()
    {
        return $this->assets;
    }

    /**
     * Set assets
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function setAssets($assets)
    {
        $this->assets = $assets;
        return $this;
    }

    /**
     * Set is_active
     *
     * @param boolean $isActive
     * @return Coursetype
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
     * @Assert\IsTrue(message = "oktothek.backend_coursetype_price_cheaper_than_reduced")
     */
    public function isReducedCheapterThenFullprice()
    {
        return $this->price >= $this->price_reduced;
    }

    /**
     * Add coursepackages
     *
     * @param \AppBundle\Entity\Course\Coursepackage $coursepackages
     * @return Coursetype
     */
    public function addCoursepackage(\AppBundle\Entity\Course\Coursepackage $coursepackages)
    {
        $this->coursepackages[] = $coursepackages;

        return $this;
    }

    /**
     * Remove coursepackages
     *
     * @param \AppBundle\Entity\Course\Coursepackage $coursepackages
     */
    public function removeCoursepackage(\AppBundle\Entity\Course\Coursepackage $coursepackages)
    {
        $this->coursepackages->removeElement($coursepackages);
    }

    /**
     * Get coursepackages
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCoursepackages()
    {
        return $this->coursepackages;
    }

    /**
     * Set teaser
     *
     * @param string $teaser
     * @return Post
     */
    public function setTeaser($teaser)
    {
        $this->teaser = $teaser;

        return $this;
    }

    /**
     * Get teaser
     *
     * @return string
     */
    public function getTeaser()
    {
        return $this->teaser;
    }
}
