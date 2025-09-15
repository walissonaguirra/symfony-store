<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/products', name: 'admin_')]
final class ProductController extends AbstractController
{
    #[Route('/', name: 'index_products', methods: 'GET')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $products = $entityManager->getRepository(Product::class)->findAll();

        return $this->render('admin/product/index.html.twig', compact('products'));
    }

    #[Route('/create', name: 'create_products')]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $product = $form->getData();

            $product->setCreatedAt();
            $product->setUpdatedAt();

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Produto criado com sucesso!');

            return $this->redirectToRoute('admin_index_products');
        }

        return $this->render('admin/product/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{product}', name: 'edit_products')]
    public function edit($product, EntityManagerInterface $entityManager, Request $request): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($product);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $product = $form->getData();

            $product->setUpdatedAt();

            $entityManager->flush();

            $this->addFlash('success', 'Produto atualizado com sucesso!');

            return $this->redirectToRoute('admin_edit_products', ['product' => $product->getId()]);

        }

        return $this->render('admin/product/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/remove/{product}', name: 'remove_products', methods: 'GET')]
    public function remove($product, EntityManagerInterface $entityManager): Response
    {
        try {
            $product = $entityManager->getRepository(Product::class)->find($product);
            $entityManager->remove($product);
            $entityManager->flush();

            $this->addFlash('success', 'Produto apagado com sucesso!');

            return $this->redirectToRoute('admin_index_products');
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}
