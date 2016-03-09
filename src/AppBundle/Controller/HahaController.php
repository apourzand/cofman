<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @Route("/haha")
 */
class HahaController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        $data = array();
        $form = $this->createFormBuilder($data)
            ->add('task', TextType::class)
            ->add('dueDate', DateType::class)
            ->add('save', SubmitType::class, array(
                'label' => 'Create Task',
                'attr' => array(
                    'class' => 'btn-primary'
                )))
            ->getForm();

        return $this->render('haha/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
