<?php

namespace App;

require __DIR__ . "/../vendor/autoload.php";

use App\Entities\AnalyticalTool;
use App\Entities\FileReader;

class App
{
    private array $input = [];

    public function loadInputFileAsArray(string $filename): void
    {
        $fileReader = new FileReader($filename);
        $this->input = $fileReader->getInput();
    }

    public function analyzeAverageWaitingTime(): void
    {
        $analyticalTool = new AnalyticalTool($this->input);
        $analyticalTool->analyzeWaitingTime();
        $analyticalTool->printAverageWaitingTime();
    }
}

$app = new App();
$app->loadInputFileAsArray('Data/input.txt');
$app->analyzeAverageWaitingTime();
