<?php

namespace App\Entities;

use App\Interfaces\QueryLineInterface;
use DateTime;

class QueryLine extends Type implements QueryLineInterface
{
    const REGEX = '/^(D) (\d+|\*)(?:\.(\d+))? (\d+|\*)(?:\.(\d+))?\.?(\d+)? ([PN]) (?<date_from>\d{1,2}\.\d{1,2}\.\d{4})(?:-(?<date_to>\d{1,2}\.\d{1,2}\.\d{4}))?$/';
    private DateTime $date_from;
    private ?DateTime $date_to;

    /**
     * @param string $line
     */
    public function __construct(string $line)
    {
        $this->processRegex($line);
        parent::__construct($this->serviceId, $this->variationId, $this->questionTypeId, $this->categoryId, $this->subCategoryId, $this->P_N);
    }

    /**
     * @param string $line
     * @return void
     */
    public function processRegex(string $line): void
    {
        if (preg_match(self::REGEX, $line, $matches, PREG_UNMATCHED_AS_NULL)) {
            [, , $this->serviceId, $this->variationId, $this->questionTypeId, $this->categoryId, $this->subCategoryId, $this->P_N, ,] = $matches;
            $this->date_from = $this->createDate($matches['date_from']);
            $this->date_to = $matches['date_to'] ? $this->createDate($matches['date_to']) : null;
        } else {
            echo "Invalid QueryLine: $line";
            exit(-1);
            // throw new Exception("Invalid QueryLine: $line");
        }
    }

    /**
     * @return DateTime
     */
    public function getDateFrom(): DateTime
    {
        return $this->date_from;
    }

    /**
     * @return DateTime|null
     */
    public function getDateTo(): ?DateTime
    {
        return $this->date_to;
    }

    /**
     * @param WaitingLine $waitingLine
     * @return bool
     */
    private function serviceIdMatches(WaitingLine $waitingLine): bool
    {
        return $this->getServiceId() === $waitingLine->getServiceId() || $this->getServiceId() === "*";
    }

    /**
     * @param WaitingLine $waitingLine
     * @return bool
     */
    private function variationIdMatches(WaitingLine $waitingLine): bool
    {
        return !$this->getVariationId() || $this->getVariationId() === $waitingLine->getVariationId();
    }

    /**
     * @param WaitingLine $waitingLine
     * @return bool
     */
    private function questionIdMatches(WaitingLine $waitingLine): bool
    {
        return $this->getQuestionTypeId() === $waitingLine->getQuestionTypeId() || $this->getQuestionTypeId() === "*";
    }

    /**
     * @param WaitingLine $waitingLine
     * @return bool
     */
    private function categoryIdMatches(WaitingLine $waitingLine): bool
    {
        return !$this->getCategoryId() || $this->getCategoryId() === $waitingLine->getCategoryId();
    }

    /**
     * @param WaitingLine $waitingLine
     * @return bool
     */
    private function subCategoryIdMatches(WaitingLine $waitingLine): bool
    {
        return !$this->getSubCategoryId() || $this->getSubCategoryId() === $waitingLine->getSubCategoryId();
    }

    /**
     * @param WaitingLine $waitingLine
     * @return bool
     */
    private function isDateInInterval(WaitingLine $waitingLine): bool
    {
        return $this->getDateTo() && $waitingLine->getDate() > $this->getDateFrom() && $waitingLine->getDate() < $this->getDateTo();
    }

    /**
     * @param WaitingLine $waitingLine
     * @return bool
     */
    private function linesMatch(WaitingLine $waitingLine): bool
    {
        return $this->serviceIdMatches($waitingLine)
            && $this->variationIdMatches($waitingLine)
            && $this->questionIdMatches($waitingLine)
            && $this->categoryIdMatches($waitingLine)
            && $this->subCategoryIdMatches($waitingLine)
            && $this->isDateInInterval($waitingLine);
    }

    /**
     * @param array $waitingTimeline
     * @return array
     */
    private function getValidEntries(array $waitingTimeline): array
    {
        return array_filter($waitingTimeline, function ($line) {
            return $this->linesMatch($line);
        });
    }

    /**
     * @param array $validEntries
     * @return int
     */
    private function calculateWaitingTime(array $validEntries): int
    {
        return array_reduce($validEntries, function ($carry, $line) {
            return $carry + $line->getTime();
        }, 0);
    }

    /**
     * @param array $waitingTimeline
     * @return int|string
     */
    public function getAverageWaitingTime(array $waitingTimeline): int|string
    {
        $validEntries = $this->getValidEntries($waitingTimeline);
        $validEntriesCount = count($validEntries);

        if ($validEntriesCount > 0) {
            $waitingTime = $this->calculateWaitingTime($validEntries);
            return intval(round($waitingTime / $validEntriesCount));
        } else {
            return "-";
        }
    }
}