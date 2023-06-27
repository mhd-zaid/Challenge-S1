<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;

class DefaultController extends AbstractController
{
    
    #[Route('/', name: 'default_index', methods: ['GET'])]
    public function index(): Response
    {

        return $this->render('back/default/index.html.twig');
    }

    #[Route('/pdf', name: 'pdf', methods: ['GET'])]
    public function pdf(Pdf $pdf): PdfResponse
    {
        $html = $this->renderView('back/pdf/estimate.html.twig');

        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            'file.pdf'
        );
    }
}
