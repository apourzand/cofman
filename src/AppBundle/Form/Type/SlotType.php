<?php
namespace AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SlotType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', ChoiceType::class, array(
                'choices'  => array(
                    '-- Select here --' => null,
                    '00:00' => '00:00:00',
                    '00:30' => '00:30:00',
                    '01:00' => '01:00:00',
                    '01:30' => '01:30:00',
                    '02:00' => '02:00:00',
                    '02:30' => '02:30:00',
                    '03:00' => '03:00:00',
                    '03:30' => '03:30:00',
                    '04:00' => '04:00:00',
                    '04:30' => '04:30:00',
                    '05:00' => '05:00:00',
                    '05:30' => '05:30:00',
                    '06:00' => '06:00:00',
                    '06:30' => '06:30:00',
                    '07:00' => '07:00:00',
                    '07:30' => '07:30:00',
                    '08:00' => '08:00:00',
                    '08:30' => '08:30:00',
                    '09:00' => '09:00:00',
                    '09:30' => '09:30:00',
                    '10:00' => '10:00:00',
                    '10:30' => '10:30:00',
                    '11:00' => '11:00:00',
                    '11:30' => '11:30:00',
                    '12:00' => '12:00:00',
                    '12:30' => '12:30:00',
                    '13:00' => '13:00:00',
                    '13:30' => '13:30:00',
                    '14:00' => '14:00:00',
                    '14:30' => '14:30:00',
                    '15:00' => '15:00:00',
                    '15:30' => '15:30:00',
                    '16:00' => '16:00:00',
                    '16:30' => '16:30:00',
                    '17:00' => '17:00:00',
                    '17:30' => '17:30:00',
                    '18:00' => '18:00:00',
                    '18:30' => '18:30:00',
                    '19:00' => '19:00:00',
                    '19:30' => '19:30:00',
                    '20:00' => '20:00:00',
                    '20:30' => '20:30:00',
                    '21:00' => '21:00:00',
                    '21:30' => '21:30:00',
                    '22:00' => '22:00:00',
                    '22:30' => '22:30:00',
                    '23:00' => '23:00:00',
                    '23:30' => '23:30:00',
                ),
            ))
            ->add('duration', ChoiceType::class, array(
                'choices'  => array(
                    '00h30' => '0.5',
                    '01h00' => '1',
                    '01h30' => '1.5',
                    '02h00' => '2',
                    '02h30' => '2.5',
                    '03h00' => '3',
                    '03h30' => '3.5',
                    '04h00' => '4',
                    '04h30' => '4.5',
                    '05h00' => '5',
                    '05h30' => '5.5',
                    '06h00' => '6',
                    '06h30' => '6.5',
                    '07h00' => '7',
                    '07h30' => '7.5',
                    '08h00' => '8',
                    '08h30' => '8.5',
                    '09h00' => '9',
                    '09h30' => '9.5',
                    '10h00' => '10',
                    '10h30' => '10.5',
                    '11h00' => '11',
                    '11h30' => '11.5',
                    '12h00' => '12',
                    '12h30' => '12.5',
                    '13h00' => '13',
                    '13h30' => '13.5',
                    '14h00' => '14',
                    '14h30' => '14.5',
                    '15h00' => '15',
                    '15h30' => '15.5',
                    '16h00' => '16',
                    '16h30' => '16.5',
                    '17h00' => '17',
                    '17h30' => '17.5',
                    '18h00' => '18',
                    '18h30' => '18.5',
                    '19h00' => '19',
                    '19h30' => '19.5',
                    '20h00' => '20',
                    '20h30' => '20.5',
                    '21h00' => '21',
                    '21h30' => '21.5',
                    '22h00' => '22',
                    '22h30' => '22.5',
                    '23h00' => '23',
                    '23h30' => '23.5',
                    '24h00' => '24',
                ),
                'attr' => array('class'=>'duration'),
            ))
        ;
    }


    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'attr'=> array('class'=>'form-inline'),
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'slot';
    }
}