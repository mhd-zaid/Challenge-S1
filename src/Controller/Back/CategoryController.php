<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Route('/category')]
#[Security('is_granted("ROLE_MECHANIC")')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'app_category_index', methods: ['GET', 'POST'])]
    public function index(Request $request, CategoryRepository $categoryRepository, PaginatorInterface $paginator): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $category->setisActive(true);
            $categoryRepository->save($category, true);
            $this->addFlash(
                'success',
                'Catégorie ajoutée'
            );
            return $this->redirectToRoute('back_app_category_index', [], Response::HTTP_SEE_OTHER);
        }elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(
                'error',
                'Erreur lors de l\'ajout de la catégorie'
            );
        }

        $category = $categoryRepository->findBy([
            'isActive' => true
        ]);
        $categoriesPagination = $paginator->paginate(
            $category, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        return $this->renderForm('back/category/index.html.twig', [
            'categories' => $categoryRepository->findBy([
                'isActive' => true
            ]),
            'category' => $category,
            'form' => $form,
            'categoriesPagination' => $categoriesPagination
        ]);
    }

    #[Route('/{id}/edit', name: 'app_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoryRepository->save($category, true);
            $this->addFlash(
                'success',
                'Catégorie modifiée avec succès'
            );
            return $this->redirectToRoute('back_app_category_index', [], Response::HTTP_SEE_OTHER);
        }elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(
                'error',
                'Erreur lors de la modification de la catégorie'
            );
        }

        return $this->renderForm('back/category/edit.html.twig', [
            'category' => $category,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_category_delete', methods: ['POST'])]
    public function delete(Request $request, Category $category, CategoryRepository $categoryRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $category->setisActive(false);
            $categoryRepository->save($category, true);
            $this->addFlash(
                'success',
                'Catégorie supprimée avec succès'
            );
        }else{
            $this->addFlash(
                'error',
                'Erreur lors de la suppression de la catégorie'
            );
        }

        return $this->redirectToRoute('back_app_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
