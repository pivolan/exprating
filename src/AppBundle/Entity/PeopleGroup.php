<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PeopleGroup
 *
 * @ORM\Table(name="people_group")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PeopleGroupRepository")
 */
class PeopleGroup
{
    const SLUG_WOMAN = 'woman';
    const SLUG_MAN = 'man';
    const SLUG_CHILD = 'child';
    const SLUG_ALL = 'all';

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     * @ORM\Id
     */
    private $slug;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return PeopleGroup
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
     * Set slug
     *
     * @param string $slug
     *
     * @return PeopleGroup
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
}

