<?php

namespace AppBundle\Service;

use AppBundle\Entity\Node;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;

class SiteMap
{

    protected $router;

    protected $em;

    public function __construct(EntityManager $em, Router $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    public function getUrls()
    {
        $urls = [];

        $urls[] = ['loc' => $this->router->generate('app_default_index', [], true)];
        $urls[] = ['loc' => $this->router->generate('app_contact_brochure', [], true)];
        $urls[] = ['loc' => $this->router->generate('app_guide_index', [], true)];

        $urls[] = ['loc' => $this->router->generate('app_job_index', [], true)];
        $urls[] = ['loc' => $this->router->generate('app_job_prepare', [], true)];
        $urls[] = ['loc' => $this->router->generate('app_job_list', [], true)];

        $postes = $this->em->getRepository('AppBundle:Poste')->findAll();
        foreach ($postes as $poste) {
            $urls[] = ['loc' => $this->router->generate('app_job_fiche', ['slug' => $poste->getSlug()], true)];
            $urls[] = ['loc' => $this->router->generate('app_job_prepare_poste', ['slug' => $poste->getSlug()], true)];
            $urls[] = ['loc' => $this->router->generate('app_job_list_poste', ['slug' => $poste->getSlug()], true)];
        }
        $annonces = $this->em->getRepository('AppBundle:Annonce')->findAll();
        foreach ($annonces as $annonce) {
            $urls[] = ['loc' => $this->router->generate('app_job_annonce', ['annonceId' => $annonce->getId()], true)];
            $urls[] = ['loc' => $this->router->generate('app_job_annonce_poste', [
                'annonceId' => $annonce->getId(),
                'slug' => $annonce->getPoste()->getSlug()
            ], true)];
        }

        $offers = $this->em->getRepository('AppBundle:Offer')->findBy(['status' => 1]);
        foreach ($offers as $offer) {
            $urls[] = ['loc' => $this->router->generate('app_offer_index', ['slug' => $offer->getSlug()], true)];
        }

        $pages = $this->em->getRepository('KernixCMSBundle:Page')->findBy(['online' => 1]);
        foreach ($pages as $page) {
            $urls[] = ['loc' => $this->router->generate('kernix_cms_page_show', ['code' => $page->getCode()], true)];
        }

        return $urls;
    }
}