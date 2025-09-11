<?php

namespace App\Controller;

use App\Entity\Product;
use DateTimeZone;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $name = 'Walisson Aguirra';

        // Inserindo dados doctrine
        // $product = new Product();
        // $product->setName('Produto Test');
        // $product->setSlug('produto-test');
        // $product->setDescription('Descrição');
        // $product->setBady('Info produto');
        // $product->setPrice(1990);
        // $product->setCreatedAt(new \DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));
        // $product->setUpdatedAt(new \DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo')));

        // $entityManager->persist($product);
        // $entityManager->flush();

        // Atualizando dados doctrine
        $product = $entityManager->getRepository(Product::class)->find(1);
        $product->setName('Produto Test Atualizado');
        $product->setSlug('produto-test-atualizado');

        $entityManager->flush();

        return $this->render('index.html.twig', compact('name'));
    }

    #[Route('/produto/{slug}', name: 'product_single')]
    public function product(string $slug): Response
    {
        return $this->render('single.html.twig', compact('slug'));
    }
}
