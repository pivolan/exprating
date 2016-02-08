<?php
/**
 * Date: 08.02.16
 * Time: 16:36
 */

namespace Exprating\SearchBundle\SearchParams;


class SearchParams
{
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