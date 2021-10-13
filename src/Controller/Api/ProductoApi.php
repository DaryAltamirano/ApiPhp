<?php

namespace App\Controller\Api;

use App\Repository\ProductoRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;


class ProductoApi extends AbstractFOSRestController
{
    /**
     * @Rest\Get(path="/productos")
     * @Rest\View(serializerGroups={"productos"}, serializerEnableMaxDepthChecks=true)
     */
    public function getAction(ProductoRepository $productoRepository) {
        return $productoRepository->findAll();
    }
}

?>