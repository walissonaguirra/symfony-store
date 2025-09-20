<?php

namespace App\Controller\Admin;

use App\Entity\ProductPhoto;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductPhotoController extends AbstractController
{
    #[Route('/admin/product/photo/{productPhoto}', name: 'admin_product_photo_remove')]
    public function remove(ProductPhoto $productPhoto, EntityManagerInterface $em): Response
    {
        $product = $productPhoto->getProduct()->getId();
        $realPhoto = $this->getparameter('upload_dir') . '/products/' . $productPhoto->getPhoto();

        if (file_exists($realPhoto)) {
            $photoIsRemoved = unlink($realPhoto);
        }

        if (isset($photoIsRemoved) && $photoIsRemoved || !isset($photoIsRemoved)) {
            $em->remove($productPhoto);
            $em->flush();
        }

        return $this->redirectToRoute('admin_edit_products', compact('product'));
    }
}
