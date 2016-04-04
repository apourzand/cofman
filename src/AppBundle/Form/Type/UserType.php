<?php

namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;


class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('first_name')
            ->add('last_name')
            ->add('username')
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required' => false,
                'first_options'  => array('label' => 'Password', 'required' => false,),
                'second_options' => array('label' => 'Repeat Password', 'required' => false,),
            ))
            ->add('email')
            ->add('phone_number')
            ->add('isActive')
            ->add('userEquipment', CollectionType::class, array(
                'entry_type' => UserEquipmentType::class,
                'allow_add'    => true,
                'label_attr' => array('class'=>'label-collection'),
                'attr' => array('class'=>'collection'),
                'entry_options' => array('label' => false),
            ))
            ->add('roles')
            ->add('companies')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'user';
    }
}
