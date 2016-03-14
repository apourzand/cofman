<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Equipment;
use AppBundle\Form\Type\EquipmentType;
use AppBundle\Form\Type\EquipmentFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Equipment controller.
 *
 * @Route("/admin/equipment")
 */
class EquipmentController extends Controller
{
    /**
     * Lists all Equipment entities.
     *
     * @Route("/", name="admin_equipment")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(EquipmentFilterType::class);
        if (!is_null($response = $this->saveFilter($form, 'equipment', 'admin_equipment'))) {
            return $response;
        }
        $qb = $em->getRepository('AppBundle:Equipment')->createQueryBuilder('e');
        $paginator = $this->filter($form, $qb, 'equipment');
        
        return array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Equipment entity.
     *
     * @Route("/{id}/show", name="admin_equipment_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Equipment $equipment)
    {
        $deleteForm = $this->createDeleteForm($equipment->getId(), 'admin_equipment_delete');

        return array(
            'equipment' => $equipment,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Equipment entity.
     *
     * @Route("/new", name="admin_equipment_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $equipment = new Equipment();
        $form = $this->createForm(EquipmentType::class, $equipment);

        return array(
            'equipment' => $equipment,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Equipment entity.
     *
     * @Route("/create", name="admin_equipment_create")
     * @Method("POST")
     * @Template("AppBundle:Equipment:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $equipment = new Equipment();
        $form = $this->createForm(EquipmentType::class, $equipment);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($equipment);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_equipment_show', array('id' => $equipment->getId())));
        }

        return array(
            'equipment' => $equipment,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Equipment entity.
     *
     * @Route("/{id}/edit", name="admin_equipment_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Equipment $equipment)
    {
        $editForm = $this->createForm(EquipmentType::class, $equipment, array(
            'action' => $this->generateUrl('admin_equipment_update', array('id' => $equipment->getId())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($equipment->getId(), 'admin_equipment_delete');

        return array(
            'equipment' => $equipment,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Equipment entity.
     *
     * @Route("/{id}/update", name="admin_equipment_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("AppBundle:Equipment:edit.html.twig")
     */
    public function updateAction(Equipment $equipment, Request $request)
    {
        $editForm = $this->createForm(EquipmentType::class, $equipment, array(
            'action' => $this->generateUrl('admin_equipment_update', array('id' => $equipment->getId())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('admin_equipment_edit', array('id' => $equipment->getId())));
        }
        $deleteForm = $this->createDeleteForm($equipment->getId(), 'admin_equipment_delete');

        return array(
            'equipment' => $equipment,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="admin_equipment_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('equipment', $field, $type);

        return $this->redirect($this->generateUrl('admin_equipment'));
    }

    /**
     * @param string $name  session name
     * @param string $field field name
     * @param string $type  sort type ("ASC"/"DESC")
     */
    protected function setOrder($name, $field, $type = 'ASC')
    {
        $this->getRequest()->getSession()->set('sort.' . $name, array('field' => $field, 'type' => $type));
    }

    /**
     * @param  string $name
     * @return array
     */
    protected function getOrder($name)
    {
        $session = $this->getRequest()->getSession();

        return $session->has('sort.' . $name) ? $session->get('sort.' . $name) : null;
    }

    /**
     * @param QueryBuilder $qb
     * @param string       $name
     */
    protected function addQueryBuilderSort(QueryBuilder $qb, $name)
    {
        $alias = current($qb->getDQLPart('from'))->getAlias();
        if (is_array($order = $this->getOrder($name))) {
            $qb->orderBy($alias . '.' . $order['field'], $order['type']);
        }
    }

    /**
     * Save filters
     *
     * @param  FormInterface $form
     * @param  string        $name   route/entity name
     * @param  string        $route  route name, if different from entity name
     * @param  array         $params possible route parameters
     * @return Response
     */
    protected function saveFilter(FormInterface $form, $name, $route = null, array $params = null)
    {
        $request = $this->getRequest();
        $url = $this->generateUrl($route ?: $name, is_null($params) ? array() : $params);
        if ($request->query->has('submit-filter') && $form->handleRequest($request)->isValid()) {
            $request->getSession()->set('filter.' . $name, $request->query->get($form->getName()));

            return $this->redirect($url);
        } elseif ($request->query->has('reset-filter')) {
            $request->getSession()->set('filter.' . $name, null);

            return $this->redirect($url);
        }
    }

    /**
     * Filter form
     *
     * @param  FormInterface                                       $form
     * @param  QueryBuilder                                        $qb
     * @param  string                                              $name
     * @return \Knp\Component\Pager\Pagination\PaginationInterface
     */
    protected function filter(FormInterface $form, QueryBuilder $qb, $name)
    {
        if (!is_null($values = $this->getFilter($name))) {
            if ($form->submit($values)->isValid()) {
                $this->get('lexik_form_filter.query_builder_updater')->addFilterConditions($form, $qb);
            }
        }

        // possible sorting
        $this->addQueryBuilderSort($qb, $name);
        return $this->get('knp_paginator')->paginate($qb, $this->getRequest()->query->get('page', 1), 20);
    }

    /**
     * Get filters from session
     *
     * @param  string $name
     * @return array
     */
    protected function getFilter($name)
    {
        return $this->getRequest()->getSession()->get('filter.' . $name);
    }

    /**
     * Deletes a Equipment entity.
     *
     * @Route("/{id}/delete", name="admin_equipment_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Equipment $equipment, Request $request)
    {
        $form = $this->createDeleteForm($equipment->getId(), 'admin_equipment_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($equipment);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_equipment'));
    }

    /**
     * Create Delete form
     *
     * @param integer                       $id
     * @param string                        $route
     * @return \Symfony\Component\Form\Form
     */
    protected function createDeleteForm($id, $route)
    {
        return $this->createFormBuilder(null, array('attr' => array('id' => 'delete')))
            ->setAction($this->generateUrl($route, array('id' => $id)))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }

    protected function getRequest()
    {
        return $this->container->get('request_stack')->getCurrentRequest();
    }

}
