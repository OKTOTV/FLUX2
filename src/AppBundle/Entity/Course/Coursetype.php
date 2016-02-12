<?php

namespace AppBundle\Entity\Course;

use Doctrine\ORM\Mapping as ORM;

/**
 * Coursetype
 *
 * @ORM\Table()
 * @ORM\Entity
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
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(name="description", type="string", length=400)
     */
    private $description;

    /**
     * scale = number of digits in total, precision = number of .digits
     * @ORM\Column(name="price", type="decimal", scale=6, precision=2)
     */
    private $price;

    /**
     * scale = number of digits in total, precision = number of .digits
     * @ORM\Column(name="price_reduced", type="decimal", scale=6, precision=2)
     */
    private $price_reduced;

    /**
     * @ORM\Column(name="highlight", type="boolean", options={"default"=false})
     */
    private $highlight;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle:Asset")
     * @ORM\JoinColumn(name="image", referencedColumnName="id")
     */
    private $image;

    /**
     * downloadable documents for this course
     *
     * @ORM\ManyToMany(targetEntity="AppBundle:Asset")
     * @ORM\JoinTable(name="coursetype_asset",
     *      joinColumns={@ORM\JoinColumn(name="coursetype_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="asset_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $assets;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity="Course", mappedBy="coursetype")
     */
    private $courses;

    public function __construct() {
        $this->features = new ArrayCollection();
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
    public function addCourse(\AppBundle\Entity\Course $courses)
    {
        $this->courses[] = $courses;

        return $this;
    }

    /**
     * Remove courses
     *
     * @param \AppBundle\Entity\Course $courses
     */
    public function removeCourse(\AppBundle\Entity\Course $courses)
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
    public function addAsset(\AppBundle\Entity\Course\AppBundle:Asset $assets)
    {
        $this->assets[] = $assets;

        return $this;
    }

    /**
     * Remove assets
     *
     * @param \AppBundle\Entity\Course\AppBundle:Asset $assets
     */
    public function removeAsset(\AppBundle\Entity\Course\AppBundle:Asset $assets)
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
}
