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
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCodeBundle\Response\QrCodeResponse;


class MainController extends AbstractController
{
    /**
     * @Route("/calendar", name="calendar_event", methods={"GET"})
     */
    public function calendar(EventRepository $eventRepository): Response
    { $events = $eventRepository->findAll();

        $rdvs = [];

        foreach($events as $event){
            $rdvs[] = [

                'dateEvent' => $event->getDateEvent()->format('Y-m-d H:i:s'),
                'nom' => $event->getNom(),
            ];
        }

        $data = json_encode($rdvs);
        return $this->render('main/index.html.twig', compact('data'));
    }
}