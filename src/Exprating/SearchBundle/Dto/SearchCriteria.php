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
     * @return string
     */
    public function getRepositoryName()
    {
        return $this->repositoryName;
    }

    /**
     * @param string $repositoryName
     *
     * @return $this
     */
    public function setRepositoryName($repositoryName)
    {
        $this->repositoryName = $repositoryName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getIndexName()
    {
        return $this->indexName;
    }

    /**
     * @param string $indexName
     *
     * @return $this
     */
    public function setIndexName($indexName)
    {
        $this->indexName = $indexName;

        return $this;
    }

    /**
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * @param array $fields
     *
     * @return $this
     */
    public function setFields(array $fields)
    {
        $this->fields = $fields;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCriteria()
    {
        return $this->criteria;
    }

    /**
     * @param array $criteria
     *
     * @return $this
     */
    public function setCriteria(array $criteria)
    {
        $this->criteria = $criteria;

        return $this;
    }
}
