<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AcmeAssert;

/**
 * ratingSettings.
 *
 * @ORM\Table(name="rating_settings")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ratingSettingsRepository")
 * @Assert\GroupSequence({"RatingSettings", "After"})
 * @AcmeAssert\RatingSettingsSumWeight(groups={"After"})
 */
class RatingSettings
{
    /**
     * @var string
     *
     * @ORM\Column(name="rating1label", type="string", length=255, options={"comment":"Название оценки №1, заполняется админом категорий"})
     */
    private $rating1label;

    /**
     * @var string
     *
     * @ORM\Column(name="rating2label", type="string", length=255, options={"comment":"Название оценки №2"})
     */
    private $rating2label;

    /**
     * @var string
     *
     * @ORM\Column(name="rating3label", type="string", length=255, options={"comment":"Название оценки №3"})
     */
    private $rating3label;

    /**
     * @var string
     *
     * @ORM\Column(name="rating4label", type="string", length=255, options={"comment":"Название оценки №4"})
     */
    private $rating4label;

    /**
     * @var int
     *
     * @ORM\Column(name="rating1weight", type="integer", nullable=true, options={"default"=25, "comment":"Вес оценки №1, используется для подсчета общей оценки товара"})
     * @Assert\Range(min=10, max=50)
     */
    private $rating1weight = 25;

    /**
     * @var int
     *
     * @ORM\Column(name="rating2weight", type="integer", nullable=true, options={"default"=25, "comment":"Вес оценки №2"})
     * @Assert\Range(min=10, max=50)
     */
    private $rating2weight = 25;

    /**
     * @var int
     *
     * @ORM\Column(name="rating3weight", type="integer", nullable=true, options={"default"=25, "comment":"Вес оценки №3"})
     * @Assert\Range(min=10, max=50)
     */
    private $rating3weight = 25;

    /**
     * @var int
     *
     * @ORM\Column(name="rating4weight", type="integer", nullable=true, options={"default"=25, "comment":"Вес оценки №4"})
     * @Assert\Range(min=10, max=50)
     */
    private $rating4weight = 25;

    /**
     * @var Category
     *
     * @ORM\Id
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Category", inversedBy="ratingSettings")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="slug")
     */
    private $category;

    /**
     * Set rating1.
     *
     * @param string $rating1
     *
     * @return RatingSettings
     */
    public function setRating1Label($rating1)
    {
        $this->rating1label = $rating1;

        return $this;
    }

    /**
     * Get rating1.
     *
     * @return string
     */
    public function getRating1Label()
    {
        return $this->rating1label;
    }

    /**
     * Set rating2.
     *
     * @param string $rating2
     *
     * @return RatingSettings
     */
    public function setRating2Label($rating2)
    {
        $this->rating2label = $rating2;

        return $this;
    }

    /**
     * Get rating2.
     *
     * @return string
     */
    public function getRating2Label()
    {
        return $this->rating2label;
    }

    /**
     * Set rating3.
     *
     * @param string $rating3
     *
     * @return RatingSettings
     */
    public function setRating3Label($rating3)
    {
        $this->rating3label = $rating3;

        return $this;
    }

    /**
     * Get rating3.
     *
     * @return string
     */
    public function getRating3Label()
    {
        return $this->rating3label;
    }

    /**
     * Set rating4.
     *
     * @param string $rating4
     *
     * @return RatingSettings
     */
    public function setRating4Label($rating4)
    {
        $this->rating4label = $rating4;

        return $this;
    }

    /**
     * Get rating4.
     *
     * @return string
     */
    public function getRating4Label()
    {
        return $this->rating4label;
    }

    /**
     * Set category.
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return RatingSettings
     */
    public function setCategory(\AppBundle\Entity\Category $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function __toString()
    {
        return $this->getCategory()->getName().' label';
    }

    /**
     * Set rating1weight.
     *
     * @param int $rating1weight
     *
     * @return RatingSettings
     */
    public function setRating1weight($rating1weight)
    {
        $this->rating1weight = $rating1weight;

        return $this;
    }

    /**
     * Get rating1weight.
     *
     * @return int
     */
    public function getRating1weight()
    {
        return $this->rating1weight;
    }

    /**
     * Set rating2weight.
     *
     * @param int $rating2weight
     *
     * @return RatingSettings
     */
    public function setRating2weight($rating2weight)
    {
        $this->rating2weight = $rating2weight;

        return $this;
    }

    /**
     * Get rating2weight.
     *
     * @return int
     */
    public function getRating2weight()
    {
        return $this->rating2weight;
    }

    /**
     * Set rating3weight.
     *
     * @param int $rating3weight
     *
     * @return RatingSettings
     */
    public function setRating3weight($rating3weight)
    {
        $this->rating3weight = $rating3weight;

        return $this;
    }

    /**
     * Get rating3weight.
     *
     * @return int
     */
    public function getRating3weight()
    {
        return $this->rating3weight;
    }

    /**
     * Set rating4weight.
     *
     * @param int $rating4weight
     *
     * @return RatingSettings
     */
    public function setRating4weight($rating4weight)
    {
        $this->rating4weight = $rating4weight;

        return $this;
    }

    /**
     * Get rating4weight.
     *
     * @return int
     */
    public function getRating4weight()
    {
        return $this->rating4weight;
    }
}
