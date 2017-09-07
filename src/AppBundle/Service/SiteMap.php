<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Routing\Router;

class SiteMap
{

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em, Router $router)
    {
        $this->em = $em;
        $this->router = $router;
    }

    public function getUrls()
    {
        $urls = [];

        $urls[] = [
            'loc' => $this->router->generate('homepage', [], Router::ABSOLUTE_URL),
            'priority' => 1,
        ];

        $news = $this->em->getRepository('AppBundle:News')->getNews()->getResult();
        foreach ($news as $new) {
            $date = ($new->getPublishAt()) ? $new->getPublishAt() : $new->getCreatedAt();
            $urls[] = [
                'loc' => $this->router->generate('new', ['slug' => $new->getSlug()], Router::ABSOLUTE_URL),
                'lastmod' => $date->format('Y-m-d'),
                'priority' => 0.8,
            ];
        }

        $urls[] = [
            'loc' => $this->router->generate('accompagnement-international', [], Router::ABSOLUTE_URL),
            'priority' => 0.7,
        ];

        $urls[] = [
            'loc' => $this->router->generate('nos-prestations', [], Router::ABSOLUTE_URL),
            'priority' => 0.7,
        ];

        $urls[] = [
            'loc' => $this->router->generate('actualite', [], Router::ABSOLUTE_URL),
            'priority' => 0.6,
        ];

        $urls[] = [
            'loc' => $this->router->generate('mention-legale', [], Router::ABSOLUTE_URL),
            'priority' => 0.5,
        ];

        $urls[] = [
            'loc' => $this->router->generate('plan', [], Router::ABSOLUTE_URL),
            'priority' => 0.4,
        ];

        return $urls;
    }
}