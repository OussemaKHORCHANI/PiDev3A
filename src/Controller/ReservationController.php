<?php

namespace App\Controller;

use Swift_Mailer;
use Dompdf\Dompdf;
use Swift_Message;
use Dompdf\Options;
use DateTimeInterface;
use App\Entity\Reservation;
use App\Entity\Terrain;
use App\Form\ReservationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ReservationRepository;
use Symfony\Component\HttpFoundation\Request;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * @Route("/reservation")
 */
class ReservationController extends AbstractController
{
   
    /**
     * @Route("/", name="reservation_index", methods={"GET"})
     */
    public function index(ReservationRepository $reservationRepository): Response
    {
        return $this->render('reservation/index.html.twig', [
            'reservations' => $reservationRepository->findAll(),
        ]);
    }

   

   

    /**
     * @Route("/new", name="reservation_new", methods={"GET","POST"})
     * @Route("/validation", name="validation", methods={"GET","POST"})
     */
    public function new(Request $request,FlashyNotifier $flashy ,Swift_Mailer $mailer): Response
    {
        $reservation = new Reservation();
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($reservation);
            $entityManager->flush();
            $flashy->info('réservation ajoutée avec succée', 'http://your-awesome-link.com');
           
            $corps="Bonjour Monsieur/Madame votre réservation ajoutée avec succée " ;
            $message = (new Swift_Message('Nouveau Article'))
                // On attribue l'expéditeur
                ->setFrom('no-reply@Surterrain.com')
                // On attribue le destinataire
                ->setTo('arwa.bejaoui@esprit.tn')
                // On crée le texte avec la vue

                ->setBody( $corps )
            ;
            //envoie le msg
            $mailer->send($message);

            $this->addFlash('message', 'Votre message a été transmis, nous vous répondrons dans les meilleurs délais.');
            return $this->render('reservation/validation.html.twig');



        }

        return $this->render('reservation/new.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);


    }

    /**
     * @Route("/{idres}", name="reservation_show", methods={"GET"})
     */
    public function show(Reservation $reservation): Response
    {
        return $this->render('reservation/show.html.twig', [
            'reservation' => $reservation,
        ]);
    }

    /**
     * @Route("/{idres}/edit", name="reservation_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Reservation $reservation, FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->info('Reservation modifiée!', 'http://your-awesome-link.com');
            return $this->redirectToRoute('reservation_index');

        }

        return $this->render('reservation/edit.html.twig', [
            'reservation' => $reservation,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idres}", name="reservation_delete", methods={"POST"})
     */
    public function delete(Request $request, Reservation $reservation,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reservation->getIdres(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($reservation);
            $entityManager->flush();
            $flashy->error('Reservation modifiée!', 'http://your-awesome-link.com');
        }

        return $this->redirectToRoute('reservation_index');
    }
    /**
     * @Route("/calendar/ok", name="calendar", methods={"GET"})
     */
    public function calendar(ReservationRepository $reservationRepository): Response
    {
        $res = $reservationRepository->findAll();

        $rdvs = [];

        foreach($res as $reservation){
            $rdvs[] = [

                'date' => $reservation->getDate()->format('Y-m-d'),

            ];
        }

        $data = json_encode($rdvs);


        return $this->render('reservation/calendar.html.twig',compact('data'));
    }
    /**
     * @Route("/imprimer/pdf", name="imprimer_index")
     */
    public function pdf(ReservationRepository $reservationRepository): Response

    {

        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', "Gill Sans MT");

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $reservation = $reservationRepository->findAll();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('reservation/pdf.html.twig', [
            'reservation' =>  $reservation,
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("Liste reservation.pdf", [
            "Attachment" => true
        ]);

    }


    /**
     * @Route("/liste/json", methods={"GET"})
     */
    public function liste_Json(SerializerInterface $serializer ,ReservationRepository $reservationRepository)
    {
        $reservation=$reservationRepository->findAll();

        $data=$serializer->serialize( $reservation,'json' ,['groups' => 'reservation']);
        $response =new Response($data,200,["content-type" =>"application/json"]);

        return  $response;



    }


     /**
     * @Route("/update/json", methods={"POST"})
     */
    public function update_Json(Request $request, SerializerInterface $serializer ,EntityManagerInterface $entityManager)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $reservation=$this->getDoctrine()->getManager()->getRepository(Reservation::class)->find($request->get("idres"));
        $reservation->setDate($request->get("date"));
        $reservation->setHeuredebut($request->get("heuredebut"));
        $reservation->setHeurefin($request->get("heurefin"));
        $reservation->setIdclient($request->get("idclient"));
        $reservation->setIdterrain($request->get("idterrain"));

       // $entityManager->persist($reservation);
        $entityManager->flush();
       // $data= $serializer->deserialize($reservation, Reservation::class,'json');
       // return new JsonResponse("Réservation modifiée avec succés !");
        $data= $serializer->normalize($terrain,'json',['groups' => 'terrain']);
        return new Response("terraain modifié avec succés".json_encode($data) );
    }


     /**
     * @Route("/delete/json")
     */
    public function delete_Json(Request $request, SerializerInterface $serializer ,EntityManagerInterface $entityManager)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $reservation=$this->getDoctrine()->getManager()->getRepository(Reservation::class)->find($request->get("idres"));
        if($reservation!=null){
            $entityManager->remove($reservation);
            $entityManager->flush();
           /*  $serializer->deserialize($reservation, Reservation::class,'json');
            return $this->json('Réservation supprimée !' ,201,['groups' => 'terrain']); */
            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("reservation  supprimee !");
            return new JsonResponse($formatted);
           
        }
        return new JsonResponse("id de reservation est invalide !");


    }

    /**
     * @Route("/add/json")
     */
    public function new_Json(Request $request)
    {

/* 
        $content = $request->getContent();
        
        try {
           
            
            $data = $serializer->deserialize($content, Reservation::class,'json');
            $data->setDate(new \DateTime());
            $data->setHeuredebut(new \DateTime());
            $data->setHeurefin(new \DateTime());
            
            $entityManager->persist($data);
            $entityManager->flush();
            return $this->json('Réservation ajoutée avec succées!' ,201,[],['groups' => 'reservation']);
           
          
        }catch (NotEncodableValueException $e ){
            return $this->json(['status'=>400,'message'=> $e->getMessage()]);
        } */

        
        $em = $this->getDoctrine()->getManager();
            $reservation = new Reservation();
            $reservation->setDate(new \DateTime());
            $reservation->setHeuredebut(new \DateTime());
            $reservation->setHeurefin(new \DateTime());
            $idterrain = $request->query->get("idterrain");
    
            $reservation->setIdterrain($this->getDoctrine()->getManager()->getRepository(Terrain::class)->find($idterrain));
            $em->persist($reservation);
            $em->flush();
            $encoder = new JsonEncoder();
            $normalizer = new ObjectNormalizer();
            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object;
            });
            $serializer = new Serializer([$normalizer], [$encoder]);
            $formatted = $serializer->normalize($reservation);
            return new JsonResponse($formatted);
    
        
    }


}
