<?php

namespace App\Controller;

use App\Entity\Adherant;
use App\Form\AdherantType;
use App\Repository\AdherantRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/adherant")
 */
class AdherantController extends AbstractController
{

    /**
     * @Route("/", name="adherant_index", methods={"GET"})
     */
    public function index(AdherantRepository $adherantRepository): Response
    {
        return $this->render('adherant/index.html.twig', [
            'adherants' => $adherantRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="adherant_new", methods={"GET","POST"})
     */
    public function new(Request $request ,FlashyNotifier $flashy): Response
    {
        $adherant = new Adherant();
        $form = $this->createForm(AdherantType::class, $adherant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($adherant);
            $entityManager->flush();
            $flashy->success('Adherant Ajouté!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('adherant_index');
        }

        return $this->render('adherant/new.html.twig', [
            'adherant' => $adherant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{ida}", name="adherant_show", methods={"GET"})
     */
    public function show(Adherant $adherant): Response
    {
        return $this->render('adherant/show.html.twig', [
            'adherant' => $adherant,
        ]);
    }

    /**
     * @Route("/{ida}/edit", name="adherant_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Adherant $adherant,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(AdherantType::class, $adherant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->warning('Client Modifié', 'http://your-awesome-link.com');
            return $this->redirectToRoute('adherant_index');
        }

        return $this->render('adherant/edit.html.twig', [
            'adherant' => $adherant,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{ida}", name="adherant_delete", methods={"POST"})
     */
    public function delete(Request $request, Adherant $adherant,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$adherant->getIda(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($adherant);
            $entityManager->flush();
            $flashy->error('Admin Supprimé', 'http://your-awesome-link.com');
        }

        return $this->redirectToRoute('adherant_index');
    }

   /**
     * @Route("/liste/json")
     */
    public function liste_Json(SerializerInterface $serializer ,AdherantRepository $repository)
    {
        $adherant=$repository->findAll();

        $data=$serializer->serialize($adherant,'json' ,['groups' => 'post:read']);

        $response =new Response($data,200,["content-type" =>"application/json"]);

        return  $response;
    }
//
    /**
    * @Route("/add/json" )
     */
    public function new_Json(Request $request, SerializerInterface $serializer ,EntityManagerInterface $entityManager)
    {

        $adherant =new Adherant();
        // $content = $request->getContent();
        try {
           $adherant->setNom($request->get("nom"));
            $adherant->setPrenom($request->get("prenom"));
           $adherant->setCin($request->get("cin"));
           $adherant->setAddress($request->get("address"));
           $adherant->setNomterain($request->get("nomterain"));
           $adherant->setNumtel($request->get("setNumtel"));
            $adherant->setEmail($request->get("email"));
            $adherant->setMdp($request->get("mdp"));
           $adherant->setResetToken($request->get('reset_token'));

            $entityManager->persist($adherant);
           $entityManager->flush();
            $data= $serializer->normalize($adherant,'json',['groups' => 'post:read']);
           return new Response("Admin ajouté avec succés".json_encode($adherant) );
           //return $this->json('terrain ajouté avec succés!' ,201,['groups' => 'terrain']);

        }catch (NotEncodableValueException $e ){
           return $this->json(['status'=>400,'message'=> $e->getMessage()]);
       }

    }
    /**
     * @Route("/update/json",methods={"POST","GET"})
    */
    public function update_Json(Request $request, NormalizerInterface $serializer ,EntityManagerInterface $entityManager)
   {
        $entityManager = $this->getDoctrine()->getManager();
        $adherant=$entityManager->getRepository(Adherant::class)->find($request->get("ida"));

       $adherant->setNom($request->get("nom"));
       $adherant->setPrenom($request->get("prenom"));
       $adherant->setCin($request->get("cin"));
        $adherant->setAddress($request->get("address"));
        $adherant->setNomterain($request->get("nomterain"));
        $adherant->setNumtel($request->get("setNumtel"));
        $adherant->setEmail($request->get("email"));
        $adherant->setMdp($request->get("mdp"));
       $adherant->setResetToken($request->get('reset_token'));

        $entityManager->flush();
       $data= $serializer->normalize($adherant,'json',['groups' => 'post:read']);
       return new Response("Adherant modifié avec succés".json_encode($data) );
    }
    /**
    * @Route("/delete/json")
    */
   public function delete_Json(Request $request, SerializerInterface $serializer ,EntityManagerInterface $entityManager)
   {

       $entityManager = $this->getDoctrine()->getManager();
       $adherant=$this->getDoctrine()->getManager()->getRepository(Adherant::class)->find($request->get("ida"));
       if($adherant!=null){
            $entityManager->remove($adherant);
            $entityManager->flush();

           $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Adherant supprimé !");
            return new JsonResponse($formatted);
       }
       return new JsonResponse("id du Adherant invalide !");


    }

}
