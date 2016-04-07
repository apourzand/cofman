<?php

namespace Glifery\EntityHiddenTypeBundle\Form\Type;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Glifery\EntityHiddenTypeBundle\Form\DataTransformer\ObjectToIdTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class EntityHiddenType extends AbstractType
{
    /**
     * @var ManagerRegistry
     */
    protected $registry;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new ObjectToIdTransformer($this->registry, $options['em'], $options['class'], $options['property']);
        $builder->addModelTransformer($transformer);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function configureOptions (OptionsResolver $resolver)
    {
        $resolver
            ->setRequired(array('class'))
            ->setDefaults(array(
                    'data_class' => null,
                    'invalid_message' => 'The entity does not exist.',
                    'property' => 'id',
                    'em' => 'default'
                ))
            ->setAllowedTypes('invalid_message', array('null', 'string'))
            ->setAllowedTypes('property', array('null', 'string'))
            ->setAllowedTypes('em', array('null', 'string', 'Doctrine\Common\Persistence\ObjectManager'))
//            ->setAllowedTypes(array(
//                    'invalid_message' => array('null', 'string'),
//                    'property' => array('null', 'string'),
//                    'em' => array('null', 'string', 'Doctrine\Common\Persistence\ObjectManager'),
//                ))
        ; 
    }

    public function getParent()
    {
        return HiddenType::class;
    }

    public function getName()
    {
        return 'entity_hidden';
    }
}