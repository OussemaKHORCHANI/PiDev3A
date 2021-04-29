<?php

namespace App\Controller;

use App\Entity\Categoriearticle;
use App\Form\ArticleType;
use App\Form\CategoriearticleType;
use App\Repository\ArticleRepository;
use App\Repository\CategoriearticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;

class CategoriearticleController extends AbstractController
{
    /**
     * @Route("/categoriearticle", name="categoriearticle")
     */
    public function index(): Response
    {
        return $this->render('categoriearticle/index.html.twig', [
            'controller_name' => 'CategoriearticleController',
        ]);
    }
    /**
     * @Route("/listcat", name="listcat")
     */
    public function list(){
        $entityManager= $this->getDoctrine();
        $list= $entityManager->getRepository(Categoriearticle::class)
            ->findAll();
        return $this->render('categoriearticle/listcategorie.html.twig',[
            'list'=>$list
        ]);
    }


    /**
     * @Route("/listcategoriearticle", name="listcategoriearticle")
     */
    public function listfront(){
        $entityManager= $this->getDoctrine();
        $list= $entityManager->getRepository(Categoriearticle::class)
            ->findAll();
        return $this->render('categoriearticle/listcategorie.html.twig',[
            'list'=>$list
        ]);
    }
    /**
     * @Route("/addCategorie", name="addCategorie")
     */
    public function addCateg(Request $request){
        $categoriearticle= new Categoriearticle();
        $form = $this->createForm(CategoriearticleType::class,$categoriearticle);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid() ){
            $em = $this->getDoctrine()->getManager();
            $em->persist($categoriearticle);
            $em->flush();
            return $this->redirectToRoute('listcat');
        }
        return $this->render("categoriearticle/addcategorie.html.twig",array("formcategorie"=>$form->createView()));
    }


    /**
     * @Route("/updatecateg/{idcat}", name="updatecateg")
     */
    public function updateCateg($idcat, Request $request){
        //0.prépaper l'entity Manger
        $em= $this->getDoctrine()->getManager();
        //préparation de l'objet
        $objet= $em->getRepository(Categoriearticle::class)->find($idcat);
        //préparer le formulaire
        $form= $this->createForm(CategoriearticleType::class,$objet);
        $form= $form->handleRequest($request);
        if($form->isSubmitted() ){
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute("listcat");
        }
        //afficher le formulaire

        return $this->render("categoriearticle/addcategorie.html.twig",array("formcategorie"=>$form->createView()));


    }
    /**
     * @Route("/deletecateg/{idcat}",name="deletecateg")
     */
    public function deleteArticle($idcat){

        $categ = $this->getDoctrine()->getRepository(Categoriearticle::class)->find($idcat);

        $em = $this->getDoctrine()->getManager();
        $em->remove($categ);
        $em->flush();
        return $this->redirectToRoute("listcat");
    }

    /**
     * @param CategoriearticleRepository $repository
     * @Route("/tricat", name="tricat")
     */
    public function tri(CategoriearticleRepository $repository, Request $request)
    {
        $list = $repository->OrderByQt();
        return $this->render('categoriearticle/listcategorie.html.twig', [
            'list' => $list
        ]);
    }
    /**
     * @Route("/searchcat ", name="searchcat")
     */
    public function search(Request $request, ArticleRepository $repository)
    {
        $nomcat = $request->get('searchcat');
        $list = $repository->findStudentByfield($nomcat);
        return $this->render('categoriearticle/listcategorie.html.twig', [
            'list' => $list,
        ]);

    }
}
