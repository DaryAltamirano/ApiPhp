<?php

namespace App\Controller;
use App\Repository\ProductoRepository;
use App\Entity\Producto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;


/**
 * @Route("/productos")
 */
class ProductoController extends AbstractController{

    /**
    * @Route("/", methods={"GET"})
     * 
     */

    public function list(Request $request,ProductoRepository $productoRepository){
        $Productos = $productoRepository->findAll();
        $productosArray = [];
        foreach ($Productos as $producto){
            $productosArray[]=[
                'id' => $producto ->getId(),
                'nombre'=>$producto -> getNombre(),
                'descripcion'=>$producto->getDescripcion(),
                'precio'=>$producto->getPrecio(),
            ];

        };
        $response = new JsonResponse();
        $response->setData([
            'succes' => true,
            'data' => $productosArray
        ]);
        return $response;
    }


     /**
    * @Route("/{id}", methods={"GET"})
     * 
     */

    public function show($id,Request $request,ProductoRepository $productoRepository){
        $producto = $productoRepository->find($id);
        $response = new JsonResponse();
        $response->setData([
            'succes' => true,
            'data' => [
                'id' => $producto ->getId(),
                'nombre'=>$producto -> getNombre(),
                'descripcion'=>$producto->getDescripcion(),
                'precio'=>$producto->getPrecio()
            ]
        ]);
        return $response;
    }





     /**
     *  @Route("/", methods={"POST"})
     */
    public function create(Request $request,ProductoRepository $productoRepository){
        $product = new Producto();

        $nombre = $request->get('nombre', null);
        $descripcion = $request->get('descripcion', null);
        $precio = $request->get('precio', null);
    
        $product->setNombre($nombre);
        $product->setDescripcion($descripcion);
        $product->setPrecio($precio);

        $em = $this->getDoctrine()->getManager();

        $em->persist($product);
        $em->flush();
        
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'data' => [
                'id' => $product ->getId(),
                'nombre'=>$product -> getNombre(),
                'descripcion'=>$product->getDescripcion(),
                'precio'=>$product->getPrecio(),
            ]
        ]);
      
    
        return $response;
    }

      /**
     *  @Route("/{id}", methods={"PUT"})
     */
    public function edit($id,Request $request,ProductoRepository $productoRepository){
        $em = $this->getDoctrine()->getManager();
        $producto = $productoRepository->find($id);
        $mensaje = 'producto actualizado';
        $sus = true;
        if (!$producto) {
            $sus=false;
            $mensaje='producto no encontrado';
        }else{          
            $nombre = $request->get('nombre', null);
            $descripcion = $request->get('descripcion', null);
            $precio = $request->get('precio', null);
            $producto->setNombre($nombre);
            $producto->setDescripcion($descripcion);
            $producto->setPrecio($precio);
            $em->flush();
        }
        $response = new JsonResponse();
        $response->setData([
            'success' => $sus,
            'mensaje' => $mensaje
        ]);
        return $response;
    }
    /**
     *  @Route("/{id}", methods={"DELETE"})
     */
    public function delete($id,Request $request,ProductoRepository $productoRepository){
        $em = $this->getDoctrine()->getManager();
        $producto = $productoRepository->find($id);
        $mensaje = 'producto eliminado';
        if (!$producto) {
            $sus=false;
            $mensaje='producto no encontrado';
        }else{
            $em->remove($producto);
            $em->flush();            
        }
        $response = new JsonResponse();
        $response->setData([
            'success' => true,
            'mensaje' => $mensaje
        ]);
        return $this->list($request,$productoRepository);
    }


}


?>