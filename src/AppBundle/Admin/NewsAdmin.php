<?php

namespace AppBundle\Admin;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

class NewsAdmin extends AbstractAdmin
{
    protected $datagridValues = [
        '_page' => 1,
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    ];

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
            ->add('publishAt', 'datetime', ['label' => 'Publié le', 'format' => 'd/m/Y H:i'])
            ->add('title', null, ['label' => 'Titre'])
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
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
            ->with('Actualité')
                ->add('publishAt', 'datetime', ['label' => 'Publié le'])
                ->add('title', null, ['label' => 'Titre'])
                ->add('content', CKEditorType::class, ['label' => 'Contenu'])
                ->add('image')
            ->end()
            ->with('Seo')
                ->add('description', 'textarea', ['label' => 'Meta Description'])
                ->add('keyword', null, ['label' => 'Meta Keyword'])
            ->end()
        ;
    }
}
