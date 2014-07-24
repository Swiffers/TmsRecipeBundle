<?php

namespace Tms\RecipeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RecipeType extends AbstractType
{
        /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, array(
                'label' => 'Nom de la recette'
            ))
            ->add('type', null, array(
                'label' => 'Type de plat'
            ))
            ->add('people', null, array(
                'label' => 'Nombre de personnes'
            ))
            ->add('preparationTime', null, array(
                'label' => 'Temps de préparation'
            ))
            ->add('cookingTime', null, array(
                'label' => 'Temps de cuisson'
            ))
            ->add('author', null, array(
                'label' => 'Auteur'
            ))
            ->add('ingredients', 'collection', array(
                'type'          => new IngredientType(),
                'allow_add'     => true,
                'by_reference'  => false,
                'label'         => 'Ingrédients'
            ))
            ->add('preparation', null, array(
                'label' => 'Préparation'
            ))
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Tms\RecipeBundle\Entity\Recipe'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tms_recipebundle_recipe';
    }
}
