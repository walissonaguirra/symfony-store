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

        return $this->render('index.html.twig', compact('name'));
    }

    #[Route('/produto/{slug}', name: 'product_single')]
    public function product(string $slug): Response
    {
        return $this->render('single.html.twig', compact('slug'));
    }
}
