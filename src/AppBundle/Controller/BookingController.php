<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Session;
use AppBundle\Entity\Booking;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\QueryBuilder;


/**
 * @Route("/booking")
 */
class BookingController extends Controller
{
    /**
     * @Route("/", name="booking_index")
     */
    public function indexAction(Request $request)
    {
        $session = $this->get('session');
        $user = $this->getUser();

        if ( !$user )
            return $this->redirectToRoute('login');

        $equipments = array();
        $profile = array();
        foreach ( $user->getUserEquipment() as $userEquipment )
        {
          $equipments[$userEquipment->getEquipment()->getName()] = $userEquipment->getEquipment()->getId();
          $accessprofile = $userEquipment->getAccessprofile();
          $profile[$userEquipment->getEquipment()->getId()] = array(
              'startTime' => $accessprofile->getStartTime()->format('H:i:s'),
              'endTime' => $accessprofile->getEndTime()->format('H:i:s'),
              'isWeekend' => $accessprofile->getIsWeekend(),
          );
        }

        $session->set('profile', $profile, null);
        //$this->get('app.utils')->dbg($profile);

        $form = $this->createFormBuilder(null, array('attr' => array('id' => 'booking_form')))
            ->setAction($this->generateUrl('booking_index'))
            ->add('equipment', ChoiceType::class, array(
                'choices' => $equipments,
                'placeholder' => 'Please choose an equipment',
                'empty_data'  => null,
                'data' => $session->get('equipmentId', null),
            ))
            ->add('startday', TextType::class, array(
                'data' => $session->get('startday', date('Y-m-d')),
                'attr' => array('readonly' => true),
            ))
            ->getForm();



        return $this->render('booking/index.html.twig', array(
            'form' => $form->createView(),
        ));
    }

   /**
     * @Route("/ajaxCalendar", name="ajax_calendar")
     */
    public function ajaxCalendarAction(Request $request)
    {
        $data = $request->request->all();
        $session = $this->get('session');
        // $this->get('app.utils')->dbg($data);
        $equipment = $this->getDoctrine()
            ->getRepository('AppBundle:Equipment')
            ->find($data['equipmentId']);

        $session->set('slots', $equipment->getSlots());
        $session->set('equipmentId', $data['equipmentId']);
        $session->set('startday', $data['startday']);

        $repository = $this->getDoctrine()
            ->getRepository('AppBundle:Booking');

        $startday = new \DateTime($session->get('startday'));
        $query = $repository->createQueryBuilder('b')
            ->andWhere('b.equipment = :equipment')
            ->andWhere('b.startsAt >= :starts_at')
            ->setParameters(array(
                'starts_at'    => $startday->format('Y-m-d H:i:s'),
                'equipment' => $equipment
            ))
            ->getQuery();

        $results = $query->getResult();

        $bookings = array();
        foreach ( $results as $result )
        {
          $bookings[$result->getStartsAt()->format('Y-m-d H:i:s')]['label'] = $result->getUser()->getUsername();
          $bookings[$result->getStartsAt()->format('Y-m-d H:i:s')]['id'] = $result->getId();
        }
 //$this->get('app.utils')->dbg($bookings);

        return $this->render('booking/booking.html.twig', array(
            'slots' => $session->get('slots', null),
            'startday' => $session->get('startday', date('yy-mm-dd')),
            'bookings' => $bookings,
        ));
     }

   /**
     * @Route("/ajaxBooking", name="ajax_booking")
     */
    public function ajaxBookingAction(Request $request)
    {
        $data = $request->request->all();
        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();

        if ( !array_key_exists('id', $data) )
        {
            $startsAt = new \DateTime($data['startsAt']);
            //$this->get('app.utils')->dbg($startsAt);
            $equipment = $this->getDoctrine()
                ->getRepository('AppBundle:Equipment')
                ->find($session->get('equipmentId'));

            $bookig = new Booking();
            $bookig->setUser($this->getUser());
            $bookig->setEquipment($equipment);
            $bookig->setStartsAt($startsAt);
            $bookig->setDuration($data['duration']);

            $em->persist($bookig);
        }
        else
        {
            $booking = $this->getDoctrine()
            ->getRepository('AppBundle:Booking')
            ->find($data['id']);

            $em->remove($booking);
        }

        $em->flush();

        return new JsonResponse(array('satus' => 'ok'));
     }
}
