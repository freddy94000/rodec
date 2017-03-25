<?php

namespace AppBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class ContactAdmin extends AbstractAdmin
{

    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    ];

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('edit')
            ->remove('export')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('createdAt', 'datetime', [
                'label' => 'Envoyé le',
                'format' => 'd/m/Y H:i',
            ])
            ->add('name', null, ['label' => 'Prénom Nom'])
            ->add('phoneNumber', null, ['label' => 'Numéro de téléphone'])
            ->add('email', null, ['label' => 'Adresse email'])
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('name', null, ['label' => 'Prénom Nom'])
            ->add('phoneNumber', null, ['label' => 'Numéro de téléphone'])
            ->add('email', null, ['label' => 'Adresse email'])
            ->add('message', null, ['label' => 'Message'])
            ->add('createdAt', 'datetime', [
                'label' => 'Envoyé le',
                'format' => 'd/m/Y H:i',
            ])
        ;
    }
}
