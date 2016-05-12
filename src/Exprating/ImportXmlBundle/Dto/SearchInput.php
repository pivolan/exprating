<?php
/**
 * Date: 11.05.16
 * Time: 2:13
 */

namespace Exprating\ImportXmlBundle\Dto;


use AppBundle\Entity\Product;
use Symfony\Component\Validator\Constraints as Assert;

class SearchInput
{
    /**
     * @var string
     * @Assert\NotBlank
     */
    protected $search;

    /**
     * @return mixed
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param mixed $search
     */
    public function setSearch($search)
    {
        $this->search = $search;
    }
}
