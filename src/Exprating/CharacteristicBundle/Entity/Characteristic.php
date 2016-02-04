<?php

namespace Exprating\CharacteristicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Exprating\CharacteristicBundle\Exceptions\CharacteristicTypeException;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Characteristic
 *
 * @ORM\Table(name="characteristic")
 * @ORM\Entity(repositoryClass="Exprating\CharacteristicBundle\Repository\CharacteristicRepository")
 */
class Characteristic
{
    const TYPE_STRING = 'string';
    const TYPE_INT = 'integer';
    const TYPE_DECIMAL = 'decimal';

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="slug", type="string", length=255)
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255)
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="type_object", type="string", length=255, nullable=false, options={"default"="string"})
     * @Assert\Choice(choices = {"string", "integer", "decimal"}, message = "Choose a valid type.")
     */
    private $type;


    /**
     * Set name
     *
     * @param string $name
     *
     * @return Characteristic
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
     * @return Characteristic
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
     * Set label
     *
     * @param string $label
     *
     * @return Characteristic
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Characteristic
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
