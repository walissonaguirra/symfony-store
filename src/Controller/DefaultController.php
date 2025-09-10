<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(): Response
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
