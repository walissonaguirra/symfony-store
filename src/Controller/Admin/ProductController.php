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

    #[Route('/create', name: 'create_products', methods: 'GET')]
    public function create(): Response
    {
        $form = $this->createForm(ProductType::class);

        return $this->render('admin/product/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/store', name: 'store_products', methods: 'POST')]
    public function store(Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $data = $request->request->all();

            $product = new Product();
            $product->setName($data['name']);
            $product->setSlug($data['slug']);
            $product->setDescription($data['description']);
            $product->setBady($data['body']);
            $product->setPrice($data['price']);

            $product->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
            $product->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash('success', 'Produto criado com sucesso!');

            return $this->redirectToRoute('admin_index_products');
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }

    #[Route('/edit/{product}', name: 'edit_products', methods: 'GET')]
    public function edit($product, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($product);

        return $this->render('admin/product/edit.html.twig', compact('product'));
    }

    #[Route('/update/{product}', name: 'update_products', methods: 'POST')]
    public function update($product, Request $request, EntityManagerInterface $entityManager): Response
    {
        try {
            $data = $request->request->all();

            $product = $entityManager->getRepository(Product::class)->find($product);
            $product->setName($data['name']);
            $product->setSlug($data['slug']);
            $product->setDescription($data['description']);
            $product->setBady($data['body']);
            $product->setPrice($data['price']);

            $product->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));

            $entityManager->flush();

            $this->addFlash('success', 'Produto atualizado com sucesso!');

            return $this->redirectToRoute('admin_edit_products', ['product' => $product->getId()]);
        } catch (\Exception $e) {
            die($e->getMessage());
        }
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
