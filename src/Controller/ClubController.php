<?php

namespace App\Controller;

use App\Entity\Club;
use App\Form\ClubType;
use App\Entity\Joueurs;
use App\Repository\ClubRepository;
use App\Repository\JoueursRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;

/**
 * @Route("/club")
 */
class ClubController extends AbstractController
{
    /**
     * @Route("/", name="club_index", methods={"GET"})
     */
    public function index(ClubRepository $clubRepository): Response
    {
        return $this->render('club/index.html.twig', [
            'clubs' => $clubRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="club_new", methods={"GET","POST"})
     */
    public function new(Request $request, FlashyNotifier $flashy): Response
    {
        $club = new Club();
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($club);
            $entityManager->flush();
            $flashy->message('Club Ajouter!');
            return $this->redirectToRoute('club_index');
        }

        return $this->render('club/new.html.twig', [
            'club' => $club,
            'form' => $form->createView(),
        ]);
    }
//methods={"GET"} Joueurs $joueur,
    /**
     * @Route("/{idClub}", name="club_show", methods={"GET"})
     */
    public function show(JoueursRepository $Joueurs, Club $club): Response
    {
        return $this->render('club/show.html.twig', [
            'club' =>$club,
            'Joueurs' => $Joueurs->findAll(),
        ]);
    }

    /**
     * @Route("/{idClub}/edit", name="club_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Club $club,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(ClubType::class, $club);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->info('Club Modifier!');
            return $this->redirectToRoute('club_index');
        }

        return $this->render('club/edit.html.twig', [
            'club' => $club,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idClub}", name="club_delete", methods={"POST"})
     */
    public function delete(Request $request, Club $club, FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$club->getIdClub(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($club);
            $entityManager->flush();
            $flashy->error('Club Supprimer!');
        }

        return $this->redirectToRoute('club_index');
    }
}
