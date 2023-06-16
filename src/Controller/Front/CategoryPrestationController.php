<?php

namespace App\Controller\Front;

use App\Entity\CategoryPrestation;
use App\Form\CategoryPrestationType;
use App\Repository\CategoryPrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/category/prestation')]
class CategoryPrestationController extends AbstractController
{
    #[Route('/', name: 'app_category_prestation_index', methods: ['GET'])]
    public function index(CategoryPrestationRepository $categoryPrestationRepository): Response
    {
        return $this->render('front/category_prestation/index.html.twig', [
            'category_prestations' => $categoryPrestationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_category_prestation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CategoryPrestationRepository $categoryPrestationRepository): Response
    {
        $categoryPrestation = new CategoryPrestation();
        $form = $this->createForm(CategoryPrestationType::class, $categoryPrestation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryPrestationRepository->save($categoryPrestation, true);

            return $this->redirectToRoute('front_app_category_prestation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/category_prestation/new.html.twig', [
            'category_prestation' => $categoryPrestation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_prestation_show', methods: ['GET'])]
    public function show(CategoryPrestation $categoryPrestation): Response
    {
        return $this->render('front/category_prestation/show.html.twig', [
            'category_prestation' => $categoryPrestation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_prestation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CategoryPrestation $categoryPrestation, CategoryPrestationRepository $categoryPrestationRepository): Response
    {
        $form = $this->createForm(CategoryPrestationType::class, $categoryPrestation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryPrestationRepository->save($categoryPrestation, true);

            return $this->redirectToRoute('front_app_category_prestation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('front/category_prestation/edit.html.twig', [
            'category_prestation' => $categoryPrestation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_prestation_delete', methods: ['POST'])]
    public function delete(Request $request, CategoryPrestation $categoryPrestation, CategoryPrestationRepository $categoryPrestationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryPrestation->getId(), $request->request->get('_token'))) {
            $categoryPrestationRepository->remove($categoryPrestation, true);
        }

        return $this->redirectToRoute('front_app_category_prestation_index', [], Response::HTTP_SEE_OTHER);
    }
}
