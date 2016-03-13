<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PeopleGroup.
 *
 * @ORM\Table(name="people_group")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\PeopleGroupRepository")
 */
class PeopleGroup
{
    const SLUG_WOMAN = 'dlya-zhenshchin';
    const SLUG_MAN = 'dlya-muzhchin';
    const SLUG_CHILD = 'dlya-detey';
    const SLUG_ALL = 'dlya-vseh';

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
     * @Assert\Choice(choices = {PeopleGroup::SLUG_WOMAN,
     *     PeopleGroup::SLUG_MAN,
     *     PeopleGroup::SLUG_CHILD,
     *     PeopleGroup::SLUG_ALL
     *     }, message = "Выберите верный фильтр")
     */
    private $slug;

    /**
     * Set name.
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
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug.
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
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
