<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class NodeAdmin extends AbstractAdmin
{
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('export')
            ->remove('show')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('title', null, ['label' => 'Titre'])
            ->add('nodeParent', null, ['label' => 'Noeud Parent'])
            ->add('url', null, ['label' => 'Url'])
            ->add('page', null, ['label' => 'Page associé'])
            ->add('rank', null, ['label' => 'Rang'])
            ->add('_action', null, array(
                'actions' => array(
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', null, ['label' => 'Titre'])
            ->add('nodeParent', null, ['label' => 'Noeud Parent'])
            ->add('url', null, ['label' => 'Url'])
            ->add('page', null, ['label' => 'Page associé'])
            ->add('rank', null, ['label' => 'Rang'])
        ;
    }
}
