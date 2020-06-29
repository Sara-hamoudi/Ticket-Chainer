<?php

class PageInfos

{
    private $total;

    private $limit;

    private $currentPage;

    private $lastPage;

    public function getTotal(): int
    {
        return $this->total;
    }

    public function setTotal(int $total): PageInfos
    {
        $this->total = $total;
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function setLimit(int $limit): PageInfos
    {
        $this->limit = $limit;
        return $this;
    }

    public function getCurrentPage(): int
    {
        return $this->currentPage;
    }

    public function setCurrentPage(int $currentPage): PageInfos
    {
        $this->currentPage = $currentPage;
        return $this;
    }

    public function getLastPage(): int
    {
        return $this->lastPage;
    }

    public function setLastPage(int $lastPage): PageInfos
    {
        $this->lastPage = $lastPage;
        return $this;
    }


}
?>