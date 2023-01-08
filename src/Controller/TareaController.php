<?php

namespace App\Controller;

use App\Entity\Tarea;
use App\Form\TareaType;
use App\Repository\TareaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/tarea")
 */
class TareaController extends AbstractController
{
    /**
     * @Route("/", name="app_tarea_index", methods={"GET"})
     */
    public function index(TareaRepository $tareaRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('tarea/index.html.twig', [
            'tareas' => $tareaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_tarea_new", methods={"GET", "POST"})
     */
    public function new(Security $security, Request $request, EntityManagerInterface $entityManager): Response
    {

        $this->denyAccessUnlessGranted('ROLE_USER');

        $tarea = new Tarea();
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tarea->setUsuario($this->getUser());
            $entityManager->persist($tarea);
            $entityManager->flush();

            return $this->redirectToRoute('app_tarea_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tarea/new.html.twig', [
            'tarea' => $tarea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_tarea_show", methods={"GET"})
     */
    public function show(Tarea $tarea): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        return $this->render('tarea/show.html.twig', [
            'tarea' => $tarea,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_tarea_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Tarea $tarea, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $form = $this->createForm(TareaType::class, $tarea);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tarea_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tarea/edit.html.twig', [
            'tarea' => $tarea,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_tarea_delete", methods={"POST"})
     */
    public function delete(Request $request, Tarea $tarea, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->isCsrfTokenValid('delete'.$tarea->getId(), $request->request->get('_token'))) {
            $entityManager->remove($tarea);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tarea_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}", name="app_tarea_finalizar", methods={"POST"})
     */

    public function finalizar(Request $request, Tarea $tarea, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
            if($request->isXmlHttpRequest()) {
                $tarea->setFinalizada($tarea->getFinalizada());
                $entityManager->flush();

                return $this->json([
                    'finalizada' =>$tarea->getFinalizada()
                ]);
            }

            throw $this->createNotFoundException();
            
    }
}
