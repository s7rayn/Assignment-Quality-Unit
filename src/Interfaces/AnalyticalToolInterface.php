<?php

namespace App\Interfaces;

interface AnalyticalToolInterface
{
    function analyzeWaitingTime(): void;

    function printAverageWaitingTime(): void;
}