<?php

namespace App\Service;

use App\Exception\BadRequestException;
use App\Exception\UnexpectedException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GetContent
{
    private const TYPE_AUTHORIZED = "application/json";

    public function getTeams(UploadedFile $uploadedFile) {

        if ($uploadedFile->getClientMimeType() !== self::TYPE_AUTHORIZED) {
            throw new BadRequestException(\sprintf('Le fichier soumis doit etre de type json'));
        }
        
        $content = file_get_contents($uploadedFile);

        if($content == false) {
            throw new UnexpectedException("Error Processing Request");
        }

        $datas = json_decode($content, true);

        if (!array_key_exists("teams", $datas)) {
            throw new BadRequestException("La clé Teams n'existe pas");
        }

        return $datas;
    }
}
