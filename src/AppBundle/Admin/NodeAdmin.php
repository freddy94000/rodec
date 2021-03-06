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
        $subject = $this->getSubject();

        $imageOptions = ['label' => 'Image', 'required' => false];

        if ($subject->getImageName()) {
            $imageOptions['help'] = '<img src="/images/' . $subject->getImageName() . '" style="max-height: 200px; max-width: 200px;" />';
        }

        $formMapper
            ->with('Noeud')
                ->add('title', null, ['label' => 'Titre'])
                ->add('accroche', 'textarea', ['label' => 'Accroche', 'required' => false])
                ->add('nodeParent', null, ['label' => 'Noeud Parent'])
                ->add('url', null, ['label' => 'Url'])
                ->add('page', null, ['label' => 'Page associé'])
                ->add('rank', null, ['label' => 'Rang'])
                ->add('imageFile', 'Vich\UploaderBundle\Form\Type\VichFileType', $imageOptions)
            ->end()
            ->with('Seo')
                ->add('description', 'textarea', ['label' => 'Meta Description', 'required' => false])
                ->add('keyword', null, ['label' => 'Meta Keyword'])
            ->end()
        ;
    }
}
