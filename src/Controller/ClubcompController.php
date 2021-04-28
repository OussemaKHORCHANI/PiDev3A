<?php

namespace App\Controller;

use App\Entity\Clubcomp;
use App\Form\ClubcompType;
use App\Repository\ClubcompRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/clubcomp")
 */
class ClubcompController extends AbstractController
{
    /**
     * @Route("/", name="clubcomp_index", methods={"GET"})
     */
    public function index(ClubcompRepository $clubcompRepository): Response
    {
        return $this->render('clubcomp/index.html.twig', [
            'clubcomps' => $clubcompRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="clubcomp_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $clubcomp = new Clubcomp();
        $form = $this->createForm(ClubcompType::class, $clubcomp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($clubcomp);
            $entityManager->flush();

            return $this->redirectToRoute('clubcomp_index');
        }

        return $this->render('clubcomp/new.html.twig', [
            'clubcomp' => $clubcomp,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idclubcomp}", name="clubcomp_show", methods={"GET"})
     */
    public function show(Clubcomp $clubcomp): Response
    {
        return $this->render('clubcomp/show.html.twig', [
            'clubcomp' => $clubcomp,
        ]);
    }

    /**
     * @Route("/{idclubcomp}/edit", name="clubcomp_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Clubcomp $clubcomp): Response
    {
        $form = $this->createForm(ClubcompType::class, $clubcomp);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('clubcomp_index');
        }

        return $this->render('clubcomp/edit.html.twig', [
            'clubcomp' => $clubcomp,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idclubcomp}", name="clubcomp_delete", methods={"POST"})
     */
    public function delete(Request $request, Clubcomp $clubcomp): Response
    {
        if ($this->isCsrfTokenValid('delete'.$clubcomp->getIdclubcomp(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($clubcomp);
            $entityManager->flush();
        }

        return $this->redirectToRoute('clubcomp_index');
    }
}
