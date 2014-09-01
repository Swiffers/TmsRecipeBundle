<?php

namespace Tms\Bundle\RecipeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecipeCreateType extends RecipeType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('save', 'submit', array(
                'label' => 'Proposer la recette',
                'attr'  => array(
                    'class' => 'btn btn-success'
                )
            ))
        ;
    }
}
