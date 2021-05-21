<?php

namespace App\Controller;
use App\Entity\Article;
use App\Entity\Categoriearticle;
use App\Form\ArticleType;
use App\Form\CategoriearticleType;
use App\Repository\ArticleRepository;
use MercurySeries\FlashyBundle\FlashyNotifier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Dompdf\Dompdf;
use Dompdf\Options;
use Swift_Mailer;
use Swift_Message;
use Knp\Component\Pager\PaginatorInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\BarChart;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPaginationInterface;
class ArticleController extends AbstractController
{
    /**
     * @Route("/article", name="article")
     */
    public function index(): Response
    {
        return $this->render('article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }

    /**
     * @Route("/listarticle", name="list")
     */
    public function list(Request $request,PaginatorInterface $paginator)
    {

        $entityManager = $this->getDoctrine();
        $allarticle = $entityManager->getRepository(Article::class)
            ->findAll();
        $list = $paginator->paginate(
            $allarticle,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('article/listarticle.html.twig', [
            'list' => $list
        ]);
    }
    /**
     * @Route("/listarticlepdf", name="listpdf")
     */
    public function listpdf(Request $request)
    {

        $entityManager = $this->getDoctrine();
        $list = $entityManager->getRepository(Article::class)
            ->findAll();


        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        //$pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        $html=$this->render('default/listpdf.html.twig', [
            'listpdf' => $list
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (force download)
        $dompdf->stream("default/listpdf.html.twig", [
            "Attachment" => false
        ]);


    }


    /**
     * @Route("/listarticlefront", name="listarticlefront")
     */
    public function listfront(Request $request,PaginatorInterface $paginator)
    {


        $entityManager = $this->getDoctrine();
        $alarticle = $entityManager->getRepository(Article::class)
            ->findAll();
        $list = $paginator->paginate(
            $alarticle,
            $request->query->getInt('page', 1),
            6
        );
        return $this->render('article/listarticlef.html.twig', [
            'list' => $list
        ]);
    }

    /**
     * @Route("/addArticle", name="addArticle", methods={"GET","POST"})
     */
    public function addArticle(Request $request,FlashyNotifier $flashy, Swift_Mailer $mailer)
    {
        $article= new Article();
        $form = $this->createForm(ArticleType::class,$article);
        $form->handleRequest($request);
        $images = $form->get('imageArticle')->getData();
        if ($form->isSubmitted() && $form->isValid() ){
            /*-------------------Début Image--------------------*/
            $file=$article->getImageArticle();
            $Filename = uniqid().'.'.$images->guessExtension();
            try {
                $file->move(
                    $this->getParameter('upload_directory'),
                    $Filename);
            }
            catch(FileException $e){
            }
            /*-------------------Fin Image--------------------*/
            $em = $this->getDoctrine()->getManager();
            $article->setImageArticle($Filename);

            $em->persist($article);
            $em->flush();

            $message = (new Swift_Message('Nouveau Article'))
                // On attribue l'expéditeur
                ->setFrom('no-reply@Surterrain.com')
                // On attribue le destinataire
                ->setTo('arwa.bejaoui@esprit.tn')
                // On crée le texte avec la vue
                ->setBody(
                    $this->renderView(
                        'article/notificationmail.html.twig', compact('article')
                    ),
                    'text/html'
                )
            ;
            //envoie le msg
            $mailer->send($message);

            $this->addFlash('message', 'Votre message a été transmis, nous vous répondrons dans les meilleurs délais.'); // Permet un message flash de renvoi

            $flashy->success('Article Ajoutée','http://your-awesome-link.com');

            return $this->redirectToRoute('list');
        }
        return $this->render("article/addarticle.html.twig", array("formarticle" => $form->createView()));
    }


    /**
     * @Route("/updateRate/{id_article}", name="updateRate")
     */
    public function updateRate($id_article, Request $request,PaginatorInterface $paginator)
    {
        $entityManager = $this->getDoctrine();

        //0.prépaper l'entity Manger
        $em = $this->getDoctrine()->getManager();
        $alarticle = $entityManager->getRepository(Article::class)
            ->findAll();
        //préparation de l'objet
       // $objet = $em->getRepository(Article::class)->find($id_article);
        $list = $paginator->paginate(
            $alarticle,
            $request->query->getInt('page', 1),
            8
        );
        $servername = "localhost";//Server Name
        $username = "root";//Server User Name
        $password = "";//Server Password
        $dbname = "surterrain";//Database Name
        ////Create DB Connection
        $conn = mysqli_connect($servername, $username, $password, $dbname);

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $rating = $_POST["rating"];

            $sql = "UPDATE  article set rate='$rating' where id_article= '$id_article' ";

            if (mysqli_query($conn, $sql)) {
                echo "New Rate addedddd successfully";
            } else {
                echo "Error: " . $sql . "<br>" . mysqli_error($conn);
            }
            mysqli_close($conn);
        }
        return $this->render('article/listarticlef.html.twig', [
            'list' => $list
        ]);
         }

    /**
     * @Route("/updatearticle/{id_article}", name="updatearticle")
     */
    public function updateArticle($id_article, Request $request,FlashyNotifier $flashy)
    {
        //0.prépaper l'entity Manger
        $em = $this->getDoctrine()->getManager();
        //préparation de l'objet
        $objet = $em->getRepository(Article::class)->find($id_article);
        //préparer le formulaire
        $form = $this->createForm(ArticleType::class, $objet);
        $form = $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $flashy->info('Article Modifiée','http://your-awesome-link.com');

            return $this->redirectToRoute("list");

        }
        //afficher le formulaire

        return $this->render("article/addarticle.html.twig", [
            'formarticle' => $form->createView()
        ]);
    }

    /**
     * @Route("/deletearticle/{id_article}",name="deletearticle")
     */
    public function deleteArticle($id_article,FlashyNotifier $flashy )
    {

        $article = $this->getDoctrine()->getRepository(Article::class)->find($id_article);

        $em = $this->getDoctrine()->getManager();
        $em->remove($article);

        $em->flush();
        $flashy->warning('Article Supprimée','http://your-awesome-link.com');

        return $this->redirectToRoute("list");

    }

    /**
     * @Route("/show/{id_article}",name="show")
     */
    public function show($id_article)
    {

        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($id_article);

        return $this->render('article/show.html.twig', [
            'article' => $article
        ]);
    }

    /**
     * @param ArticleRepository $repository
     * @Route("/triart", name="triart")
     */
    public function tri(ArticleRepository $repository, Request $request,PaginatorInterface $paginator)
    {
        $list = $repository->OrderByQt();
        $list2 = $paginator->paginate(
            $list,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('article/listarticle.html.twig', [
            'list' => $list2
        ]);
    }
    /**
     * @Route("/search ", name="search")
     */
    public function search(Request $request, ArticleRepository $repository,PaginatorInterface $paginator)
    {
        $libelle = $request->get('search');
        $list = $repository->findStudentByfield($libelle);
        $list1 = $paginator->paginate(
            $list,
            $request->query->getInt('page', 1),
            8
        );
        return $this->render('article/listarticle.html.twig', [
            'list' => $list1,
        ]);

    }
    /**
     * @Route("/searchVideo", name="searchVideo")
     */
    public function searchVideo(Request $request,ArticleRepository $article)
    {

        $requestString=$request->get('searchValue');
        $list = $article->a( $requestString);

        $data=array();


        return $this->redirectToRoute("list");



    }
    /**
     * @Route("/rechercheart", name="rechercheart")
     */
    public function recherche(ArticleRepository $repository , Request $request,PaginatorInterface $paginator)
    {

        $beginprix=$request->get('search1');
        $endprix=$request->get('search2');
        $article=$repository->findItemsCreatedBetweenTwoDates($beginprix,$endprix);
        $list1 = $paginator->paginate(
            $article,
            $request->query->getInt('page', 1),
            8
        );
        return $this->render('article/listarticlef.html.twig', [
            'list' => $list1
        ]);

    }

    /**
     * @Route("/statistiques",name="statistiques")
     */
    public function statistiques(ArticleRepository $repository)
    {

        $nbs = $repository->getART();
        $data = [['rate', 'Article']];
        foreach($nbs as $nb)
        {
            $data[] = array($nb['prixart'], $nb['art']);
        }
        $bar = new barchart();
        $bar->getData()->setArrayToDataTable(
            $data
        );

        $bar->getOptions()->getTitleTextStyle()->setColor('#07600');
        $bar->getOptions()->getTitleTextStyle()->setFontSize(50);
        return $this->render('article/statistique.html.twig', array('barchart' => $bar,'nbs' => $nbs));

    }

}