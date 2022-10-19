<?php

namespace App\Service;

use App\Exception\BadRequestException;
use App\Exception\InvalidArgumentException;
use App\Exception\UnexpectedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GetContent
{
    private const TYPE_AUTHORIZED = "application/json";

    public function getTeams(UploadedFile $uploadedFile) {

        if ($uploadedFile->getClientMimeType() !== self::TYPE_AUTHORIZED) {
            throw new InvalidArgumentException(\sprintf('Le fichier soumis doit etre de type json'));
        }
        
        $content = file_get_contents($uploadedFile);

        if($content == false) {
            throw new UnexpectedException("Error Processing Request : ".__METHOD__);
        }

        return json_decode($content, true);
    }
}
