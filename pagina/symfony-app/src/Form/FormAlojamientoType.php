<?php

namespace App\Form;

use App\Entity\Alojamiento;
use App\Entity\Usuario;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FormAlojamientoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('descripcion')
            /*->add('propietario', EntityType::class, [
                'class' => Usuario::class,
                'choice_label' => 'id',
            ])*/
            ->add('agregar', SubmitType::class, ['label' => 'Añadir alojamiento'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Alojamiento::class,
        ]);
    }
}
