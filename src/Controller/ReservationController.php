<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Swift_Mailer;
use Swift_Message;

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
            /*$mail = new PHPMailer(true);
            try {

                //$nom = $form->get('idclient')->getData();


                /*$email = $form->get('emailadresse')->getData();*/

                //Server settings
                /*$mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'oussema.khorchani@esprit.tn';             // SMTP username
                $mail->Password   = '203JMT1891';                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('oussema.khorchani@esprit.tn', 'SurTerrain');
                $mail->addAddress('ousskh63@gmail.com');     // Add a recipient

                // Content
                $corps="Bonjour Monsieur/Madame votre réservation ajoutée avec succée " ;
                $mail->Subject = 'validation de réservation!';
                $mail->Body    = $corps;
                $mail->send();

            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }*/
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

            return $this->redirectToRoute('reservation_index');
            $flashy->info('Reservation modifiée!', 'http://your-awesome-link.com');
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
}
