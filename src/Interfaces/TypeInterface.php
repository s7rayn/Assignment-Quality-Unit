<?php

namespace App\Interfaces;

interface TypeInterface
{
    function processRegex(string $line): void;
}