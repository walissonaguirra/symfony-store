<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class DefaultController extends AbstractController
{
    #[Route('/', name: 'app_default')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $user->setFirstName('Walisson');
        $user->setLastName('Aguirra');
        $user->setEmail('walissonaguirra@proton.me');
        $user->setPassword('senha123');
        $user->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));
        $user->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo')));

        $entityManager->persist($user);
        $entityManager->flush();

        $address = new Address();
        $address->setAddress('Rua 1');
        $address->setNeightborhood('Bairro 1');
        $address->setNumber(1234);
        $address->setCity('São Paulo');
        $address->setState('SP');
        $address->setZipcode('123456-000');

        $address->setUser($user);

        $entityManager->persist($address);
        $entityManager->flush();


        $name = 'Walisson Aguirra';

        return $this->render('index.html.twig', compact('name'));
    }

    #[Route('/produto/{slug}', name: 'product_single')]
    public function product(string $slug): Response
    {
        return $this->render('single.html.twig', compact('slug'));
    }
}
