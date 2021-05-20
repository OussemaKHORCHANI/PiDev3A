<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods={"GET"})
     */
    public function index(AdminRepository $adminRepository): Response
    {
        return $this->render('admin/index.html.twig', [
            'admins' => $adminRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="admin_new", methods={"GET","POST"})
     */
    public function new(Request $request , FlashyNotifier $flashy): Response
    {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();
            $flashy->success('Admin Ajouté!', 'http://your-awesome-link.com');
            return $this->redirectToRoute('admin_index');

        }

        return $this->render('admin/new.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{id}", name="admin_show", methods={"GET"})
     */
    public function show(Admin $admin , FlashyNotifier $flashy): Response
    {

        return $this->render('admin/show.html.twig', [
            'admin' => $admin,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="admin_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Admin $admin , FlashyNotifier $flashy): Response
    {
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $flashy->warning('Admin Modifié', 'http://your-awesome-link.com');
            return $this->redirectToRoute('admin_index');

        }

        return $this->render('admin/edit.html.twig', [
            'admin' => $admin,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="admin_delete", methods={"POST"})
     */
    public function delete(Request $request, Admin $admin , FlashyNotifier $flashy): Response
    {
        if ($this->isCsrfTokenValid('delete'.$admin->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($admin);
            $entityManager->flush();
            $flashy->error('Admin Supprimé', 'http://your-awesome-link.com');
        }

        return $this->redirectToRoute('admin_index');
    }
    /**
     *@Route("/pdf/ok",name="pdf_index", methods={"GET"})
     */
    public function listu1(AdminRepository $adminRepository): Response
    {
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);
        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('admin/pdf.html.twig', [
            'admins' =>$adminRepository->findAll(),


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
     * @Route("/liste/json")
     */
    public function liste_Json(SerializerInterface $serializer ,AdminRepository $adminRepository)
    {
        $admin=$adminRepository->findAll();

        $data=$serializer->serialize($admin,'json' ,['groups' => 'post:read']);

        $response =new Response($data,200,["content-type" =>"application/json"]);

        return  $response;
    }

    /**
     * @Route("/add/json")
     */
    public function new_Json(Request $request, SerializerInterface $serializer ,EntityManagerInterface $entityManager)
    {

        $admin =new Admin();
        // $content = $request->getContent();
        try {
            $admin->setNom($request->get("nom"));
            $admin->setPrenom($request->get("prenom"));
            $admin->setUsername($request->get("username"));
            $admin->setEmail($request->get("email"));
            $admin->setMdp($request->get("mdp"));

            $entityManager->persist($admin);
            $entityManager->flush();
            $data= $serializer->normalize($admin,'json',['groups' => 'post:read']);
            return new Response("Admin ajouté avec succés".json_encode($admin) );
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
        $admin=$entityManager->getRepository(Admin::class)->find($request->get("id"));

        $admin->setNom($request->get("nom"));
        $admin->setPrenom($request->get("prenom"));
        $admin->setUsername($request->get("username"));
        $admin->setEmail($request->get("email"));

        $entityManager->flush();
        $data= $serializer->normalize($admin,'json',['groups' => 'post:read']);
        return new Response("Admin modifié avec succés".json_encode($data) );
    }
    /**
     * @Route("/delete/json")
     */
    public function delete_Json(Request $request, SerializerInterface $serializer ,EntityManagerInterface $entityManager)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $admin=$this->getDoctrine()->getManager()->getRepository(Admin::class)->find($request->get("id"));
        if($admin!=null){
            $entityManager->remove($admin);
            $entityManager->flush();
            $serialize = new Serializer([new ObjectNormalizer()]);
            $formatted = $serialize->normalize("Admin supprimé !");
            return new JsonResponse($formatted);
        }
        return new JsonResponse("id du Admin invalide !");


    }

    /**
     * @Route("admin/getPasswordByEmail", name="app_password")
     */

    public function getPassswordByEmail(Request $request) {

        $email = $request->get('email');
        $user = $this->getDoctrine()->getManager()->getRepository(Admin::class)->findOneBy(['email'=>$email]);
        if($user) {
            $password = $user->getPassword();
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($password);
            return new JsonResponse($formatted);
        }
        return new Response("user not found");

    }




}
