<?php

namespace App\Controller;

use App\Repository\TareaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    const elementos_por_pagina = 10;

   /**
     * @Route(
     *  "/{pagina}",
     *  name="app_index",
     *  defaults={
     *      "pagina": 1
     *  },
     *  requirements={
     *      "pagina"="\d+"
     *  },
     *  methods={
     *      "GET"
     *  }
     * )
     */
    
    public function index(int $pagina, TareaRepository $tareasRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('index/index.html.twig', [
            'tareas' => $tareasRepository->buscarTodas($pagina,self::elementos_por_pagina),
            'pagina' => $pagina,
        ]);
    }
}
