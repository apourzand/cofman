<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use AppBundle\Form\Type\SlotType;

class EquipmentType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('name')
            ->add('description')
            ->add('isActive')
            ->add('slots', CollectionType::class, array(
                'entry_type' => SlotType::class,
//                'prototype'    => true,
                'allow_add'    => true,
                'by_reference' => false,
                'allow_delete' => true,
                'label_attr' => array('class'=>'label-collection'),
                'attr' => array('class'=>'collection'),
                'entry_options' => array('label' => false),
            ))
//            ->add('createdAt')
//            ->add('updatedAt')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Equipment',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'equipment';
    }
}
