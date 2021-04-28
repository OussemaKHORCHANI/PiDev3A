<?php

namespace App\Controller;

use App\Entity\Terrain;
use App\Form\TerrainType;
use App\Repository\TerrainRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;
use Dompdf\Dompdf;
use Dompdf\Options;
/**
 * @Route("/terrain")
 */
class TerrainController extends AbstractController
{
    /**
     * @Route("/", name="terrain_index", methods={"GET"})
     */
    public function index(TerrainRepository $terrainRepository,Request $request): Response
    {
        return $this->render('terrain/index.html.twig', [
            'terrains' => $terrainRepository->findAll(),
        ]);


    }



    /**
     * @Route("/new", name="terrain_new", methods={"GET","POST"})
     */
    public function new(Request $request,FlashyNotifier $flashy): Response
    {
        $terrain = new Terrain();
        $form = $this->createForm(TerrainType::class, $terrain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($terrain);
            $entityManager->flush();
            $flashy->success('Terrain ajouté avec succés', 'http://your-awesome-link.com');

            return $this->redirectToRoute('terrain_index');
        }

        return $this->render('terrain/new.html.twig', [
            'terrain' => $terrain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idterrain}", name="terrain_show", methods={"GET"})
     */
    public function show(Terrain $terrain): Response
    {
        return $this->render('terrain/show.html.twig', [
            'terrain' => $terrain,
        ]);
    }

    /**
     * @Route("/{idterrain}/edit", name="terrain_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Terrain $terrain , FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(TerrainType::class, $terrain);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->info('Terrain modifié!', 'http://your-awesome-link.com');
            return $this->redirectToRoute('terrain_index');

        }

        return $this->render('terrain/edit.html.twig', [
            'terrain' => $terrain,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idterrain}", name="terrain_delete", methods={"POST"})
     */
    public function delete(Request $request, Terrain $terrain,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$terrain->getIdterrain(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($terrain);
            $entityManager->flush();
            $flashy->error('Terrain supprimé !!', 'http://your-awesome-link.com');
        }

        return $this->redirectToRoute('terrain_index');
    }


    /**
     * @Route("/stats/chart", name="statistique")
     */
    public function stat(){

      /*  $repository = $this->getDoctrine()->getRepository(terrain::class);
        $etat ="disponible";
       $terrain = $repository->findOneBy(array('etat' => $etat));

       $this->getDoctrine()->getManager();

       // $repository = $this->getDoctrine()->getRepository(terrain::class);
        //$terrain = $repository->findAll();
        //$this->getDoctrine()->getManager();


        $etat1=0;
        $etat2=0;



        foreach ($terrain as $terrains)
        {
            if ( $terrain->getEtat()==$etat)  :

                $etat1+=1;
            else:

                $etat2+=1;


            endif;

        }

        $pieChart = new PieChart();
        $pieChart->getData()->setArrayToDataTable(
            [['etat', 'nombre'],
                ['disponible',  $etat1],
                ['réservé',  $etat2],
            ]
        );
        $pieChart->getOptions()->setTitle('Etat de terrain:');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#303030');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(false);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);*/

        $pieChart = new PieChart();



        $pieChart->getData()->setArrayToDataTable( array(
            ['etat', 'nombre'],
            ['disponible',     31.5],
            ['réservé',      68.5],
        ));

        $pieChart->getOptions()->setTitle('Etat des terrains:');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#07600');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(25);






        return $this->render('terrain/stat.html.twig', array('piechart' => $pieChart));
    }

    /**
     * @Route("/imprimer/pdf", name="imprimer_index")
     */
    public function pdf(TerrainRepository $TerrainRepository): Response

    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', "Gill Sans MT");

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $terrain = $TerrainRepository->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('terrain/pdf.html.twig', [
            'terrains' =>  $terrain,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Liste terrain.pdf", [
            "Attachment" => true
        ]);

    }
    /**
     * @Route("/map/map", name="map_index", methods={"GET"})
     */
    public function map(): Response
    {
        return $this->render('terrain/map.html.twig');


    }
}
