<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Entity\Club;
use App\Form\CompetitionType;
use App\Repository\ClubcompRepository;
use App\Repository\CompetitionRepository;
use App\Repository\ClubRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;

/**
 * @Route("/competition")
 */
class CompetitionController extends AbstractController
{
    /**
     * @Route("/", name="competition_index", methods={"GET"})
     */
    public function index(CompetitionRepository $competitionRepository): Response
    {
        return $this->render('competition/index.html.twig', [
            'competitions' => $competitionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/pdf",name="pdf1")
     */
    public function topdf(CompetitionRepository $repo)
    {   $competition = $repo->findAll();
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('competition/mypdf.html.twig', [

            'competition' => $competition

        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }
    /**
     * @Route("/new", name="competition_new", methods={"GET","POST"})
     */
    public function new(Request $request, FlashyNotifier $flashy): Response
    {
        $competition = new Competition();
        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($competition);
            $entityManager->flush();
            $flashy->success('Competition Ajouter!');
            return $this->redirectToRoute('competition_index');
        }

        return $this->render('competition/new.html.twig', [
            'competition' => $competition,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idCompetition}", name="competition_show", methods={"GET"})
     */
    public function show(ClubcompRepository $clubcomp, Competition $competition): Response
    {
        return $this->render('competition/show.html.twig', [
            'clubcomp' => $clubcomp->findAll(),
            'competition' => $competition,

        ]);
    }

    /**
     * @Route("/{idCompetition}/edit", name="competition_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Competition $competition, FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(CompetitionType::class, $competition);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->info('Competition modifier!');
            return $this->redirectToRoute('competition_index');
        }

        return $this->render('competition/edit.html.twig', [
            'competition' => $competition,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idCompetition}", name="competition_delete", methods={"POST"})
     */
    public function delete(Request $request, Competition $competition, FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$competition->getIdCompetition(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($competition);
            $entityManager->flush();
            $flashy->error('Competition Supprimer!');
        }

        return $this->redirectToRoute('competition_index');
    }

}

