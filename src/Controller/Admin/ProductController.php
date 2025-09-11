<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    }

    #[Route('/store', name: 'store_products', methods: 'POST')]
    public function store(EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $product->setName('Produto Test');
        $product->setSlug('produto-test');
        $product->setDescription('Descrição');
        $product->setBady('Info produto');
        $product->setPrice(1990);
        $product->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
        $product->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));

        $entityManager->persist($product);
        $entityManager->flush();
    }

    #[Route('/edit/{product}', name: 'edit_products', methods: 'GET')]
    public function edit($product, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($product);
    }

    #[Route('/update/{product}', name: 'update_products', methods: 'POST')]
    public function update($product, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($product);
        $product->setName('Produto Test Atualizado');
        $product->setSlug('produto-test-atualizado');

        $entityManager->flush();
    }

    #[Route('/remove/{product}', name: 'remove_products', methods: 'POST')]
    public function remove($product, EntityManagerInterface $entityManager): Response
    {
        $product = $entityManager->getRepository(Product::class)->find($product);
    }
}
