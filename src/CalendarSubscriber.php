<?php

namespace App;

// ...
use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use CalendarBundle\CalendarEvents;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $reservationrepository;
    private $router;

    public function __construct(
        ReservationRepository $reservationrepository,
        UrlGeneratorInterface $router
    ) {
        $this->reservationrepository = $reservationrepository;
        $this->router = $router;
    }

    // ...
    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(ReservationRepository $reservationRepository)
    {
        $reservationRepository->getDate();
       // $calendar->getHeureDebut();
       // $calendar->getHeureFin();
        //$filters = $reservation->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
       /* $reservations = $this->Reservationepository
            ->createQueryBuilder('reservation')
            //->where('reservation.heuredebut BETWEEN :start and :end OR reservation.heurefin BETWEEN :start and :end')
            ->setParameter('date', $date->format('Y-m-d '))
            ->setParameter('start', $start->format(' H:i:s'))
            ->setParameter('end', $end->format(' H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($reservations as $reservation) {
            // this create the events with your data (here booking data) to fill calendar
            $reservationEvent = new Reservation(
                $reservation->getDate(),
                $reservation->getHeuredebut(),
                $reservation->getHeureFin() // If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts


            $reservationEvent->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ]);
            $reservationEvent->addOption(
                'url',
                $this->router->generate('booking_show', [
                    'id' => $reservation->getIdres(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $reservation->addEvent($reservationEvent);
        }*/
    }


}