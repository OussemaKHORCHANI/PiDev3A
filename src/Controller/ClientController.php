<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Client;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/client")
 */
class ClientController extends AbstractController
{

    /**
     * @Route("/", name="client_index", methods={"GET"})
     */
    public function index(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="client_new", methods={"GET","POST"})
     */
    public function new(Request $request,FlashyNotifier $flashy): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($client);
            $entityManager->flush();
            $flashy->success('Client Ajouté!', 'http://your-awesome-link.com');

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idc}", name="client_show", methods={"GET"})
     */
    public function show(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    /**
     * @Route("/{idc}/edit", name="client_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Client $client ,FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->warning('Client Modifié', 'http://your-awesome-link.com');

            return $this->redirectToRoute('client_index');
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{idc}", name="client_delete", methods={"POST"})
     */
    public function delete(Request $request, Client $client ,FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$client->getIdc(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($client);
            $entityManager->flush();
            $flashy->error('Client Supprimé', 'http://your-awesome-link.com');
        }

        return $this->redirectToRoute('client_index');
    }

   /**
   * @Route("/liste/json", name="liste_json")
    */
   public function liste_Json(SerializerInterface $serializer ,ClientRepository $adminRepository)
   {
       $admin=$adminRepository->findAll();

     $data=$serializer->serialize($admin,'json' ,['groups' => 'post:read']);

        $response =new Response($data,200,["content-type" =>"application/json"]);

       return  $response;
   }

   /**
     * @Route("/add/json", name="new_json" )
    */
     public function new_Json(Request $request, SerializerInterface $serializer ,EntityManagerInterface $entityManager)

        {
        $admin =new Client();
       // $content = $request->getContent();
       try {
            $admin->setNom($request->get("nom"));
            $admin->setPrenom($request->get("prenom"));
           $admin->setAddress($request->get("address"));
           $admin->setNumtelc($request->get("numtelc"));
            $admin->setEmail($request->get("email"));
           $admin->setMdp($request->get("mdp"));

           $entityManager->persist($admin);
            $entityManager->flush();
            $data= $serializer->normalize($admin,'json',['groups' => 'post:read']);
            return new Response("Client ajouté avec succés".json_encode($admin) );
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
       $admin=$entityManager->getRepository(Client::class)->find($request->get("idc"));

       $admin->setNom($request->get("nom"));
        $admin->setPrenom($request->get("prenom"));
       $admin->setAddress($request->get("address"));
        $admin->setNumtelc($request->get("numtelc"));
        $admin->setEmail($request->get("email"));


        $entityManager->flush();
        $data= $serializer->normalize($admin,'json',['groups' => 'post:read']);
        return new Response("Client modifié avec succés".json_encode($data) );
    }
    /**
     * @Route("/delete/json", name="delete_json" )
     */
    public function delete_Json(Request $request, SerializerInterface $serializer ,EntityManagerInterface $entityManager)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $admin=$this->getDoctrine()->getManager()->getRepository(Client::class)->find($request->get("idc"));
        if($admin!=null){
            $entityManager->remove($admin);
            $entityManager->flush();
            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Client supprimé !");
           return new JsonResponse($formatted);
        }
        return new JsonResponse("id du Client invalide !");


    }



}
