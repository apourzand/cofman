<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Billingclass;
use AppBundle\Form\Type\BillingclassType;
use AppBundle\Form\Type\BillingclassFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Billingclass controller.
 *
 * @Route("/admin/billingclass")
 */
class BillingclassController extends Controller
{
    /**
     * Lists all Billingclass entities.
     *
     * @Route("/", name="admin_billingclass")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(BillingclassFilterType::class);
        if (!is_null($response = $this->saveFilter($form, 'billingclass', 'admin_billingclass'))) {
            return $response;
        }
        $qb = $em->getRepository('AppBundle:Billingclass')->createQueryBuilder('b');
        $paginator = $this->filter($form, $qb, 'billingclass');
        
        return array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Billingclass entity.
     *
     * @Route("/{id}/show", name="admin_billingclass_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Billingclass $billingclass)
    {
        $deleteForm = $this->createDeleteForm($billingclass->getId(), 'admin_billingclass_delete');

        return array(
            'billingclass' => $billingclass,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Billingclass entity.
     *
     * @Route("/new", name="admin_billingclass_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $billingclass = new Billingclass();
        $form = $this->createForm(BillingclassType::class, $billingclass);

        return array(
            'billingclass' => $billingclass,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Billingclass entity.
     *
     * @Route("/create", name="admin_billingclass_create")
     * @Method("POST")
     * @Template("AppBundle:Billingclass:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $billingclass = new Billingclass();
        $form = $this->createForm(BillingclassType::class, $billingclass);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($billingclass);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_billingclass_show', array('id' => $billingclass->getId())));
        }

        return array(
            'billingclass' => $billingclass,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Billingclass entity.
     *
     * @Route("/{id}/edit", name="admin_billingclass_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Billingclass $billingclass)
    {
        $editForm = $this->createForm(BillingclassType::class, $billingclass, array(
            'action' => $this->generateUrl('admin_billingclass_update', array('id' => $billingclass->getId())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($billingclass->getId(), 'admin_billingclass_delete');

        return array(
            'billingclass' => $billingclass,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Billingclass entity.
     *
     * @Route("/{id}/update", name="admin_billingclass_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("AppBundle:Billingclass:edit.html.twig")
     */
    public function updateAction(Billingclass $billingclass, Request $request)
    {
        $editForm = $this->createForm(BillingclassType::class, $billingclass, array(
            'action' => $this->generateUrl('admin_billingclass_update', array('id' => $billingclass->getId())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('admin_billingclass_edit', array('id' => $billingclass->getId())));
        }
        $deleteForm = $this->createDeleteForm($billingclass->getId(), 'admin_billingclass_delete');

        return array(
            'billingclass' => $billingclass,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="admin_billingclass_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('billingclass', $field, $type);

        return $this->redirect($this->generateUrl('admin_billingclass'));
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
     * Deletes a Billingclass entity.
     *
     * @Route("/{id}/delete", name="admin_billingclass_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Billingclass $billingclass, Request $request)
    {
        $form = $this->createDeleteForm($billingclass->getId(), 'admin_billingclass_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($billingclass);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_billingclass'));
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
