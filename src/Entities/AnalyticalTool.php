<?php

namespace App\Entities;

use App\Interfaces\AnalyticalToolInterface;

class AnalyticalTool implements AnalyticalToolInterface
{
    private array $input;
    private array $averageWaitingTime;

    /**
     * @param array $input
     */
    public function __construct(array $input)
    {
        $this->input = $input;
        $this->averageWaitingTime = [];
    }

    /**
     * @param string $inputLine
     * @param array $waitingTimeline
     * @return void
     */
    private function processLine(string $inputLine, array &$waitingTimeline): void
    {
        $lineStart = explode(' ', $inputLine)[0];

        switch ($lineStart) {
            case 'C':
                $this->processWaitingLine($inputLine, $waitingTimeline);
                break;
            case 'D':
                $this->processQueryLine($inputLine, $waitingTimeline);
                break;
            default:
                echo "Invalid input: Unknown line start $lineStart";
                exit(-1);
                // throw new Exception('Invalid input: Unknown line start ' . $lineStart);
        }
    }

    /**
     * @param string $inputLine
     * @param array $waitingTimeline
     * @return void
     */
    private function processWaitingLine(string $inputLine, array &$waitingTimeline): void
    {
        $waitingTimeline[] = new WaitingLine($inputLine);
    }

    /**
     * @param string $inputLine
     * @param array $waitingTimeline
     * @return void
     */
    private function processQueryLine(string $inputLine, array &$waitingTimeline): void
    {
        $queryLine = new QueryLine($inputLine);
        $averageWaitingTime = $queryLine->getAverageWaitingTime($waitingTimeline);
        $this->averageWaitingTime[] = $averageWaitingTime;
    }

    /**
     * @return void
     */
    public function analyzeWaitingTime(): void
    {
        $firstLine = intval(array_shift($this->input));

        if ($firstLine > 100_000) {
            echo "First line should be less than or equal to 100 000!";
            exit(-1);
            // throw new Exception("First line should be less than or equal to 100 000!");
        }

        $waitingTimeline = array();

        for ($i = 0; $i < $firstLine; $i++) {
            $this->processLine($this->input[$i], $waitingTimeline);
        }
    }

    /**
     * @param array $input
     */
    public function setInput(array $input): void
    {
        $this->input = $input;
    }

    /**
     * @return array
     */
    public function getWaitingTime(): array
    {
        return $this->averageWaitingTime;
    }

    /**
     * @return void
     */
    public function printAverageWaitingTime(): void
    {
        foreach ($this->getWaitingTime() as $waitingTime) {
            echo $waitingTime . PHP_EOL;
        }
    }
}