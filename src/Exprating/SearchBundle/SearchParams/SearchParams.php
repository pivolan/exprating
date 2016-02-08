<?php
/**
 * Date: 08.02.16
 * Time: 16:36
 */

namespace Exprating\SearchBundle\SearchParams;

use Symfony\Component\Validator\Constraints as Assert;


class SearchParams
{
    /**
     * @var string
     * @Assert\Length(min=3, minMessage = "Минимальный размер строки 3 символа.")
     */
    protected $string;

    /**
     * @return mixed
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param mixed $string
     *
     * @return $this
     */
    public function setString($string)
    {
        $this->string = $string;
        return $this;
    }
} 