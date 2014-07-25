<?php

namespace Tms\RecipeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecipeUpdateType extends RecipeType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('save_and_validate', 'submit', array(
                'label' => 'Accepter la recette',
                'attr'  => array(
                    'class' => 'btn btn-success'
                )
            ))
            ->add('save_and_refuse', 'submit', array(
                'label' => 'Refuser la recette',
                'attr'  => array(
                    'class' => 'btn btn-danger'
                )
            ))
        ;
    }
}
