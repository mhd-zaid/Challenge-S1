<?php

namespace App\Controller\Back;

use App\Entity\Category;
use App\Entity\Prestation;
use App\Entity\PrestationProduct;
use App\Entity\Product;
use App\Form\PrestationType;
use App\Repository\CategoryRepository;
use App\Repository\PrestationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
#[Route('/prestation')]
class PrestationController extends AdminController
{
    #[Route('/', name: 'app_prestation_index')]
    #[Security('is_granted("ROLE_CUSTOMER") and !is_granted("ROLE_ACCOUNTANT") or is_granted("ROLE_ADMIN")')]
    public function index(PrestationRepository $prestationRepository,CategoryRepository $categoryRepository): Response
    {
        return $this->render('back/prestation/index.html.twig', [
            'prestations' => $prestationRepository->findAll(),
            'categories' => $categoryRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_prestation_new', methods: ['GET', 'POST'])]
    #[Security('is_granted("ROLE_MECHANIC")')]
    public function new(Request $request,EntityManagerInterface $em ): Response
    {
        $prestation = new Prestation();
        $prestationRepository = $em->getRepository(Prestation::class);
        $productRepository = $em->getRepository(Product::class);
        $products = $productRepository->findAll();
        $form = $this->createForm(PrestationType::class, $prestation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prestation->setName($form->getData()->getName());
            $prestation->setCategory($form->getData()->getCategory());
            $prestation->setDuration($form->getData()->getDuration());
            $prestation->setWorkforce($form->getData()->getWorkforce());
            $prestationRepository->save($prestation, true);

            $products = $form->get('productQuantities')->getData();
            foreach($products as $value){
                $product = $productRepository->find($value['product']->getId());
                $product->setQuantity($product->getQuantity() - $value['quantity']);
                $productRepository->save($product, true);

                $prestationProduct = new PrestationProduct();
                $prestationProduct->setPrestation($prestation);
                $prestationProduct->setProduct($product);
                $prestationProduct->setTotalHt($product->getTotalHt());
                $prestationProduct->setTotalTva($product->getTotalTva());
                $prestationProduct->setQuantity($value['quantity']);
                $prestationProduct->setWorkforce($form->get('workforce')->getData());
                $em->getRepository(PrestationProduct::class)->save($prestationProduct, true);

            }
            return $this->redirectToRoute('back_app_prestation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/prestation/new.html.twig', [
            'prestation' => $prestation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_prestation_show', methods: ['GET'])]
    public function show(Prestation $prestation): Response
    {
        return $this->render('back/prestation/show.html.twig', [
            'prestation' => $prestation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_prestation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Prestation $prestation, PrestationRepository $prestationRepository): Response
    {
        $form = $this->createForm(PrestationType::class, $prestation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prestationRepository->save($prestation, true);

            return $this->redirectToRoute('back_app_prestation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/prestation/edit.html.twig', [
            'prestation' => $prestation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'app_prestation_delete', methods: ['POST'])]
    #[Security('is_granted("ROLE_MECHANIC")')]
    public function delete(Request $request, Prestation $prestation, PrestationRepository $prestationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prestation->getId(), $request->request->get('_token'))) {
            $prestationRepository->remove($prestation, true);
        }

        return $this->redirectToRoute('back_app_prestation_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/filter', name: 'app_prestation_filter', methods: ['GET'])]
    public function filter(Request $request,EntityManagerInterface $em): Response
    {   
        $filters = [
            'category' => $request->query->get('category'),
            'prestation' => $request->query->get('prestation'),
        ];
        
        $filteredParams = array_filter($filters, function($value) {
            return !empty($value);
        });

        if (empty($filteredParams)) {
            
            return $this->redirectToRoute('back_app_prestation_index');
        }

        $prestations  = $em->getRepository(Prestation::class)->findByFilter($filteredParams);
        $categories = $em->getRepository(Category::class)->findAll();
        return $this->render('back/prestation/index.html.twig',['prestations'=>$prestations,'categories'=>$categories]);
    }
}
