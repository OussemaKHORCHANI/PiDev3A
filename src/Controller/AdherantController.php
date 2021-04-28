<?php

namespace App\Controller;

use App\Entity\Adherant;
use App\Form\AdherantType;
use App\Repository\AdherantRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/adherant")
 */
class AdherantController extends AbstractController
{

    /**
     * @Route("/", name="adherant_index", methods={"GET"})
     */
    public function index(AdherantRepository $adherantRepository): Response
    {
        return $this->render('adherant/index.html.twig', [
            'adherants' => $adherantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="adherant_new", methods={"GET","POST"})
     */
    public function new(Request $request ,FlashyNotifier $flashy): Response
    {
        $adherant = new Adherant();
        $form = $this->createForm(AdherantType::class, $adherant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adherant);
            $entityManager->flush();
            $flashy->success('Adherant Ajouté!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('adherant_index');
        }

        return $this->render('adherant/new.html.twig', [
            'adherant' => $adherant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{ida}", name="adherant_show", methods={"GET"})
     */
    public function show(Adherant $adherant): Response
    {
        return $this->render('adherant/show.html.twig', [
            'adherant' => $adherant,
        ]);
    }

    /**
     * @Route("/{ida}/edit", name="adherant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Adherant $adherant,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(AdherantType::class, $adherant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->warning('Client Modifié', 'http://your-awesome-link.com');
            return $this->redirectToRoute('adherant_index');
        }

        return $this->render('adherant/edit.html.twig', [
            'adherant' => $adherant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{ida}", name="adherant_delete", methods={"POST"})
     */
    public function delete(Request $request, Adherant $adherant,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adherant->getIda(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adherant);
            $entityManager->flush();
            $flashy->error('Admin Supprimé', 'http://your-awesome-link.com');
        }

        return $this->redirectToRoute('adherant_index');
    }
}
