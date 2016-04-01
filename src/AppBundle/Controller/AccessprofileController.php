<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Accessprofile;
use AppBundle\Form\Type\AccessprofileType;
use AppBundle\Form\Type\AccessprofileFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Accessprofile controller.
 *
 * @Route("/admin/accessprofile")
 */
class AccessprofileController extends Controller
{
    /**
     * Lists all Accessprofile entities.
     *
     * @Route("/", name="admin_accessprofile")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AccessprofileFilterType::class);
        if (!is_null($response = $this->saveFilter($form, 'accessprofile', 'admin_accessprofile'))) {
            return $response;
        }
        $qb = $em->getRepository('AppBundle:Accessprofile')->createQueryBuilder('a');
        $paginator = $this->filter($form, $qb, 'accessprofile');
        
        return array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Accessprofile entity.
     *
     * @Route("/{id}/show", name="admin_accessprofile_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Accessprofile $accessprofile)
    {
        $deleteForm = $this->createDeleteForm($accessprofile->getId(), 'admin_accessprofile_delete');

        return array(
            'accessprofile' => $accessprofile,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Accessprofile entity.
     *
     * @Route("/new", name="admin_accessprofile_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $accessprofile = new Accessprofile();
        $form = $this->createForm(AccessprofileType::class, $accessprofile);

        return array(
            'accessprofile' => $accessprofile,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Accessprofile entity.
     *
     * @Route("/create", name="admin_accessprofile_create")
     * @Method("POST")
     * @Template("AppBundle:Accessprofile:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $accessprofile = new Accessprofile();
        $form = $this->createForm(AccessprofileType::class, $accessprofile);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($accessprofile);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_accessprofile_show', array('id' => $accessprofile->getId())));
        }

        return array(
            'accessprofile' => $accessprofile,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Accessprofile entity.
     *
     * @Route("/{id}/edit", name="admin_accessprofile_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Accessprofile $accessprofile)
    {
        $editForm = $this->createForm(AccessprofileType::class, $accessprofile, array(
            'action' => $this->generateUrl('admin_accessprofile_update', array('id' => $accessprofile->getId())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($accessprofile->getId(), 'admin_accessprofile_delete');

        return array(
            'accessprofile' => $accessprofile,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Accessprofile entity.
     *
     * @Route("/{id}/update", name="admin_accessprofile_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("AppBundle:Accessprofile:edit.html.twig")
     */
    public function updateAction(Accessprofile $accessprofile, Request $request)
    {
        $editForm = $this->createForm(AccessprofileType::class, $accessprofile, array(
            'action' => $this->generateUrl('admin_accessprofile_update', array('id' => $accessprofile->getId())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('admin_accessprofile_edit', array('id' => $accessprofile->getId())));
        }
        $deleteForm = $this->createDeleteForm($accessprofile->getId(), 'admin_accessprofile_delete');

        return array(
            'accessprofile' => $accessprofile,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="admin_accessprofile_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('accessprofile', $field, $type);

        return $this->redirect($this->generateUrl('admin_accessprofile'));
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
     * Deletes a Accessprofile entity.
     *
     * @Route("/{id}/delete", name="admin_accessprofile_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Accessprofile $accessprofile, Request $request)
    {
        $form = $this->createDeleteForm($accessprofile->getId(), 'admin_accessprofile_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($accessprofile);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_accessprofile'));
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
