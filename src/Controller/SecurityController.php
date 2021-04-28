<?php

namespace App\Controller;

use App\Entity\Adherant;
use App\Entity\Admin;
use App\Entity\Client;
use App\Form\ResetPassType;
use App\Repository\AdherantRepository;
use App\Repository\AdminRepository;
use App\Repository\ClientRepository;
use Swift_Message;
use Symfony\Bridge\Monolog\Handler\SwiftMailerHandler;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Mailer\MailerInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/oubli-pass", name="app_forgotten_password")
     */
    public function oubliPass(Request $request,ClientRepository $clientRepository, \Swift_Mailer $mailer, TokenGeneratorInterface $tokenGenerator
        ,AdminRepository $adminRepository,AdherantRepository $adherantRepository): Response
    {
        // On initialise le formulaire
        $form = $this->createForm(ResetPassType::class);

        // On traite le formulaire
        $form->handleRequest($request);

        // Si le formulaire est valide
        if ($form->isSubmitted() && $form->isValid()) {
            // On récupère les données
            $donnees = $form->getData();

            // On cherche un utilisateur ayant cet e-mail
          //  $user = $clientRepository->findOneByEmail($donnees['email']);
            if ($clientRepository->findOneByEmail($donnees['email']))
            {
                $user=$clientRepository->findOneByEmail($donnees['email']);
            }elseif ($adminRepository->findOneByEmail($donnees['email']))
            {
                $user=$adminRepository->findOneByEmail($donnees['email']);
            }   else
            {
                $user=$adherantRepository->findOneByEmail($donnees['email']);
            }


            // Si l'utilisateur n'existe pas
            if ($user == null) {
                // On envoie une alerte disant que l'adresse e-mail est inconnue
                $this->addFlash('danger', 'Cette adresse e-mail est inconnue');

                // On retourne sur la page de connexion
                return $this->redirectToRoute('app_login');
            }

            // On génère un token
            $token = $tokenGenerator->generateToken();

            // On essaie d'écrire le token en base de données
            try{
                $user->setResetToken($token);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash('warning', $e->getMessage());
                return $this->redirectToRoute('app_login');
            }

            // On génère l'URL de réinitialisation de mot de passe
            $url = $this->generateUrl('app_reset_password', array('token' => $token),UrlGeneratorInterface::ABSOLUTE_URL);

            // On génère l'e-mail
            $message = (new Swift_Message('Mot de passe oublié'))
                ->setFrom('BenAzizasalah@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    "Bonjour,<br><br>Une demande de réinitialisation de mot de passe a été effectuée pour le site Sur-Terrain.tn. Veuillez cliquer sur le lien suivant : " . $url,
                    'text/html'
                )
            ;

            // On envoie l'e-mail
            $mailer->send($message);

            // On crée le message flash de confirmation
            $this->addFlash('message', 'E-mail de réinitialisation du mot de passe envoyé !');

            // On redirige vers la page de login
            return $this->redirectToRoute('app_login');
        }

        // On envoie le formulaire à la vue
        return $this->render('security/forgotten_password.html.twig',['emailForm' => $form->createView()]);
    }
    /**
     * @Route("/reset_pass/{token}", name="app_reset_password")
     */
    public function resetPassword(Request $request, string $token )
    {
        // On cherche un utilisateur avec le token donné
        //  $user = $this->getDoctrine()->getRepository(Client::class)->findOneBy(['reset_token' => $token]);
          if ($this->getDoctrine()->getRepository(Client::class)->findOneBy(['reset_token' => $token]))
          {
              $user = $this->getDoctrine()->getRepository(Client::class)->findOneBy(['reset_token' => $token]);
          }elseif ($this->getDoctrine()->getRepository(Admin::class)->findOneBy(['reset_token' => $token]))
          {
              $user=$this->getDoctrine()->getRepository(Admin::class)->findOneBy(['reset_token' => $token]);
          }else
          {
              $user=$this->getDoctrine()->getRepository(Adherant::class)->findOneBy(['reset_token' => $token]);
          }


        // Si l'utilisateur n'existe pas
        if ($user === null) {
            // On affiche une erreur
            $this->addFlash('danger', 'Token Inconnu');
            return $this->redirectToRoute('app_login');
        }

        // Si le formulaire est envoyé en méthode post
        if ($request->isMethod('POST')) {
            // On supprime le token
            $user->setResetToken(null);

            // On chiffre le mot de passe
            $user->setPassword($request->request->get('Password'));

            // On stocke
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // On crée le message flash
            $this->addFlash('message', 'Mot de passe mis à jour');

            // On redirige vers la page de connexion
            return $this->redirectToRoute('app_login');
        }else {
            // Si on n'a pas reçu les données, on affiche le formulaire
            return $this->render('security/ResetPass.html.twig', ['token' => $token]);
        }

    }





}
