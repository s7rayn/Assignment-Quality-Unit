<?php

namespace App\Entities;

use DateTime;

class WaitingLine extends Type
{
    const REGEX = '/^(C) (\d+)(?:\.(\d+))? (\d+)(?:\.(\d+))?\.?(\d+)? ([PN]) (?<date>\d{1,2}\.\d{1,2}\.\d{4}) (\d+)$/';
    private DateTime $date;
    private int $time;

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
            [, , $this->serviceId, $this->variationId, $this->questionTypeId, $this->categoryId, $this->subCategoryId, $this->P_N, , $this->time] = $matches;
            $this->date = $this->createDate($matches['date']);
        } else {
            echo "Invalid WaitingLine: $line";
            exit(-1);
            // throw new Exception("Invalid WaitingLine: $line");
        }
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @return int
     */
    public function getTime(): int
    {
        return $this->time;
    }
}