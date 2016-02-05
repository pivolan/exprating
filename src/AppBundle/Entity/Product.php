<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;

/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="preview_image", type="string", length=255, nullable=true)
     */
    private $previewImage;

    /**
     * @var string
     *
     * @ORM\Column(name="expert_opinion", type="text", nullable=true)
     */
    private $expertOpinion;

    /**
     * @var array|string[]
     *
     * @ORM\Column(name="advantages", type="json_array", nullable=true)
     */
    private $advantages;

    /**
     * @var array|string[]
     *
     * @ORM\Column(name="disadvantages", type="json_array", nullable=true)
     */
    private $disadvantages;

    /**
     * @var float
     *
     * @ORM\Column(name="min_price", type="decimal", precision=10, scale=2, nullable=true)
     */
    private $minPrice;

    /**
     * @var int
     *
     * @ORM\Column(name="rating", type="integer", nullable=true)
     */
    private $rating = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="rating1", type="integer", nullable=true)
     */
    private $rating1 = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="rating2", type="integer", nullable=true)
     */
    private $rating2 = 0;

    /**
     * @var int
     *
     * @ORM\Column(name="rating3", type="integer", nullable=true)
     */
    private $rating3 = 0;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_enabled", type="boolean", nullable=false, options={"default"=false})
     */
    private $isEnabled = false;

    /**
     * @var int
     *
     * @ORM\Column(name="rating4", type="integer", nullable=true)
     */
    private $rating4 = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="enabled_at", type="datetime", nullable=true)
     */
    private $enabledAt;

    /**
     * @var int
     *
     * @ORM\Column(name="visits_count", type="bigint", options={"default"=0})
     */
    private $visitsCount = 0;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="products")
     * @ORM\JoinColumn(name="expert_user_id", referencedColumnName="id")
     *
     */
    private $expertUser;

    /**
     * @var Image[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Image", mappedBy="product", cascade={"persist", "remove"})
     */
    private $images;

    /**
     * @var Comment[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="product", cascade={"persist", "remove"})
     */
    private $comments;

    /**
     * @var Feedback[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Feedback", mappedBy="product", cascade={"persist", "remove"})
     */
    private $feedbacks;

    /**
     * @var ProductShopPrice[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductShopPrice", mappedBy="product", cascade={"persist", "remove"})
     */
    private $productShopPrices;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category", inversedBy="products")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="slug")
     */
    private $category;

    /**
     * @var Manufacturer
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Manufacturer", inversedBy="products")
     * @ORM\JoinColumn(name="manufacturer_id", referencedColumnName="id")
     */
    private $manufacturer;

    /**
     * @var ProductCharacteristic[]
     * @ORM\OneToMany(targetEntity="Exprating\CharacteristicBundle\Entity\ProductCharacteristic", mappedBy="product", cascade={"persist", "remove"})
     */
    private $productCharacteristics;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
        $this->images = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->feedbacks = new ArrayCollection();
        $this->productShopPrices = new ArrayCollection();
        $this->productCharacteristics = new ArrayCollection();
    }

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
     * Set title
     *
     * @param string $title
     *
     * @return Product
     */
    public function setName($title)
    {
        $this->name = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return Product
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
     * Set previewImage
     *
     * @param string $previewImage
     *
     * @return Product
     */
    public function setPreviewImage($previewImage)
    {
        $this->previewImage = $previewImage;

        return $this;
    }

    /**
     * Get previewImage
     *
     * @return string
     */
    public function getPreviewImage()
    {
        return $this->previewImage;
    }

    /**
     * Set minPrice
     *
     * @param string $minPrice
     *
     * @return Product
     */
    public function setMinPrice($minPrice)
    {
        $this->minPrice = $minPrice;

        return $this;
    }

    /**
     * Get minPrice
     *
     * @return float
     */
    public function getMinPrice()
    {
        return $this->minPrice;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Product
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
     * @return int
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     *
     * @return Product
     */
    public function setRating($rating)
    {
        $this->rating = $rating;
        return $this;
    }

    /**
     * Set expertUser
     *
     * @param \AppBundle\Entity\User $expertUser
     *
     * @return Product
     */
    public function setExpertUser(\AppBundle\Entity\User $expertUser = null)
    {
        $this->expertUser = $expertUser;

        return $this;
    }

    /**
     * Get expertUser
     *
     * @return \AppBundle\Entity\User
     */
    public function getExpertUser()
    {
        return $this->expertUser;
    }

    /**
     * Add image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Product
     */
    public function addImage(\AppBundle\Entity\Image $image)
    {
        $this->images->add($image);

        return $this;
    }

    /**
     * Remove image
     *
     * @param \AppBundle\Entity\Image $image
     */
    public function removeImage(\AppBundle\Entity\Image $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    public function __toString()
    {
        return $this->slug;
    }

    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Product
     */
    public function addComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set category
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Product
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

    /**
     * Set advantages
     *
     * @param array $advantages
     *
     * @return Product
     */
    public function setAdvantages($advantages)
    {
        $this->advantages = $advantages;

        return $this;
    }

    /**
     * Get advantages
     *
     * @return array
     */
    public function getAdvantages()
    {
        return $this->advantages;
    }

    /**
     * Set disadvantages
     *
     * @param array $disadvantages
     *
     * @return Product
     */
    public function setDisadvantages($disadvantages)
    {
        $this->disadvantages = $disadvantages;

        return $this;
    }

    /**
     * Get disadvantages
     *
     * @return array
     */
    public function getDisadvantages()
    {
        return $this->disadvantages;
    }

    /**
     * Set manufacturer
     *
     * @param \AppBundle\Entity\Manufacturer $manufacturer
     *
     * @return Product
     */
    public function setManufacturer(\AppBundle\Entity\Manufacturer $manufacturer = null)
    {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    /**
     * Get manufacturer
     *
     * @return \AppBundle\Entity\Manufacturer
     */
    public function getManufacturer()
    {
        return $this->manufacturer;
    }

    /**
     * Add shopsPrice
     *
     * @param \AppBundle\Entity\ProductShopPrice $shopsPrice
     *
     * @return Product
     */
    public function addShopsPrice(\AppBundle\Entity\ProductShopPrice $shopsPrice)
    {
        $this->productShopPrices[] = $shopsPrice;

        return $this;
    }

    /**
     * Remove shopsPrice
     *
     * @param \AppBundle\Entity\ProductShopPrice $shopsPrice
     */
    public function removeShopsPrice(\AppBundle\Entity\ProductShopPrice $shopsPrice)
    {
        $this->productShopPrices->removeElement($shopsPrice);
    }

    /**
     * Get shopsPrice
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductShopPrices()
    {
        return $this->productShopPrices;
    }

    /**
     * Add feedback
     *
     * @param \AppBundle\Entity\Feedback $feedback
     *
     * @return Product
     */
    public function addFeedback(\AppBundle\Entity\Feedback $feedback)
    {
        $this->feedbacks[] = $feedback;

        return $this;
    }

    /**
     * Remove feedback
     *
     * @param \AppBundle\Entity\Feedback $feedback
     */
    public function removeFeedback(\AppBundle\Entity\Feedback $feedback)
    {
        $this->feedbacks->removeElement($feedback);
    }

    /**
     * Get feedbacks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFeedbacks()
    {
        return $this->feedbacks;
    }

    /**
     * Add productShopPrice
     *
     * @param \AppBundle\Entity\ProductShopPrice $productShopPrice
     *
     * @return Product
     */
    public function addProductShopPrice(\AppBundle\Entity\ProductShopPrice $productShopPrice)
    {
        $this->productShopPrices[] = $productShopPrice;

        return $this;
    }

    /**
     * Remove productShopPrice
     *
     * @param \AppBundle\Entity\ProductShopPrice $productShopPrice
     */
    public function removeProductShopPrice(\AppBundle\Entity\ProductShopPrice $productShopPrice)
    {
        $this->productShopPrices->removeElement($productShopPrice);
    }

    /**
     * Set expertOpinion
     *
     * @param string $expertOpinion
     *
     * @return Product
     */
    public function setExpertOpinion($expertOpinion)
    {
        $this->expertOpinion = $expertOpinion;

        return $this;
    }

    /**
     * Get expertOpinion
     *
     * @return string
     */
    public function getExpertOpinion()
    {
        return $this->expertOpinion;
    }

    /**
     * Add productCharacteristic
     *
     * @param \Exprating\CharacteristicBundle\Entity\ProductCharacteristic $productCharacteristic
     *
     * @return Product
     */
    public function addProductCharacteristic(\Exprating\CharacteristicBundle\Entity\ProductCharacteristic $productCharacteristic)
    {
        $this->productCharacteristics[] = $productCharacteristic;

        return $this;
    }

    /**
     * Remove productCharacteristic
     *
     * @param \Exprating\CharacteristicBundle\Entity\ProductCharacteristic $productCharacteristic
     */
    public function removeProductCharacteristic(\Exprating\CharacteristicBundle\Entity\ProductCharacteristic $productCharacteristic)
    {
        $this->productCharacteristics->removeElement($productCharacteristic);
    }

    /**
     * Get productCharacteristics
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductCharacteristics()
    {
        return $this->productCharacteristics;
    }

    /**
     * Set rating1
     *
     * @param integer $rating1
     *
     * @return Product
     */
    public function setRating1($rating1)
    {
        $this->rating1 = $rating1;

        return $this;
    }

    /**
     * Get rating1
     *
     * @return integer
     */
    public function getRating1()
    {
        return $this->rating1;
    }

    /**
     * Set rating2
     *
     * @param integer $rating2
     *
     * @return Product
     */
    public function setRating2($rating2)
    {
        $this->rating2 = $rating2;

        return $this;
    }

    /**
     * Get rating2
     *
     * @return integer
     */
    public function getRating2()
    {
        return $this->rating2;
    }

    /**
     * Set rating3
     *
     * @param integer $rating3
     *
     * @return Product
     */
    public function setRating3($rating3)
    {
        $this->rating3 = $rating3;

        return $this;
    }

    /**
     * Get rating3
     *
     * @return integer
     */
    public function getRating3()
    {
        return $this->rating3;
    }

    /**
     * Set rating4
     *
     * @param integer $rating4
     *
     * @return Product
     */
    public function setRating4($rating4)
    {
        $this->rating4 = $rating4;

        return $this;
    }

    /**
     * Get rating4
     *
     * @return integer
     */
    public function getRating4()
    {
        return $this->rating4;
    }

    /**
     * Set isEnabled
     *
     * @param string $isEnabled
     *
     * @return Product
     */
    public function setIsEnabled($isEnabled)
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * Get isEnabled
     *
     * @return string
     */
    public function getIsEnabled()
    {
        return $this->isEnabled;
    }

    /**
     * Set enabledAt
     *
     * @param \DateTime $enabledAt
     *
     * @return Product
     */
    public function setEnabledAt($enabledAt)
    {
        $this->enabledAt = $enabledAt;

        return $this;
    }

    /**
     * Get enabledAt
     *
     * @return \DateTime
     */
    public function getEnabledAt()
    {
        return $this->enabledAt;
    }

    /**
     * Set visitsCount
     *
     * @param integer $visitsCount
     *
     * @return Product
     */
    public function setVisitsCount($visitsCount)
    {
        $this->visitsCount = $visitsCount;

        return $this;
    }

    /**
     * Get visitsCount
     *
     * @return integer
     */
    public function getVisitsCount()
    {
        return $this->visitsCount;
    }
}
