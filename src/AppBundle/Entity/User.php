<?php

// src/AppBundle/Entity/User.php


namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Gedmo\Tree(type="nested")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @ORM\Table(name="fos_user")
 * @UniqueEntity(fields={"username"}, message="Пользователь с таким Логином уже зарегестрирован")
 */
class User extends BaseUser
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_EXPERT = 'ROLE_EXPERT';
    const ROLE_EXPERT_CATEGORY_ADMIN = 'ROLE_EXPERT_CATEGORY_ADMIN';
    const ROLE_EXPERT_CURATOR = 'ROLE_EXPERT_CURATOR';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_MODERATOR = 'ROLE_MODERATOR';

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Gedmo\TreeLeft
     * @ORM\Column(name="lft", type="integer", options={"comment":"Поле формируется автоматически для дерева."})
     */
    private $lft;

    /**
     * @Gedmo\TreeLevel
     * @ORM\Column(name="lvl", type="integer",
     *     options={"comment":"Уровень вложенности внутри дерева. Формируется автоматически."})
     */
    private $lvl;

    /**
     * @Gedmo\TreeRight
     * @ORM\Column(name="rgt", type="integer", options={"comment":"Полу формируется автоматически для дерева."})
     */
    private $rgt;

    /**
     * @Gedmo\TreeRoot
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="tree_root", referencedColumnName="id", onDelete="SET NULL")
     */
    private $root;

    /**
     * @var User
     *
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="experts")
     * @ORM\JoinColumn(name="curator_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $curator;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="curator")
     */
    private $experts;

    /**
     * @var string
     *
     * @ORM\Column(name="full_name", type="string", unique=false, nullable=true,
     *     options={"comment":"Полное имя Эксперта"})
     *
     * @Assert\NotBlank
     */
    private $fullName;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="birthday", type="date", nullable=true,
     *     options={"comment":"Дата рождения эксперта"})
     */
    private $birthday;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", nullable=true,
     *     options={"comment":"город. Отображается на странице эксперта"})
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="caption", type="string", nullable=true, unique=false,
     *     options={"comment":"Подпись эксперта, показывается в списке экспертов"})
     */
    private $caption;

    /**
     * @var string
     *
     * @ORM\Column(name="skype", type="string", nullable=true, unique=false,
     * options={"comment": "Skype аккаунт пользователя"})
     */
    private $skype;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", nullable=true, unique=false,
     * options={"comment": "Номер телефона пользователя"})
     */
    private $phone;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_activated", type="boolean", nullable=false, unique=false,
     * options={"default": true, "comment": "Эксперт активирован: прошел по инвайту, заполнил форму."})
     */
    private $isActivated = false;

    /**
     * @var bool
     *
     * @ORM\Column(name="can_controlled_pre_curator",
     *     options={"comment":"Может ли вышестоящий куратор управлять этим пользователем", "default": false})
     */
    private $canControlledPreCurator = false;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar_image", type="string", unique=true, nullable=true,
     *     options={"comment":"Аватар картинка эксперта"})
     */
    private $avatarImage;

    /**
     * @var Invite
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Invite", mappedBy="expert")
     */
    private $invite;

    /**
     * @var Invite[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Invite", mappedBy="curator")
     */
    private $invites;

    /**
     * @var Product[]
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="expertUser", cascade={"persist", "detach"})
     */
    private $products;

    /**
     * @var Category[]
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category", inversedBy="experts")
     * @ORM\JoinTable(name="user_category", joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id",
     *     onDelete="CASCADE")},
     *            inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="slug",
     * onDelete="CASCADE")})
     */
    private $categories;

    /**
     * @var Category[]
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Category", inversedBy="admins")
     * @ORM\JoinTable(name="user_admin_category", joinColumns={
     *     @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")},
     *                 inverseJoinColumns={@ORM\JoinColumn(name="admin_category_id", referencedColumnName="slug",
     *      onDelete="CASCADE")})
     */
    private $adminCategories;

    /**
     * @var Comment[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment", mappedBy="user")
     */
    private $comments;

    /**
     * @var CuratorDecision[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\CuratorDecision", mappedBy="curator")
     */
    private $myDecisions;

    /**
     * @var Notification[]
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Notification", mappedBy="user")
     */
    private $notifications;


    public function __construct()
    {
        parent::__construct();

        $this->products = new ArrayCollection();
        $this->experts = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->adminCategories = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->myDecisions = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->createdAt = new \DateTime();
    }

    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }

    /**
     * Add product.
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return User
     */
    public function addProduct(\AppBundle\Entity\Product $product)
    {
        $this->products[] = $product;

        return $this;
    }

    /**
     * Remove product.
     *
     * @param \AppBundle\Entity\Product $product
     */
    public function removeProduct(\AppBundle\Entity\Product $product)
    {
        $this->products->removeElement($product);
    }

    /**
     * Get products.
     *
     * @return Product[]|ArrayCollection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     *
     * @return $this
     */
    public function setFullName($fullName)
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return string
     */
    public function getAvatarImage()
    {
        return $this->avatarImage;
    }

    /**
     * @param string $avatarImage
     *
     * @return $this
     */
    public function setAvatarImage($avatarImage)
    {
        $this->avatarImage = $avatarImage;

        return $this;
    }

    /**
     * Set caption.
     *
     * @param string $caption
     *
     * @return User
     */
    public function setCaption($caption)
    {
        $this->caption = $caption;

        return $this;
    }

    /**
     * Get caption.
     *
     * @return string
     */
    public function getCaption()
    {
        return $this->caption;
    }

    /**
     * Set birthday.
     *
     * @param \DateTime $birthday
     *
     * @return User
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * Get birthday.
     *
     * @return \DateTime
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    public function getAge()
    {
        if ($this->getBirthday()) {
            $date = new \DateTime();
            $diff = $date->diff($this->getBirthday());

            return $diff->y;
        }

        return null;
    }

    /**
     * Set city.
     *
     * @param string $city
     *
     * @return User
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city.
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Add category.
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return User
     */
    public function addCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category.
     *
     * @param \AppBundle\Entity\Category $category
     */
    public function removeCategory(\AppBundle\Entity\Category $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set curator.
     *
     * @param \AppBundle\Entity\User $curator
     *
     * @return User
     */
    public function setCurator(\AppBundle\Entity\User $curator = null)
    {
        $this->curator = $curator;

        return $this;
    }

    /**
     * Get curator.
     *
     * @return \AppBundle\Entity\User
     */
    public function getCurator()
    {
        return $this->curator;
    }

    /**
     * Add expert.
     *
     * @param \AppBundle\Entity\User $expert
     *
     * @return User
     */
    public function addExpert(\AppBundle\Entity\User $expert)
    {
        $this->experts[] = $expert;

        return $this;
    }

    /**
     * Remove expert.
     *
     * @param \AppBundle\Entity\User $expert
     */
    public function removeExpert(\AppBundle\Entity\User $expert)
    {
        $this->experts->removeElement($expert);
    }

    /**
     * Get experts.
     *
     * @return \Doctrine\Common\Collections\Collection|User[]
     */
    public function getExperts()
    {
        return $this->experts;
    }

    /**
     * Add adminCategory.
     *
     * @param \AppBundle\Entity\Category $adminCategory
     *
     * @return User
     */
    public function addAdminCategory(\AppBundle\Entity\Category $adminCategory)
    {
        $this->adminCategories[] = $adminCategory;

        return $this;
    }

    /**
     * Remove adminCategory.
     *
     * @param \AppBundle\Entity\Category $adminCategory
     */
    public function removeAdminCategory(\AppBundle\Entity\Category $adminCategory)
    {
        $this->adminCategories->removeElement($adminCategory);
    }

    /**
     * Get adminCategories.
     *
     * @return \Doctrine\Common\Collections\Collection|Category[]
     */
    public function getAdminCategories()
    {
        return $this->adminCategories;
    }

    /**
     * Set canControlledPreCurator.
     *
     * @param string $canControlledPreCurator
     *
     * @return User
     */
    public function setCanControlledPreCurator($canControlledPreCurator)
    {
        $this->canControlledPreCurator = $canControlledPreCurator;

        return $this;
    }

    /**
     * Get canControlledPreCurator.
     *
     * @return string
     */
    public function getCanControlledPreCurator()
    {
        return $this->canControlledPreCurator;
    }

    /**
     * @return string
     */
    public function getSkype()
    {
        return $this->skype;
    }

    /**
     * @param string $skype
     *
     * @return $this
     */
    public function setSkype($skype)
    {
        $this->skype = $skype;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsActivated()
    {
        return $this->isActivated;
    }

    /**
     * @param boolean $isActivated
     *
     * @return $this
     */
    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;

        return $this;
    }

    /**
     * @return Invite
     */
    public function getInvite()
    {
        return $this->invite;
    }

    /**
     * @param Invite $invite
     *
     * @return $this
     */
    public function setInvite(Invite $invite)
    {
        $this->invite = $invite;

        return $this;
    }

    /**
     * Add invite
     *
     * @param \AppBundle\Entity\Invite $invite
     *
     * @return User
     */
    public function addInvite(\AppBundle\Entity\Invite $invite)
    {
        $this->invites[] = $invite;

        return $this;
    }

    /**
     * Remove invite
     *
     * @param \AppBundle\Entity\Invite $invite
     */
    public function removeInvite(\AppBundle\Entity\Invite $invite)
    {
        $this->invites->removeElement($invite);
    }

    /**
     * Get invites
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInvites()
    {
        return $this->invites;
    }

    /**
     * Set lft
     *
     * @param integer $lft
     *
     * @return User
     */
    public function setLft($lft)
    {
        $this->lft = $lft;

        return $this;
    }

    /**
     * Get lft
     *
     * @return integer
     */
    public function getLft()
    {
        return $this->lft;
    }

    /**
     * Set lvl
     *
     * @param integer $lvl
     *
     * @return User
     */
    public function setLvl($lvl)
    {
        $this->lvl = $lvl;

        return $this;
    }

    /**
     * Get lvl
     *
     * @return integer
     */
    public function getLvl()
    {
        return $this->lvl;
    }

    /**
     * Set rgt
     *
     * @param integer $rgt
     *
     * @return User
     */
    public function setRgt($rgt)
    {
        $this->rgt = $rgt;

        return $this;
    }

    /**
     * Get rgt
     *
     * @return integer
     */
    public function getRgt()
    {
        return $this->rgt;
    }

    /**
     * Set root
     *
     * @param \AppBundle\Entity\User $root
     *
     * @return User
     */
    public function setRoot(\AppBundle\Entity\User $root = null)
    {
        $this->root = $root;

        return $this;
    }

    /**
     * Get root
     *
     * @return \AppBundle\Entity\User
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return User
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
     * Add myDecision
     *
     * @param \AppBundle\Entity\CuratorDecision $myDecision
     *
     * @return User
     */
    public function addMyDecision(\AppBundle\Entity\CuratorDecision $myDecision)
    {
        $this->myDecisions[] = $myDecision;

        return $this;
    }

    /**
     * Remove myDecision
     *
     * @param \AppBundle\Entity\CuratorDecision $myDecision
     */
    public function removeMyDecision(\AppBundle\Entity\CuratorDecision $myDecision)
    {
        $this->myDecisions->removeElement($myDecision);
    }

    /**
     * Get myDecisions
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMyDecisions()
    {
        return $this->myDecisions;
    }

    /**
     * Add notification
     *
     * @param \AppBundle\Entity\Notification $notification
     *
     * @return User
     */
    public function addNotification(\AppBundle\Entity\Notification $notification)
    {
        $this->notifications[] = $notification;

        return $this;
    }

    /**
     * Remove notification
     *
     * @param \AppBundle\Entity\Notification $notification
     */
    public function removeNotification(\AppBundle\Entity\Notification $notification)
    {
        $this->notifications->removeElement($notification);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getNotifications()
    {
        return $this->notifications;
    }
}
