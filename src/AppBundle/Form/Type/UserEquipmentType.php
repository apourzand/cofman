<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class UserEquipmentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('equipment', EntityType::class, array(
                'class' => 'AppBundle:Equipment',
                'label' => false,
                'placeholder' => 'Please choose an equipment', 
                'empty_data'  => null,
            ))
            ->add('accessprofile', EntityType::class, array(
                'class' => 'AppBundle:Accessprofile',
                'label' => false,
                'placeholder' => 'Please choose an access profile',
                'empty_data'  => null,
            ))
//            ->add('user')
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\UserEquipment',
            'attr'=> array('class'=>'form-inline'),
        ));
    }
}
