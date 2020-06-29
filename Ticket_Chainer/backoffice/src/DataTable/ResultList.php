<?php


namespace App\DataTable;


class ResultList
{
    /**
     * @var int
     */
    private $draw;
    /**
     * @var int
     */
    private $recordsTotal;
    /**
     * @var int
     */
    private $recordsFiltered;

    /**
     * @var array
     */
    private $data;

    /**
     * @return int
     */
    public function getDraw(): int
    {
        return $this->draw;
    }

    /**
     * @param int $draw
     * @return DataTableXhrResponseData
     */
    public function setDraw(int $draw): DataTableXhrResponseData
    {
        $this->draw = $draw;
        return $this;
    }

    /**
     * @return int
     */
    public function getRecordsTotal(): int
    {
        return $this->recordsTotal;
    }

    /**
     * @param int $recordsTotal
     * @return DataTableXhrResponseData
     */
    public function setRecordsTotal(int $recordsTotal): DataTableXhrResponseData
    {
        $this->recordsTotal = $recordsTotal;
        return $this;
    }

    /**
     * @return int
     */
    public function getRecordsFiltered(): int
    {
        return $this->recordsFiltered;
    }

    /**
     * @param int $recordsFiltered
     * @return DataTableXhrResponseData
     */
    public function setRecordsFiltered(int $recordsFiltered): DataTableXhrResponseData
    {
        $this->recordsFiltered = $recordsFiltered;
        return $this;
    }

    /**
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @return DataTableXhrResponseData
     */
    public function setData(array $data): DataTableXhrResponseData
    {
        $this->data = $data;
        return $this;
    }

}