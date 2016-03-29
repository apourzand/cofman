<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Role;
use AppBundle\Form\Type\RoleType;
use AppBundle\Form\Type\RoleFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Role controller.
 *
 * @Route("/admin/role")
 */
class RoleController extends Controller
{
    /**
     * Lists all Role entities.
     *
     * @Route("/", name="admin_role")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(RoleFilterType::class);
        if (!is_null($response = $this->saveFilter($form, 'role', 'admin_role'))) {
            return $response;
        }
        $qb = $em->getRepository('AppBundle:Role')->createQueryBuilder('r');
        $paginator = $this->filter($form, $qb, 'role');

        return array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Role entity.
     *
     * @Route("/{id}/show", name="admin_role_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Role $role)
    {
        $deleteForm = $this->createDeleteForm($role->getId(), 'admin_role_delete');

        return array(
            'role' => $role,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Role entity.
     *
     * @Route("/new", name="admin_role_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);

        return array(
            'role' => $role,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Role entity.
     *
     * @Route("/create", name="admin_role_create")
     * @Method("POST")
     * @Template("AppBundle:Role:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $role = new Role();
        $form = $this->createForm(RoleType::class, $role);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($role);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_role_show', array('id' => $role->getId())));
        }

        return array(
            'role' => $role,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Role entity.
     *
     * @Route("/{id}/edit", name="admin_role_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Role $role)
    {
        $editForm = $this->createForm(RoleType::class, $role, array(
            'action' => $this->generateUrl('admin_role_update', array('id' => $role->getId())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($role->getId(), 'admin_role_delete');

        return array(
            'role' => $role,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Role entity.
     *
     * @Route("/{id}/update", name="admin_role_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("AppBundle:Role:edit.html.twig")
     */
    public function updateAction(Role $role, Request $request)
    {
        $editForm = $this->createForm(RoleType::class, $role, array(
            'action' => $this->generateUrl('admin_role_update', array('id' => $role->getId())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('admin_role_edit', array('id' => $role->getId())));
        }
        $deleteForm = $this->createDeleteForm($role->getId(), 'admin_role_delete');

        return array(
            'role' => $role,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="admin_role_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('role', $field, $type);

        return $this->redirect($this->generateUrl('admin_role'));
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
        return $this->get('knp_paginator')->paginate($qb, $this->getRequest()->query->get('page', 1), $this->container->getParameter('knp_paginator.page_range'));
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
     * Deletes a Role entity.
     *
     * @Route("/{id}/delete", name="admin_role_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Role $role, Request $request)
    {
        $form = $this->createDeleteForm($role->getId(), 'admin_role_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($role);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_role'));
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
