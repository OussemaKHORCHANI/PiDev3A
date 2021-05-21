<?php

namespace App\Form;

use App\Entity\Joueurs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JoueursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //commit
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('age')
            //->add('nomClub')
            ->add('email')
            ->add('idClub')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Joueurs::class,
            'attr' => [
                'novalidate' => 'novalidate', // comment me to reactivate the html5 validation!  🚥
            ]
        ]);
    }
}
