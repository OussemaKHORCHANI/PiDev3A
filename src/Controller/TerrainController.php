<?php

namespace App\Controller;

use App\Entity\Terrain;
use App\Form\TerrainType;
use App\Repository\TerrainRepository;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;



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
    public function stat(TerrainRepository $terrainRepository){


        /*$pieChart->getOptions()->setTitle('Etat de terrain:');
        $pieChart->getOptions()->setHeight(500);
        $pieChart->getOptions()->setWidth(900);
        $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#303030');
        $pieChart->getOptions()->getTitleTextStyle()->setItalic(false);
        $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);*/
        $nbs = $terrainRepository->countEtat();
        $data = [['e', 'stade']];
        foreach( (array)$nbs as $nb)
        {
            $data[] = array($nb['e'], $nb['stade']);
        }





        $bar = new barchart();
        $bar->getData()->setArrayToDataTable(
            $data
        );

        $bar->getOptions()->setTitle('Etat des terrains:');
        $bar->getOptions()->setHeight(500);
        $bar->getOptions()->setWidth(900);
        $bar->getOptions()->getTitleTextStyle()->setColor('#07600');
        $bar->getOptions()->getTitleTextStyle()->setFontSize(25);






        return $this->render('terrain/stat.html.twig', array('barchart' => $bar,'nbs' => $nbs));
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
    /**
     * @Route("/liste/json", name="liste_json")
     */
    public function liste_Json(SerializerInterface $serializer ,TerrainRepository $terrainRepository)
    {
        $terrain=$terrainRepository->findAll();

         $data=$serializer->serialize($terrain,'json' ,['groups' => 'terrain']);

         $response =new Response($data,200,["content-type" =>"application/json"]);

            return  $response;
    }

    /**
     * @Route("/add/json", name="new_json" )
     */
    public function new_Json(Request $request, SerializerInterface $serializer ,EntityManagerInterface $entityManager)
    {

        $terrain =new Terrain();
       // $content = $request->getContent();
        try {
        $terrain->setNomterrain($request->get("nomterrain"));
        $terrain->setAdresse($request->get("adresse"));
        $terrain->setEtat($request->get("etat"));
        $terrain->setDescription($request->get("description"));
        $terrain->setPhoto($request->get("photo"));
        
        $entityManager->persist($terrain);
        $entityManager->flush();
        $data= $serializer->normalize($terrain,'json',['groups' => 'terrain']);
        return new Response("terraain ajouté avec succés".json_encode($terrain) );
        //return $this->json('terrain ajouté avec succés!' ,201,['groups' => 'terrain']);

        }catch (NotEncodableValueException $e ){
            return $this->json(['status'=>400,'message'=> $e->getMessage()]);
        }

    }
    /**
     * @Route("/update/json", name="update_json",methods={"POST","GET"})
     */
    public function update_Json(Request $request, NormalizerInterface $serializer ,EntityManagerInterface $entityManager)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $terrain=$entityManager->getRepository(Terrain::class)->find($request->get("idterrain"));

        $terrain->setNomterrain($request->get("nomterrain"));
        $terrain->setAdresse($request->get("adresse"));
        $terrain->setEtat($request->get("etat"));
        $terrain->setDescription($request->get("description"));
        $terrain->setPhoto($request->get("photo"));
        $entityManager->flush();
        $data= $serializer->normalize($terrain,'json',['groups' => 'terrain']);
        return new Response("terraain modifié avec succés".json_encode($data) );
    }
    /**
     * @Route("/delete/json", name="delete_json" )
     */
    public function delete_Json(Request $request, SerializerInterface $serializer ,EntityManagerInterface $entityManager)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $terrain=$this->getDoctrine()->getManager()->getRepository(Terrain::class)->find($request->get("idterrain"));
        if($terrain!=null){
            $entityManager->remove($terrain);
            $entityManager->flush();
//            $serializer->deserialize($terrain, Terrain::class,'json');
//            return $this->json('json' ,201,['groups' => 'terrain']);
            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("terrain  supprimé !");
            return new JsonResponse($formatted);
        }
        return new JsonResponse("id du terrain invalide !");


    }



}
