<?php

namespace App\Controller;

use App\Entity\Joueurs;
use App\Entity\Club;
use App\Form\ClubType;
use App\Form\JoueursType;
use App\Repository\ClubRepository;
use App\Repository\JoueursRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\HttpFoundation\JsonResponse;


/**
 * @Route("/joueurs")
 */
class JoueursController extends AbstractController
{

    /**
     * @Route("/", name="joueurs_index", methods={"GET"})
     */
    public function index(JoueursRepository $joueursRepository): Response
    {
        return $this->render('joueurs/index.html.twig', [
            'joueurs' => $joueursRepository->findAll(),]);

    }


    /**
     * @Route("/new", name="joueurs_new", methods={"GET","POST"})
     */
    public function new(Request $request,FlashyNotifier $flashy): Response
    {

        $joueur = new Joueurs();
        $form = $this->createForm(JoueursType::class, $joueur);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($joueur);
            $entityManager->flush();

            $mail = new PHPMailer(true);
            try {

                $nom = $form->get('nom')->getData();
                $prenom = $form->get('prenom')->getData();
                $age = $form->get('age')->getData();
                $email = $form->get('email')->getData();
                $idClub = $form->get('idClub')->getData();


                //Server settings
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'issaouihamidou@gmail.com';             // SMTP username
                $mail->Password   = '2info2D3CGK';                               // SMTP password
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                //Recipients
                $mail->setFrom('issaouihamidou@gmail.com', 'Joueur');
                $mail->addAddress($email, 'user');     // Add a recipient
                // Content
                //
                $corps="Inscription terminer. Bienvenue monsieur :  <br>" .$nom. " ".$prenom."<br> ".$age." ans,<br> ".$email."<br> Inscrit dans : " .$idClub."";
                $mail->isHTML(true);                                  // Set email format to HTML
                $mail->Subject = 'Inscription dans un Club football!';
                $mail->Body = $corps;

                $mail->send();
                $this->addFlash('message','the email has been sent');

            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
            $flashy->success('Joueur ajoutée avec succes!');
            return $this->redirectToRoute('joueurs_index');
        }

        return $this->render('joueurs/new.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);
    }

        /**
         * @Route("/newjson")
         */
        public function newjson(Request $request,FlashyNotifier $flashy,NormalizerInterface $normalizer): Response
    {


        $joueur = new Joueurs();
        $form = $this->createForm(JoueursType::class, $joueur);
        $form->handleRequest($request);

        $joueur = new joueurs();
        $nom = $request->query->get("nom");
        $prenom = $request->query->get("prenom");
        $age = $request->query->get("age");
        $email = $request->query->get("email");
        $idClub=$request->query->get("idClub");



        $joueur->setNom($nom);
        $joueur->setPrenom($prenom);
        $joueur->setAge($age);
        $joueur->setEmail($email);
        $joueur->setIdClub($this->getDoctrine()->getRepository(Club::class)->find($idClub));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($joueur);
        $entityManager->flush();

        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($joueur);
        return new JsonResponse($formatted);

        }


    /**
     * @Route("/jor/{id}", name="joueurs_show", methods={"GET"})
     */
    public function show(Joueurs $joueur): Response
    {
        return $this->render('joueurs/show.html.twig', [
            'joueur' => $joueur,

        ]);

    }
    /**
     * @Route("/display", name="display_jr", methods={"GET"})
     */
    public function dispjoueur(): Response
    {
        $joueur=$this->getDoctrine()->getManager()->getRepository(Joueurs::class)->findAll();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted=$serializer->normalize($joueur);
        return new JsonResponse($formatted);
    }

    /**
     * @Route("/{id}/edit", name="joueurs_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Joueurs $joueur, FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(JoueursType::class, $joueur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('joueurs_index');
        }
        $flashy->info('Joueur modifier avec succes!');
        return $this->render('joueurs/edit.html.twig', [
            'joueur' => $joueur,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{id}", name="joueurs_delete", methods={"POST"})
     */
    public function delete(Request $request, Joueurs $joueur, FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete' . $joueur->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($joueur);
            $entityManager->flush();

            $flashy->error('Joueur supprimer!');
        }
        return $this->redirectToRoute('joueurs_index');
    }
    /**
     * @Route("/delete/json")
     */
    public function delete_Json(Request $request)
    {
        $joueur = new Joueurs();
        $id=$request->get("id");
        $entityManager = $this->getDoctrine()->getManager();
        $joueur=$entityManager->getRepository(Joueurs::class)->find($id);
        if($joueur!=null){
            $entityManager->remove($joueur);
            $entityManager->flush();

            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("joueur supprimer");
            return new JsonResponse($formatted);

        }
        return new JsonResponse("id de reservation est invalide !");


    }
     /**
      * @Route("/joueurs/tri", name="sortbyage",methods={"GET","POST"})
      */
    public function sortByage(JoueursRepository $joueursRepository): Response
    {

        $joueurs=$joueursRepository->sortByage();
        return $this->render('joueurs/index.html.twig', [
            'joueurs' => $joueurs,
        ]);
    }


}

