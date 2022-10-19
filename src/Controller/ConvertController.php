<?php

namespace App\Controller;

use App\Exception\InvalidArgumentException;
use App\Exception\UnexpectedException;
use App\Form\FileCustomizedType;
use App\Form\Model\FileFormModel;
use App\Service\GetContent;
use App\Service\TeamHelper;
use App\Service\TeamUploader;
use App\Service\UploadedFileService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConvertController extends AbstractController
{
    /**
     * @Route("/", name="app_convert_file")
     */
    public function index(
        Request $request,
        GetContent $getContent,
        TeamHelper $teamHelper,
    ): Response
    {
        $fileFormModel = new FileFormModel();
        
        $form = $this->createForm(FileCustomizedType::class, $fileFormModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            try {
                $content = $getContent->getTeams($form->get('fileJson')->getData());

                if ($content === null) {
                    throw new \InvalidArgumentException("the format of json file is bad");
                }

                $teamHelper->buildTeams($content);
            
                return $this->redirectToRoute('app_download');

            } catch (\Throwable $th) {
                throw $th;
            }
        }

        return $this->render('convert/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/download", name="app_download")
     */
    public function download()
    {
        return $this->render('convert/download.html.twig');
    }
}
