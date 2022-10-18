<?php

namespace App\Controller;

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
     * @Route("/convert", name="app_convert_file")
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
            $content = $getContent->getTeams($form->get('fileJson')->getData());

            // $fileteams = $teamHelper->buildTeams($content);

            dd($teamHelper->buildMembersTeams($content));
            
            $this->redirectToRoute('app_convert_file');
        }

        return $this->render('convert/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
