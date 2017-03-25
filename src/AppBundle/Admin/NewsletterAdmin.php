<?php

namespace AppBundle\Admin;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

class NewsletterAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    ];

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('edit')
            ->remove('remove')
            ->remove('export')
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('createdAt', 'datetime', ['label' => 'Envoyé le', 'format' => 'd/m/Y H:i'])
            ->add('subject', null, ['label' => 'Objet'])
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
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
            ->add('subject', null, ['label' => 'Objet'])
            ->add('content', CKEditorType::class, ['label' => 'Contenu'])
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('createdAt', 'datetime', ['label' => 'Envoyé le', 'format' => 'd/m/Y H:i'])
            ->add('subject', null, ['label' => 'Objet'])
            ->add('content', null, ['label' => 'Contenu'])
        ;
    }
}
