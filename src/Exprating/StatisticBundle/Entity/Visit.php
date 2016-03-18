<?php

namespace Exprating\StatisticBundle\Entity;

use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * Visit
 *
 * @ORM\Table(name="visit")
 * @ORM\Entity(repositoryClass="Exprating\StatisticBundle\Repository\VisitRepository")
 */
class Visit
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
     * @ORM\Column(name="ip", type="string", length=255, nullable=true,
     *     options={"comment": "IP адрес посетителя"})
     */
    private $ip;

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="string", length=255, nullable=true,
     *      options={"comment":"user-agent информация о бразуере посетителя"})
     */
    private $userAgent;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, nullable=true,
     *     options={"comment":"Полный адрес страницы включая схему и домен"})
     */
    private $url;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", options={"comment":"Дата просмотра страницы"})
     */
    private $createdAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $user;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $product;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="expert_id", referencedColumnName="id", onDelete="SET NULL")
     */

    private $expert;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="curator_first_level_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $curatorFirstLevel;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(name="curator_second_level_id", referencedColumnName="id", onDelete="SET NULL")
     */
    private $curatorSecondLevel;


    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
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
     * Set ip
     *
     * @param string $ip
     *
     * @return Visit
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

        return $this;
    }

    /**
     * Get ip
     *
     * @return string
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     *
     * @return Visit
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Visit
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
     * Set url
     *
     * @param string $url
     *
     * @return Visit
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set user
     *
     * @param string $user
     *
     * @return Visit
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set product
     *
     * @param string $product
     *
     * @return Visit
     */
    public function setProduct($product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return string
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set expert
     *
     * @param string $expert
     *
     * @return Visit
     */
    public function setExpert($expert)
    {
        $this->expert = $expert;

        return $this;
    }

    /**
     * Get expert
     *
     * @return string
     */
    public function getExpert()
    {
        return $this->expert;
    }

    /**
     * Set curatorFirstLevel
     *
     * @param string $curatorFirstLevel
     *
     * @return Visit
     */
    public function setCuratorFirstLevel($curatorFirstLevel)
    {
        $this->curatorFirstLevel = $curatorFirstLevel;

        return $this;
    }

    /**
     * Get curatorFirstLevel
     *
     * @return string
     */
    public function getCuratorFirstLevel()
    {
        return $this->curatorFirstLevel;
    }

    /**
     * Set curatorSecondLevel
     *
     * @param string $curatorSecondLevel
     *
     * @return Visit
     */
    public function setCuratorSecondLevel($curatorSecondLevel)
    {
        $this->curatorSecondLevel = $curatorSecondLevel;

        return $this;
    }

    /**
     * Get curatorSecondLevel
     *
     * @return string
     */
    public function getCuratorSecondLevel()
    {
        return $this->curatorSecondLevel;
    }
}
