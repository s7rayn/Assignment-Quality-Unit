<?php

namespace App\Interfaces;

interface QueryLineInterface
{
    function getAverageWaitingTime(array $waitingTimeline): int|string;
}