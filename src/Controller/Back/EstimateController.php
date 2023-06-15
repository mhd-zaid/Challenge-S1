<?php

namespace App\Controller\Back;

use App\Entity\Customer;
use App\Entity\Estimate;
use App\Form\EstimateType;
use App\Entity\Product;
use App\Entity\EstimateProduct;
use App\Repository\ProductRepository;
use App\Repository\EstimateRepository;
use App\Repository\EstimateProductRepository;
use App\Repository\CustomerRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/estimate')]
class EstimateController extends AbstractController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/', name: 'app_estimate_index', methods: ['GET'])]
    public function index(EstimateRepository $estimateRepository): Response
    {
        return $this->render('back/estimate/index.html.twig', [
            'estimates' => $estimateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_estimate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EstimateRepository $estimateRepository, ProductRepository $productRepository, CustomerRepository $customerRepository, EstimateProductRepository $estimateProductRepository): Response
    {
        $estimate = new Estimate();
        $products = $productRepository->findAll();
        $form = $this->createForm(EstimateType::class, $estimate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customer = $customerRepository->findOneBy([
                'email' => $form->get('email')->getData()
            ]);
            if ($customer === null) {
                $id = $this->generateCustomerId($customerRepository);
                $customer = $this->createCustomer($form,$id, $customerRepository);
            }
            $estimate->setClient($customer);
            $estimate->setTitle($form->getData()->getTitle());
            $estimate->setValidityDate($form->get('validity_date')->getData());
            dump($estimate);
            $estimateRepository->save($estimate, true);

            $products = $form->get('productQuantities')->getData();
            foreach($products as $value){
                $product = $productRepository->find($value['product']->getId());
                $product->setQuantity($product->getQuantity() - $value['quantity']);
                $productRepository->save($product, true);

                $estimateProduct = new EstimateProduct();
                $estimateProduct->setEstimate($estimate);
                $estimateProduct->setProduct($product);
                $estimateProduct->setTotalHt($product->getTotalHt());
                $estimateProduct->setTotalTva($product->getTotalTva());
                $estimateProduct->setQuantity($value['quantity']);
                $estimateProduct->setWorkforce($form->get('workforce')->getData());
                $estimateProductRepository->save($estimateProduct, true);
            }

            return $this->redirectToRoute('back_app_estimate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/estimate/new.html.twig', [
            'estimate' => $estimate,
            'form' => $form,
            'products' => $products
        ]);
    }

    #[Route('/{id}', name: 'app_estimate_show', methods: ['GET'])]
    public function show(Estimate $estimate): Response
    {
        return $this->render('back/estimate/show.html.twig', [
            'estimate' => $estimate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_estimate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Estimate $estimate, EstimateRepository $estimateRepository): Response
    {
        $form = $this->createForm(EstimateType::class, $estimate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $estimateRepository->save($estimate, true);

            return $this->redirectToRoute('app_estimate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/estimate/edit.html.twig', [
            'estimate' => $estimate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_estimate_delete', methods: ['POST'])]
    public function delete(Request $request, Estimate $estimate, EstimateRepository $estimateRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estimate->getId(), $request->request->get('_token'))) {
            $estimateRepository->remove($estimate, true);
        }

        return $this->redirectToRoute('app_estimate_index', [], Response::HTTP_SEE_OTHER);
    }

    public function checkId(int $id, CustomerRepository $customerRepository): bool
    {
        $user = $customerRepository->find($id);
        dump($user);
        if($user === null){
            return false;
        }

        return true;
    }

    public function generateCustomerId(CustomerRepository $customerRepository): int
    {
        $id = 0;
        do {
            $id = random_int(10000, 20000);
        } while ($this->checkId($id, $customerRepository));
        return $id;
    }

    public function createCustomer(object $form,int $id, CustomerRepository $customerRepository): Customer
    {
        // $firstname = $form->get('firstname')->getData();
        // $lastname = $form->get('lastname')->getData();
        $customer = new Customer();
        $customer->setId($id);
        // $customer->setFirstname($firstname);
        // $customer->setLastname($lastname);

        $customerRepository->save($customer,true);

        return $customer;
    }
}
