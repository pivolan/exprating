<?php
/**
 * Date: 04.04.16
 * Time: 18:03
 */

namespace Exprating\ImportBundle\Entity;


class Offer
{
    private $hash;

    private $name;
    private $price;
    private $url;

    private $id;
    private $description;
    private $oldPrice;
    private $categoryId;
    private $categoryName;
    private $categoryPath;
    private $marketCategory;

    private $amount;
    private $available;
    private $params;
    private $pictures;
    private $year;
    private $vendor;
    private $vendorCode;
}