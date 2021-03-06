<?php

namespace Exprating\CharacteristicBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Characteristic.
 *
 * @ORM\Table(name="characteristic")
 * @ORM\Entity(repositoryClass="Exprating\CharacteristicBundle\Repository\CharacteristicRepository")
 * @UniqueEntity(fields={"slug"})
 * @UniqueEntity(fields={"name"})
 */
class Characteristic
{
    const TYPE_STRING = 'string';
    const TYPE_INT = 'integer';
    const TYPE_DECIMAL = 'decimal';

    const SCALE_WATT = 'Вт';
    const SCALE_CM = 'См';
    const SCALE_METERS = 'М';
    const SCALE_KG = 'Кг';

    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(name="slug", type="string", length=255, options={"comment":"Уникальный идентфикатор характерстики"})
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, unique=true, options={"comment":"Название характеристики"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="label", type="string", length=255,
     *     options={"comment":"Метка характеристики, может быть не уникальна. Для использования на странице товара."})
     */
    private $label;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255, nullable=false,
     *     options={"default"="string", "comment":"Тип значения, используется для поиска по характеристикам."})
     * @Assert\Choice(choices = {"string", "integer", "decimal"}, message = "Choose a valid type.")
     */
    private $type = self::TYPE_STRING;

    /**
     * @var string
     *
     * @ORM\Column(name="scale", type="string", length=255, nullable=true, options={"comment":"Единица измерения"})
     * @Assert\Choice(choices = {"Вт", "См", "М", "Кг"}, message = "Choose a valid scale.")
     */
    private $scale;

    /**
     * Set name.
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
     * @return Characteristic
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

    /**
     * Set label.
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
     * Get label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set type.
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
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set scale.
     *
     * @param string $scale
     *
     * @return Characteristic
     */
    public function setScale($scale)
    {
        $this->scale = $scale;

        return $this;
    }

    /**
     * Get scale.
     *
     * @return string
     */
    public function getScale()
    {
        return $this->scale;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
