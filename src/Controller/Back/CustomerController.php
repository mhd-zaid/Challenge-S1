<?php

namespace App\Controller\Back;

use App\Entity\Customer;
use App\Form\CustomerRegisterType;
use App\Form\AdminEditCustomerType;
use App\Form\CustomerType;
use App\Repository\CustomerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security as Sec;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

#[Route('/customer')]
class CustomerController extends AdminController
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    #[Route('/', name: 'app_customer_index', methods: ['GET'])]
    #[Security('is_granted("ROLE_MECHANIC")')]
    public function index(CustomerRepository $customerRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $customer = $customerRepository->findAll();
        $customerPagination = $paginator->paginate(
            $customer, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            5 /*limit per page*/
        );
        return $this->render('back/customer/index.html.twig', [
            'customers' => $customer,
            'customersPagination' => $customerPagination,
        ]);
    }

    #[Route('/{id}', name: 'app_customer_show', methods: ['GET'])]
    #[Security('is_granted("ROLE_MECHANIC")')]
    public function show(Customer $customer): Response
    {
        return $this->render('back/customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_customer_edit', methods: ['GET', 'POST'])]
    #[Security('is_granted("ROLE_MECHANIC")')]
    public function edit(Request $request, Customer $customer, CustomerRepository $customerRepository): Response
    {
        $form = $this->createForm(AdminEditCustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customerRepository->save($customer, true);
            $this->addFlash(
                'success',
                'Client modifié'
            );
            return $this->redirectToRoute('back_app_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/new/{token}', name: 'app_customer_new', methods: ['GET', 'POST'])]
    #[Security('is_granted("ROLE_MECHANIC")')]
    public function new(Request $request, CustomerRepository $customerRepository,string $token): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerRegisterType::class,['token' =>$token]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $customer = $customerRepository->find($form->get('id')->getData());
            $customer->setIsValidated(true);
            $customer->setId(intval($form->get('id')->getData()));
            $customer->setPlainPassword($form->get('plainPassword')->getData());
            $customer->setIsRegistered(true);
            
            $customerRepository->save($customer, true);
            $this->addFlash(
                'success',
                'Client ajouté'
            );
            return $this->redirectToRoute('front_default_index', [], Response::HTTP_SEE_OTHER);
        }else{
            $this->addFlash(
                'error',
                'Erreur lors de l\'ajout'
            );
        }
        return $this->renderForm('front/customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_customer_delete', methods: ['POST'])]
    #[Security('is_granted("ROLE_MECHANIC") or user === customer')]
    public function delete(Request $request, Customer $customer, CustomerRepository $customerRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customer->getId(), $request->request->get('_token'))) {
            $customerRepository->remove($customer, true);
            $this->addFlash(
                'success',
                'Client supprimé'
            );
        }else{
            $this->addFlash(
                'error',
                'Erreur lors de la suppression'
            );
        }

        return $this->redirectToRoute('back_app_customer_index', [], Response::HTTP_SEE_OTHER);
    }

    public function generateCustomerId(CustomerRepository $customerRepository): int
    {
        $id = 0;
        do {
            $id = random_int(10000, 20000);
        } while ($this->checkId($id, $customerRepository));
        return $id;
    }

    public function checkId(int $id, CustomerRepository $customerRepository): bool
    {
        $user = $customerRepository->find($id);
        if($user === null){
            return false;
        }

        return true;
    }
}

