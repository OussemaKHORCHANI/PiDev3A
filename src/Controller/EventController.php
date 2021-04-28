<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\CategorieRepository;
use App\Repository\EventRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use phpDocumentor\Reflection\DocBlock\Serializer;
use Symfony\Component\Routing\Loader\XmlFileLoader;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
/**
 * @Route("/event")
 */
class EventController extends AbstractController
{
    /**
     * @Route("/", name="event_index", methods={"GET"})
     */
    public function index(EventRepository $eventRepository,CategorieRepository $categorieRepository,FlashyNotifier $flashy): Response
    { // $events=$eventRepository->getDoctrine()->getRepository(Event::class);
        $events=$eventRepository->findAll();
        for($i =0 ; $i < $eventRepository->createQueryBuilder('u')->select('count(u)')->getQuery()->getSingleScalarResult();$i++)
        {

            $events[$i]->setType($categorieRepository->createQueryBuilder('c')->select('c.type')->where('c.idCategorie = '.$events[$i]->getCategoriesId().'')->getQuery()->getSingleScalarResult());

        }

        return $this->render('event/index.html.twig', [
            'events' => $events,

        ]);
    }

    function filterwords($text){
        $filterWords = array('fuck','pute','bitch','hello');
        $filterCount = sizeof($filterWords);
        for ($i = 0; $i < $filterCount; $i++) {
            $text = preg_replace_callback('/\b' . $filterWords[$i] . '\b/i', function($matches){return str_repeat('*', strlen($matches[0]));}, $text);
        }
        return $text;
    }

    /**
     * @Route("/new", name="event_new", methods={"GET","POST"})
     */
    public function new(Request $request ,FlashyNotifier $flashy): Response
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $event->setDescription($this->filterwords($event->getDescription()));

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($event);
            $entityManager->flush();
             $flashy->success('Event created!', 'http://your-awesome-link.com');

            $mail = new PHPMailer(true);

            try {

                $nom = $form->get('nom')->getData();


                /*$email = $form->get('emailadresse')->getData();*/

                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'souissieya2018@gmail.com';             // SMTP username
                $mail->Password   = 'Youyou2020';                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('souissieya2018@gmail.com', 'Sur terrain');
                $mail->addAddress('maram.benameur@esprit.tn', ' Nouveau Evenement');     // Add a recipient

                // Content
                $corps="Bonjour Monsieur/Madame notre nouveau evenement ".$nom . " soyez les bienvenues" ;
                $mail->Subject = 'Nouveau Evenement!';
                $mail->Body    = $corps;

                $mail->send();

            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }

            //end mailing
            $flashy->success('Event created!', 'http://your-awesome-link.com');
            return $this->redirectToRoute('event_index');
        }

        return $this->render('event/new.html.twig', [
            'event' => $event,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{id}", name="event_show", methods={"GET"})
     */
    public function show(Event $event,FlashyNotifier $flashy): Response
    { $flashy->success('Event created!', 'http://your-awesome-link.com');
        $flashy->success('Event removed!', 'http://your-awesome-link.com');
        $flashy->success('Event changed!', 'http://your-awesome-link.com');
        return $this->render('event/show.html.twig', [
            'event' => $event,
        ]);
    }
    /**
     * @Route("/mara/ok", name="event_front")
     */
    public function show2(Event $event,FlashyNotifier $flashy): Response
    {
        return $this->render('event/front.html.twig', [
            'event' => $event,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="event_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Event $event,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->success('Event changed!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('event_index');

        }
        $flashy->success('Event changed!', 'http://your-awesome-link.com');
        return $this->render('event/edit.html.twig', [
            'event' => $event,
            'form' => $form->createView(),

        ]);

    }

    /**
     * @Route("/{id}", name="event_delete", methods={"POST"})
     */
    public function delete(Request $request, Event $event,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$event->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($event);
            $entityManager->flush();
            $flashy->success('Event removed!', 'http://your-awesome-link.com') ;
        }
        $flashy->success('Event removed!', 'http://your-awesome-link.com');
        return $this->redirectToRoute('event_index');

    }
   /* public function KidsBikesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Event');
        $query = $em->createQueryBuilder()
            ->select('v')->from('AppBundle:Event','v')
            ->where('v.nom = :nom ')

            ->setParameter('nom','2')
            ->getQuery();

        $Events = $query->getResult();
        return $this->render('../template/show.html.twig', array('Event' => $Events));
    }
    public function searchAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $requestString = $request->get('q');
        $produits =  $em->getRepository('AppBundle:Produit')->findEntitiesByString($requestString);
        if(!$produits) {
            $result['produits']['error'] = "produit Not found :( ";
        } else {
            $result['produits'] = $this->getRealEntities($produits);
        }
        return new Response(json_encode($result));
    }*/

    /**
     *@Route("/pdf/ok",name="pdf_index", methods={"GET"})
     */
    public function listu1(EventRepository $eventRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('event/pdf.html.twig', [
            'events' =>$eventRepository->findAll(),


        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A2', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();
        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }

    /**
     * @Route("/trie/nom", name="sortbytitleasc")
     */
    public function sortByTitleASC(EventRepository $eventRepository,CategorieRepository $categorieRepository,FlashyNotifier $flash): Response
    {

        $events=$eventRepository->sortByTitleASC();
        // $events=$eventRepository->findAll();

        for($i =0 ; $i < $eventRepository->createQueryBuilder('u')->select('count(u)')->getQuery()->getSingleScalarResult();$i++)
        {

            $events[$i]->setType($categorieRepository->createQueryBuilder('c')->select('c.type')->where('c.idCategorie = '.$events[$i]->getCategoriesId().'')->getQuery()->getSingleScalarResult());

        }


        return $this->render('event/index.html.twig', [
            'events' => $events,
        ]);
    }
    public function index1(EventRepository $calendar)
    {
        $events = $calendar->findAll();

        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [
                'id' => $event->getId(),
                'nom' => $event->getNom(),
                'dateEvent' => $event->getDateEvent()->format('Y-m-d H:i:s'),
                'type' => $event->getType(),
                'description' => $event->getDescription(),
                'lieuEvent' => $event->getLieuEvent(),
                'prix' => $event->getPrix()
            ];
        }

        $data = json_encode($rdvs);

        return $this->render('main/index.html.twig', compact('data'));
    }

}
