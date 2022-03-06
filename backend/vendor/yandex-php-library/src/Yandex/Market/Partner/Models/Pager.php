<?php

namespace Yandex\Market\Partner\Models;

use Yandex\Common\Model;

class Pager extends Model
{

    protected $total = null;

    protected $from = null;

    protected $to = null;

    protected $pageSize = null;

    protected $pagesCount = null;

    protected $currentPage = null;

    protected $mappingClasses = [];

    protected $propNameMap = [];

    /**
     * Retrieve the total property
     *
     * @return int|null
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set the total property
     *
     * @param int $total
     * @return $this
     */
    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    /**
     * Retrieve the from property
     *
     * @return int|null
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * Set the from property
     *
     * @param int $from
     * @return $this
     */
    public function setFrom($from)
    {
        $this->from = $from;
        return $this;
    }

    /**
     * Retrieve the to property
     *
     * @return int|null
     */
    public function getTo()
    {
        return $this->to;
    }

    /**
     * Set the to property
     *
     * @param int $to
     * @return $this
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * Retrieve the pageSize property
     *
     * @return int|null
     */
    public function getPageSize()
    {
        return $this->pageSize;
    }

    /**
     * Set the pageSize property
     *
     * @param int $pageSize
     * @return $this
     */
    public function setPageSize($pageSize)
    {
        $this->pageSize = $pageSize;
        return $this;
    }

    /**
     * Retrieve the pagesCount property
     *
     * @return int|null
     */
    public function getPagesCount()
    {
        return $this->pagesCount;
    }

    /**
     * Set the pagesCount property
     *
     * @param int $pagesCount
     * @return $this
     */
    public function setPagesCount($pagesCount)
    {
        $this->pagesCount = $pagesCount;
        return $this;
    }

    /**
     * Retrieve the currentPage property
     *
     * @return int|null
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

    /**
     * Set the currentPage property
     *
     * @param int $currentPage
     * @return $this
     */
    public function setCurrentPage($currentPage)
    {
        $this->currentPage = $currentPage;
        return $this;
    }
}
