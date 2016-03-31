<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Company;
use AppBundle\Form\Type\CompanyType;
use AppBundle\Form\Type\CompanyFilterType;
use Symfony\Component\Form\FormInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Company controller.
 *
 * @Route("/admin/company")
 */
class CompanyController extends Controller
{
    /**
     * Lists all Company entities.
     *
     * @Route("/", name="admin_company")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(CompanyFilterType::class);
        if (!is_null($response = $this->saveFilter($form, 'company', 'admin_company'))) {
            return $response;
        }
        $qb = $em->getRepository('AppBundle:Company')->createQueryBuilder('c');
        $paginator = $this->filter($form, $qb, 'company');
        
        return array(
            'form'      => $form->createView(),
            'paginator' => $paginator,
        );
    }

    /**
     * Finds and displays a Company entity.
     *
     * @Route("/{id}/show", name="admin_company_show", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function showAction(Company $company)
    {
        $deleteForm = $this->createDeleteForm($company->getId(), 'admin_company_delete');

        return array(
            'company' => $company,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Company entity.
     *
     * @Route("/new", name="admin_company_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);

        return array(
            'company' => $company,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Company entity.
     *
     * @Route("/create", name="admin_company_create")
     * @Method("POST")
     * @Template("AppBundle:Company:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company);
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($company);
            $em->flush();

            return $this->redirect($this->generateUrl('admin_company_show', array('id' => $company->getId())));
        }

        return array(
            'company' => $company,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Company entity.
     *
     * @Route("/{id}/edit", name="admin_company_edit", requirements={"id"="\d+"})
     * @Method("GET")
     * @Template()
     */
    public function editAction(Company $company)
    {
        $editForm = $this->createForm(CompanyType::class, $company, array(
            'action' => $this->generateUrl('admin_company_update', array('id' => $company->getId())),
            'method' => 'PUT',
        ));
        $deleteForm = $this->createDeleteForm($company->getId(), 'admin_company_delete');

        return array(
            'company' => $company,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Company entity.
     *
     * @Route("/{id}/update", name="admin_company_update", requirements={"id"="\d+"})
     * @Method("PUT")
     * @Template("AppBundle:Company:edit.html.twig")
     */
    public function updateAction(Company $company, Request $request)
    {
        $editForm = $this->createForm(CompanyType::class, $company, array(
            'action' => $this->generateUrl('admin_company_update', array('id' => $company->getId())),
            'method' => 'PUT',
        ));
        if ($editForm->handleRequest($request)->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirect($this->generateUrl('admin_company_edit', array('id' => $company->getId())));
        }
        $deleteForm = $this->createDeleteForm($company->getId(), 'admin_company_delete');

        return array(
            'company' => $company,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }


    /**
     * Save order.
     *
     * @Route("/order/{field}/{type}", name="admin_company_sort")
     */
    public function sortAction($field, $type)
    {
        $this->setOrder('company', $field, $type);

        return $this->redirect($this->generateUrl('admin_company'));
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
     * Deletes a Company entity.
     *
     * @Route("/{id}/delete", name="admin_company_delete", requirements={"id"="\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Company $company, Request $request)
    {
        $form = $this->createDeleteForm($company->getId(), 'admin_company_delete');
        if ($form->handleRequest($request)->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($company);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('admin_company'));
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
