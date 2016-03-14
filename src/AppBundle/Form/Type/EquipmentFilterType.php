<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
        
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\TextFilterType;        
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\BooleanFilterType;    
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\DateFilterType;
class EquipmentFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        
            ->add('name', TextFilterType::class)
            ->add('description', TextFilterType::class)
            ->add('isActive', BooleanFilterType::class)
            ->add('createdAt', DateFilterType::class)
            ->add('updatedAt', DateFilterType::class)
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'        => 'AppBundle\Entity\Equipment',
            'csrf_protection'   => false,
            'validation_groups' => array('filter'),
            'method'            => 'GET',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'equipment_filter';
    }
}
