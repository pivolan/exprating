<?php
/**
 * Date: 11.05.16
 * Time: 10:24
 */

namespace Exprating\SearchBundle\Dto;

/**
 * Используется для поиска, настройка поиска, в какой репе искать, по какому индексу, по каким полям
 * @package Exprating\SearchBundle\Dto
 */
class SearchCriteria
{
    /**
     * @var String
     */
    protected $repositoryName;

    /**
     * @var string
     */
    protected $indexName;

    /**
     * @var array
     */
    protected $fields;

    /**
     * @var array
     */
    protected $criteria;

    /**
     * @return mixed
     */
    public function getRepositoryName()
    {
        return $this->repositoryName;
    }

    /**
     * @param mixed $repositoryName
     */
    public function setRepositoryName($repositoryName)
    {
        $this->repositoryName = $repositoryName;
    }

    /**
     * @return mixed
     */
    public function getIndexName()
    {
        return $this->indexName;
    }

    /**
     * @param mixed $indexName
     */
    public function setIndexName($indexName)
    {
        $this->indexName = $indexName;
    }

    /**
     * @return mixed
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param mixed $fields
     */
    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param mixed $criteria
     */
    public function setCriteria($criteria)
    {
        $this->criteria = $criteria;
    }
}