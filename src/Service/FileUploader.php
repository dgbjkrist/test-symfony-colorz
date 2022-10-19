<?php

namespace App\Service;
use App\Exception\UnexpectedException;


class FileUploader
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(string $fileName)
    {
        $file = fopen($this->getTargetDirectory().$fileName, 'w');
        if ($file === false) {
            throw new UnexpectedException("Error Processing Request : ".__METHOD__);
        }
        return $file;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
