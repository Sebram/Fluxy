<?php
// src/Fluxy/Form/FluxyCsvType.php

namespace App\Fluxy\Form;


use App\Entity\Csv;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class FluxycsvType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ...
            ->add('name', FileType::class, array('label' => 'Fichier (Csv) :'))
            // ...
        ;
    }
    /*
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => FluxyCsvType::class,
        ));
    }*/
}
