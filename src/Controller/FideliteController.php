<?php

namespace App\Controller;

use App\Entity\Fidelite;
use App\Form\FideliteType;
use App\Repository\FideliteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/fidelite")
 */
class FideliteController extends AbstractController
{

    /**
     * @Route("/", name="fidelite_index", methods={"GET"})
     */
    public function index(FideliteRepository $fideliteRepository): Response
    {
        return $this->render('fidelite/index.html.twig', [
            'fidelites' => $fideliteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="fidelite_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $fidelite = new Fidelite();
        $form = $this->createForm(FideliteType::class, $fidelite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fidelite);
            $entityManager->flush();

            return $this->redirectToRoute('fidelite_index');
        }

        return $this->render('fidelite/new.html.twig', [
            'fidelite' => $fidelite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idfidelite}", name="fidelite_show", methods={"GET"})
     */
    public function show(Fidelite $fidelite): Response
    {
        return $this->render('fidelite/show.html.twig', [
            'fidelite' => $fidelite,
        ]);
    }

    /**
     * @Route("/{idfidelite}/edit", name="fidelite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Fidelite $fidelite): Response
    {
        $form = $this->createForm(FideliteType::class, $fidelite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('fidelite_index');
        }

        return $this->render('fidelite/edit.html.twig', [
            'fidelite' => $fidelite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idfidelite}", name="fidelite_delete", methods={"POST"})
     */
    public function delete(Request $request, Fidelite $fidelite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fidelite->getIdfidelite(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fidelite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('fidelite_index');
    }
}
