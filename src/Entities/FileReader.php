<?php

namespace App\Entities;

use App\Interfaces\InputInterface;

class FileReader extends InputReader implements InputInterface
{
    const SUPPORTED_EXTENSIONS = array('txt', 'csv');
    protected array $input;

    /**
     * @param string $filename
     */
    public function __construct(string $filename = "")
    {
        if (empty(trim($filename))) {
            $filename = $this->promptFilename();
        }
        parent::__construct($this->loadFile($filename));
    }

    /**
     * @return string
     */
    private function promptFilename(): string
    {
        echo "Input file name: ";
        return trim(fgets(STDIN));
    }

    /**
     * @param string $filename
     * @return array|false|void
     */
    private function loadFile(string $filename)
    {
        if ($this->isFileCorrect($filename)) {
            return file($filename, FILE_IGNORE_NEW_LINES) ?? [];
        } else {
            exit(-1);
        }
    }

    /**
     * @param string $filename
     * @return bool
     */
    private function isFileCorrect(string $filename): bool
    {
        if (!file_exists($filename)) {
            echo "File $filename does not exist!" . PHP_EOL;
            return false;
        }

        if (!is_readable($filename)) {
            echo "File $filename is not readable!" . PHP_EOL;
            return false;
        }

        if (is_dir($filename)) {
            echo "File $filename is a directory!" . PHP_EOL;
            return false;
        }

        if (!$this->isFileInCorrectFormat($filename)) {
            echo "File $filename is not in correct format!" . PHP_EOL;
            return false;
        }

        return true;
    }

    /**
     * @param string $filename
     * @return bool
     */
    private function isFileInCorrectFormat(string $filename): bool
    {
        $parts = explode(".", $filename);
        $extension = strtolower(end($parts));

        if (in_array($extension, self::SUPPORTED_EXTENSIONS)) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getInput(): array
    {
        return $this->input;
    }
}