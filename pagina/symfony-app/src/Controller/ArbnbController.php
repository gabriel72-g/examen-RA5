<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Alojamiento;
use App\Entity\Usuario;
use App\Entity\Alquiler;
use App\Form\FormAlojamientoType;

class ArbnbController extends AbstractController
{
    #[Route('/arbnb', name: 'app_arbnb')]
    public function index(): Response
    {
        return $this->render('arbnb/index.html.twig', [
            'titulo' => 'Bienvenido',
        ]);
    }

    #[Route('/arbnb/misalojamientos', name: 'app_mis_alojamientos')]
    public function misAlojamientos(Request $request, EntityManagerInterface $em): Response
    {
        //Creamos un objeto de alojamiento
        $alojamiento = new Alojamiento();
        //Creamos el formulario vasandonos en FormAlojamientoType y los datos introducidos se asignaran a $alojamiento
        $form = $this->createForm(FormAlojamientoType::class, $alojamiento);
        //Gestiona y actualiza el alojamiento con los datos introducidos
        $form->handleRequest($request);
        //Comprueba si el formulario se ha enviado y si es valido
        if ($form->isSubmitted() && $form->isValid()) {
            //Asocia el objeto alojamiento al usuario actual
            $alojamiento->setPropietario($this->getUser());
            //guarda el objeto alojamiento en la base de datos 
            $em->persist($alojamiento);
            $em->flush();
        }


        return $this->render('arbnb/misalojamientos.html.twig', [            
            'titulo' => 'Mis alojamientos',
            'form' =>$form
        ]);
    }

    #[Route('/arbnb/{id}/alquileresdelalojamiento', name: 'app_alquileres_del_alojamiento')]
    public function alquileresAlojamientos(Alojamiento $alojamiento): Response
    {
        return $this->render('arbnb/alquilerdelalojamiento.html.twig', [            
            'titulo' => 'Alquileres del alojamiento',
            'alojamiento' => $alojamiento
        ]);
    }

    

    #[Route('/arbnb/misalojamientosalquilados', name: 'app_mis_alojamientos_alquilados')]
    public function misAlojamientosAlquilados(): Response
    {
        return $this->render('arbnb/index.html.twig', [            
            'titulo' => 'Mis alojamientos Alquilados',
        ]);
    }

    #[Route('/arbnb/alquilar', name: 'app_alquilar')]
    public function alquilar(EntityManagerInterface $em): Response
    {
        // Leeemos toda las tabla alojamientos
        $casas = $em->getRepository(Alojamiento::class)->findAll();

        return $this->render('arbnb/alquilar.html.twig', [            
            'titulo' => 'Alquilar',
            'casas' => $casas
        ]);
    }
    //se le pasa por la url el id del alojamiento
    #[Route('/arbnb/{id}/realizaralquiler', name: 'app_realizar_alquiler')]
    public function realizarAlquiler(Alojamiento $alojamiento, EntityManagerInterface $em): Response
    {
        //Crea un objeto alquiler que crea una nueva instancia de Alquiler
        $alquiler = new Alquiler();
        //Se le asigna el usuario actual    
        $alquiler->setCliente($this->getUser());
        //Se le guarda el alojamiento en alquiler 
        $alquiler->setAlojamiento($alojamiento);
        //y por ultimo guarda todo en la tabla
        $em->persist($alquiler);
        $em->flush();

        return $this->render('arbnb/misalquileres.html.twig', [            
            'titulo' => 'Mis alquileres',
        ]);
    }

    

    
    #[Route('/arbnb/misalquileres', name: 'app_mis_alquileres')]
    public function misAlquileres(): Response
    {
        return $this->render('arbnb/misalquileres.html.twig', [            
            'titulo' => 'Mis alquileres',
        ]);
    }




}
