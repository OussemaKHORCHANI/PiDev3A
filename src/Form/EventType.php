<?php

namespace App\Form;

use App\Entity\Event;
use App\Repository\EventRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EventType extends AbstractType
{
    private $eventRepository;
    public  function __construct( EventRepository $eventRepository)
    {
        $this->eventRepository=$eventRepository;

    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {


        $builder

            ->add('nom')
            ->add('description')
            ->add('lieuEvent')

            ->add('dateEvent', \Symfony\Component\Form\Extension\Core\Type\DateType::class, [
               'widget' => 'single_text',
               'required' => false,
               'empty_data' => '',
           ])

            ->add('prix')
            ->add('categoriesId',ChoiceType::class,[

                'multiple' => false,
                'choices' => $this->eventRepository->createQueryBuilder('c')->select('c.categoriesId')->getQuery()->getResult(),
                'choice_label' => function ($choice) {
                    return $choice;
                },])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
            'attr' => [
                'novalidate' => 'novalidate', // comment me to reactivate the html5 validation!  🚥
            ]
        ]);
    }

}
