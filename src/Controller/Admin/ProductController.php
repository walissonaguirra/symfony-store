<?php

namespace App\Controller\Admin;

use App\Entity\ProductPhoto;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use App\Service\UploadService;
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

    #[Route('/create', name: 'create_products')]
    public function create(Request $request, EntityManagerInterface $em, UploadService $uploadService): Response
    {
        $form = $this->createForm(ProductType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $product->setCreatedAt();
            $product->setUpdatedAt();

            $photos = $form['photos']->getData();

            if ($photos) {
                $photosUpdated = $uploadService->upload($photos, 'products');
                $photosUpdated = $this->makeProductPhotoEntoties($photosUpdated);
                $product->addManyProductPhoto($photosUpdated);
            }

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
    public function edit($product, EntityManagerInterface $em, ProductRepository $productRepository, Request $request, UploadService $uploadService): Response
    {
        $product = $productRepository->find($product);

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product = $form->getData();
            $product->setUpdatedAt();

            $photos = $form['photos']->getData();

            if ($photos) {
                $photosUpdated = $uploadService->upload($photos, 'products');
                $photosUpdated = $this->makeProductPhotoEntoties($photosUpdated);
                $product->addManyProductPhoto($photosUpdated);
            }

            $em->flush();

            $this->addFlash('success', 'Produto atualizado com sucesso!');

            return $this->redirectToRoute('admin_edit_products', ['product' => $product->getId()]);

        }

        return $this->render('admin/product/edit.html.twig', [
            'form' => $form->createView(),
            'productPhotos' => $product->getProductPhotos()
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

    /**
     * @param string[] $photosUpdated
     * @return ProductPhoto[]
     */
    private function makeProductPhotoEntoties(array $photosUpdated): array
    {
        $entities = [];

        foreach($photosUpdated as $photo) {
            $productPhoto = new ProductPhoto();
            $productPhoto->setPhoto($photo);
            $productPhoto->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_paulo')));
            $productPhoto->setUpdatedAt(new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_paulo')));
            $entities[] = $productPhoto;
        }

        return $entities;
    }
}
