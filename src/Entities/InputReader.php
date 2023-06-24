<?php

namespace App\Entities;

abstract class InputReader
{
    protected array $input;

    /**
     * @param array $input
     */
    public function __construct(array $input)
    {
        $this->input = $input;
    }

    /**
     * @return array
     */
    public function getInput(): array
    {
        return $this->input;
    }
}