<?php

namespace App\Entities;

use App\Interfaces\TypeInterface;
use DateTime;

abstract class Type implements TypeInterface
{
    protected int|string $serviceId;
    protected ?int $variationId = null;
    protected int|string $questionTypeId;
    protected ?int $categoryId = null;
    protected ?int $subCategoryId = null;
    protected string $P_N;

    /**
     * @param int|string $serviceId
     * @param int|null $variationId
     * @param int|string $questionTypeId
     * @param int|null $categoryId
     * @param int|null $subCategoryId
     * @param string $P_N
     */
    public function __construct(int|string $serviceId, ?int $variationId, int|string $questionTypeId, ?int $categoryId, ?int $subCategoryId, string $P_N)
    {
        $this->serviceId = $serviceId;
        $this->variationId = $variationId;
        $this->questionTypeId = $questionTypeId;
        $this->categoryId = $categoryId;
        $this->subCategoryId = $subCategoryId;
        $this->P_N = $P_N;
    }

    /**
     * @param string $date
     * @return DateTime
     */
    protected function createDate(string $date): DateTime
    {
        return DateTime::createFromFormat('d.m.Y', $date);
    }

    /**
     * @return int|string
     */
    public function getServiceId(): int|string
    {
        return $this->serviceId;
    }

    /**
     * @return int|null
     */
    public function getVariationId(): ?int
    {
        return $this->variationId;
    }

    /**
     * @return int|string
     */
    public function getQuestionTypeId(): int|string
    {
        return $this->questionTypeId;
    }

    /**
     * @return int|null
     */
    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    /**
     * @return int|null
     */
    public function getSubCategoryId(): ?int
    {
        return $this->subCategoryId;
    }

    /**
     * @return string
     */
    public function getPN(): string
    {
        return $this->P_N;
    }
}