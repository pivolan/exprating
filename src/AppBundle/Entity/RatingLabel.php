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
     * @ORM\Column(name="rating1", type="string", length=255)
     */
    private $rating1;

    /**
     * @var string
     *
     * @ORM\Column(name="rating2", type="string", length=255)
     */
    private $rating2;

    /**
     * @var string
     *
     * @ORM\Column(name="rating3", type="string", length=255)
     */
    private $rating3;

    /**
     * @var string
     *
     * @ORM\Column(name="rating4", type="string", length=255)
     */
    private $rating4;


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
    public function setRating1($rating1)
    {
        $this->rating1 = $rating1;

        return $this;
    }

    /**
     * Get rating1
     *
     * @return string
     */
    public function getRating1()
    {
        return $this->rating1;
    }

    /**
     * Set rating2
     *
     * @param string $rating2
     *
     * @return RatingLabel
     */
    public function setRating2($rating2)
    {
        $this->rating2 = $rating2;

        return $this;
    }

    /**
     * Get rating2
     *
     * @return string
     */
    public function getRating2()
    {
        return $this->rating2;
    }

    /**
     * Set rating3
     *
     * @param string $rating3
     *
     * @return RatingLabel
     */
    public function setRating3($rating3)
    {
        $this->rating3 = $rating3;

        return $this;
    }

    /**
     * Get rating3
     *
     * @return string
     */
    public function getRating3()
    {
        return $this->rating3;
    }

    /**
     * Set rating4
     *
     * @param string $rating4
     *
     * @return RatingLabel
     */
    public function setRating4($rating4)
    {
        $this->rating4 = $rating4;

        return $this;
    }

    /**
     * Get rating4
     *
     * @return string
     */
    public function getRating4()
    {
        return $this->rating4;
    }
}
