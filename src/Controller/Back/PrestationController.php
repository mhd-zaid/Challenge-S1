<?php

namespace App\Controller\Back;

use App\Entity\Prestation;
use App\Form\PrestationType;
use App\Repository\PrestationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
#[Route('/prestation')]
class PrestationController extends AbstractController
{
    #[Route('/', name: 'app_prestation_index')]
    #[Security('is_granted("ROLE_CUSTOMER") and !is_granted("ROLE_ACCOUNTANT") or is_granted("ROLE_ADMIN")')]
    public function index(PrestationRepository $prestationRepository): Response
    {
        return $this->render('back/prestation/index.html.twig', [
            'prestations' => $prestationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_prestation_new', methods: ['GET', 'POST'])]
    #[Security('is_granted("ROLE_MECHANIC")')]
    public function new(Request $request, PrestationRepository $prestationRepository): Response
    {
        $prestation = new Prestation();
        $form = $this->createForm(PrestationType::class, $prestation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $prestationRepository->save($prestation, true);

            return $this->redirectToRoute('back_app_prestation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/prestation/new.html.twig', [
            'prestation' => $prestation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_prestation_show', methods: ['GET'])]
    #[Security('user.getId() == invoice.getClient() or is_granted("ROLE_MECHANIC")')]
    public function show(Prestation $prestation): Response
    {
        return $this->render('back/prestation/show.html.twig', [
            'prestation' => $prestation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_prestation_edit', methods: ['GET', 'POST'])]
    #[Security('user.getId() == invoice.getClient() or is_granted("ROLE_MECHANIC")')]
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

    #[Route('/{id}', name: 'app_prestation_delete', methods: ['POST'])]
    #[Security('is_granted("ROLE_MECHANIC")')]
    public function delete(Request $request, Prestation $prestation, PrestationRepository $prestationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$prestation->getId(), $request->request->get('_token'))) {
            $prestationRepository->remove($prestation, true);
        }

        return $this->redirectToRoute('back_app_prestation_index', [], Response::HTTP_SEE_OTHER);
    }
}
