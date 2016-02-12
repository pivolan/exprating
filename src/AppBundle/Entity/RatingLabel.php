<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RatingLabel
 *
 * @ORM\Table(name="rating_label")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\RatingLabelRepository")
 */
class RatingLabel
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
     * @ORM\Column(name="rating1label", type="string", length=255)
     */
    private $rating1label;

    /**
     * @var string
     *
     * @ORM\Column(name="rating2label", type="string", length=255)
     */
    private $rating2label;

    /**
     * @var string
     *
     * @ORM\Column(name="rating3label", type="string", length=255)
     */
    private $rating3label;

    /**
     * @var string
     *
     * @ORM\Column(name="rating4label", type="string", length=255)
     */
    private $rating4label;

    /**
     * @var Category
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Category", inversedBy="ratingLabel")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="slug")
     */
    private $category;

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
     * Set rating1
     *
     * @param string $rating1
     *
     * @return RatingLabel
     */
    public function setRating1Label($rating1)
    {
        $this->rating1label = $rating1;

        return $this;
    }

    /**
     * Get rating1
     *
     * @return string
     */
    public function getRating1Label()
    {
        return $this->rating1label;
    }

    /**
     * Set rating2
     *
     * @param string $rating2
     *
     * @return RatingLabel
     */
    public function setRating2Label($rating2)
    {
        $this->rating2label = $rating2;

        return $this;
    }

    /**
     * Get rating2
     *
     * @return string
     */
    public function getRating2Label()
    {
        return $this->rating2label;
    }

    /**
     * Set rating3
     *
     * @param string $rating3
     *
     * @return RatingLabel
     */
    public function setRating3Label($rating3)
    {
        $this->rating3label = $rating3;

        return $this;
    }

    /**
     * Get rating3
     *
     * @return string
     */
    public function getRating3Label()
    {
        return $this->rating3label;
    }

    /**
     * Set rating4
     *
     * @param string $rating4
     *
     * @return RatingLabel
     */
    public function setRating4Label($rating4)
    {
        $this->rating4label = $rating4;

        return $this;
    }

    /**
     * Get rating4
     *
     * @return string
     */
    public function getRating4Label()
    {
        return $this->rating4label;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return RatingLabel
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function __toString()
    {
        return $this->getCategory()->getName() . ' label';
    }
}
