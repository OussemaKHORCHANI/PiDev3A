<?php


namespace App\Controller;


use App\Entity\Adherant;
use App\Entity\Admin;
use App\Entity\Client;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class UtilisateurApiController extends AbstractController
{

    /**
     * @Route("user/signup", name="app_register")
     */
    public function  signupAction(Request  $request, UserPasswordEncoderInterface $passwordEncoder) {

        $nom = $request->query->get("nom");
        $prenom = $request->query->get("prenom");
        $adresse= $request->query->get("address");
        $numtel = $request->query->get("numtelc");
        $email = $request->query->get("email");
        $password = $request->query->get("mdp");
       // $roles= $request->query->get("roles");
       // $dateNassiance = $request->query->get("dateNaissance");

        //control al email lazm @
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response("email invalid.");
        }
        $user = new Client();
        $user->setNom($nom);
        $user->setPrenom($prenom);
        $user->setAddress($adresse);
        $user->setNumtelc($numtel);
        $user->setEmail($email);
        $user->setPassword($password);
      //  $user->setIsVerified(true);//par défaut user lazm ykoun enabled.
      //  $user->setDateNaissance(new \DateTime($dateNassiance));
       // $user->setRoles(array($roles));//aleh array khater roles par defaut fi security  type ta3ha array

        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

                return new JsonResponse("success",200);//200 ya3ni http result ta3 server OK
        }catch (\Exception $ex) {
            return new Response("execption ".$ex->getMessage());
        }
    }


    /**
     * @Route("user/signin", name="app_login")
     */

    public function signinAction(Request $request) {
        $username = $request->query->get("email");
        $password = $request->query->get("mdp");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Admin::class)->findOneBy(['email'=>$username]);//bch nlawj ala user b username ta3o fi base s'il existe njibo
        //ken l9ito f base
       // if ($em->getRepository(Client::class)->findOneBy(['email'=>$username]))
      //  {
      //      $user = $em->getRepository(Client::class)->findOneBy(['email'=>$username]);
      //  }elseif ($em->getRepository(Admin::class)->findOneBy(['email'=>$username]))
      //  {
       //     $user=$em->getRepository(Admin::class)->findOneBy(['email'=>$username]);
      //  }else
      //  {
      //      $user=$em->getRepository(Adherant::class)->findOneBy(['email'=>$username]);
     //   }


        if($user){
            //lazm n9arn password zeda madamo crypté nesta3mlo password_verify
            if($password==$user->getPassword()) {
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
            }
            else {
                return new Response("passowrd not found");
            }
        }
        else {
            return new Response("failed");//ya3ni username/pass mch s7a7

        }
    }
    /**
     * @Route("user/client")
     */

    public function ActionClient(Request $request) {
        $username = $request->query->get("email");
        $password = $request->query->get("mdp");

        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository(Client::class)->findOneBy(['email'=>$username]);//bch nlawj ala user b username ta3o fi base s'il existe njibo
        //ken l9ito f base



        if($user){
            //lazm n9arn password zeda madamo crypté nesta3mlo password_verify
            if($password==$user->getPassword()) {
                $serializer = new Serializer([new ObjectNormalizer()]);
                $formatted = $serializer->normalize($user);
                return new JsonResponse($formatted);
            }
            else {
                return new Response("passowrd not found");
            }
        }
        else {
            return new Response("failed");//ya3ni username/pass mch s7a7

        }
    }


    /**
     * @Route("user/ediUser", name="app_gestion_profile")
     */

    public function editUser(Request $request, UserPasswordEncoderInterface $passwordEncoder) {
        $id = $request->get("id");//kima query->get wala get directement c la meme chose
        $username = $request->query->get("username");
        $password = $request->query->get("password");
        $email = $request->query->get("email");
        $em=$this->getDoctrine()->getManager();
        $user = $em->getRepository(Client::class)->find($id);
        //bon l modification bch na3mlouha bel image ya3ni kif tbadl profile ta3ik tzid image
        if($request->files->get("photo")!= null) {

            $file = $request->files->get("photo");//njib image fi url
            $fileName = $file->getClientOriginalName();//nom ta3ha

            //taw na5ouha w n7otaha fi dossier upload ely tet7t fih les images en principe te7t public folder
            $file->move(
                $fileName
            );
            $user->setPhoto($fileName);
        }


        $user->setUsername($username);
        $user->setPassword(
            $passwordEncoder->encodePassword(
                $user,
                $password
            )
        );

        $user->setEmail($email);
        $user->setIsVerified(true);//par défaut user lazm ykoun enabled.



        try {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return new JsonResponse("success",200);//200 ya3ni http result ta3 server OK
        }catch (\Exception $ex) {
            return new Response("fail ".$ex->getMessage());
        }

    }


    /**
     * @Route("user/getPasswordByEmail", name="app_password")
     */

    public function getPassswordByEmail(Request $request) {

        $email = $request->get('email');
        $es =$this->getDoctrine()->getManager();
       // $user = $this->getDoctrine()->getManager()->getRepository(Client::class)->findOneBy(['email'=>$email]);
        if ($es->getRepository(Client::class)->findOneBy(['email'=>$email]))
        {
            $user = $es->getRepository(Client::class)->findOneBy(['email'=>$email]);
        }elseif ($es->getRepository(Admin::class)->findOneBy(['email'=>$email]))
        {
            $user=$es->getRepository(Admin::class)->findOneBy(['email'=>$email]);
        }else
        {
            $user=$es->getRepository(Adherant::class)->findOneBy(['email'=>$email]);
        }
        if($user) {
            $password = $user->getPassword();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($password);
            return new JsonResponse($formatted);
        }
        return new Response("user not found");




    }
    /**
     * @Route("user/getEmailAdmin", name="app_password")
     */

    public function getEmail(Request $request) {

        $email = $request->get('email');
        $es =$this->getDoctrine()->getManager();
        // $user = $this->getDoctrine()->getManager()->getRepository(Client::class)->findOneBy(['email'=>$email]);
       $user= $es->getRepository(Admin::class)->findOneBy(['email'=>$email]);

        if($user) {
            $password = $user->getEmail();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($password);
            return new JsonResponse($formatted);
        }
        return new Response("user not found");

    }
    /**
     * @Route("user/getEmailClient")
     */

    public function getEmailClient(Request $request) {

        $email = $request->get('email');
        $es =$this->getDoctrine()->getManager();
        // $user = $this->getDoctrine()->getManager()->getRepository(Client::class)->findOneBy(['email'=>$email]);
        $user= $es->getRepository(Client::class)->findOneBy(['email'=>$email]);

        if($user) {
            $password = $user->getEmail();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($password);
            return new JsonResponse($formatted);
        }
        return new Response("user not found");

    }
}