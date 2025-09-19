<?php

namespace App\Controller\Admin;

use App\Form\ProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/products', name: 'admin_')]
final class ProductController extends AbstractController
{
    #[Route('/', name: 'index_products', methods: 'GET')]
    public function index(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('admin/product/index.html.twig', compact('products'));
    }

    #[Route('/upload')]
    public function upload(Request $request)
    {
        $photos = $request->files->get('photos');
        $upload_dir = $this->getParameter('upload_dir') . '/products';

        foreach ($photos as $photo) {
            $photoname = sha1($photo->getClientOriginalName()) . uniqid() . '.' .$photo->guessExtension();
            $photo->move($upload_dir, $photoname);
        }

        return new Response('Upload');
    }

    #[Route('/create', name: 'create_products')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(ProductType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $product->setCreatedAt();
            $product->setUpdatedAt();

            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Produto criado com sucesso!');

            return $this->redirectToRoute('admin_index_products');
        }

        return $this->render('admin/product/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/edit/{product}', name: 'edit_products')]
    public function edit($product, EntityManagerInterface $em, ProductRepository $productRepository, Request $request): Response
    {
        $product = $productRepository->find($product);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();

            $product->setUpdatedAt();

            $em->flush();

            $this->addFlash('success', 'Produto atualizado com sucesso!');

            return $this->redirectToRoute('admin_edit_products', ['product' => $product->getId()]);

        }

        return $this->render('admin/product/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/remove/{product}', name: 'remove_products', methods: 'GET')]
    public function remove($product, EntityManagerInterface $em, ProductRepository $productRepository): Response
    {
        try {
            $product = $productRepository->find($product);
            $em->remove($product);
            $em->flush();

            $this->addFlash('success', 'Produto apagado com sucesso!');

            return $this->redirectToRoute('admin_index_products');
        } catch (\Exception $e) {
            die($e->getMessage());
        }
    }
}
