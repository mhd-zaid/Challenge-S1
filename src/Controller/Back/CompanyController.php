<?php

namespace App\Controller\Back;

use App\Entity\Company;
use App\Form\CompanyType;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Route('/company')]
#[Security('is_granted("ROLE_ADMIN")')]
class CompanyController extends AbstractController
{
    #[Route('/', name: 'app_company_show', methods: ['GET'])]
    public function show(CompanyRepository $companyRepository): Response
    {
        $company = $companyRepository->findOneBy([
            'id' => 1
        ]);

        return $this->render('back/company/show.html.twig', [
            'company' => $company,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, CompanyRepository $companyRepository): Response
    {
        $form = $this->createForm(CompanyType::class, $company);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $companyRepository->save($company, true);
            $this->addFlash(
                'success',
                'La société a bien été modifiée'
            );
            return $this->redirectToRoute('back_app_company_show', [], Response::HTTP_SEE_OTHER);
        }elseif ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash(
                'error',
                'La société n\'a pas été modifiée'
            );
        }

        return $this->renderForm('back/company/edit.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company, CompanyRepository $companyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $companyRepository->remove($company, true);
            $this->addFlash(
                'success',
                'La société a bien été supprimée'
            );
        }else{
            $this->addFlash(
                'error',
                'La société n\'a pas été supprimée'
            );
        }

        return $this->redirectToRoute('back_app_company_show', [], Response::HTTP_SEE_OTHER);
    }
}
