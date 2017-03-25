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

        $pages = $this->em->getRepository('AppBundle:Page')->findAll();
        foreach ($pages as $page) {
            $urls[] = [
                'loc' => $this->router->generate('page', ['code' => $page->getCode()], Router::ABSOLUTE_URL),
                'priority' => 0.9,
            ];
        }

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
            'loc' => $this->router->generate('news', [], Router::ABSOLUTE_URL),
            'priority' => 0.7,
        ];

        $nodes = $this->em->getRepository('AppBundle:Node')->findAll();
        foreach ($nodes as $node) {
            if (!$node->getPage()) {
                $urls[] = [
                    'loc' => $this->router->generate('page', ['code' => $node->getUrl()], Router::ABSOLUTE_URL),
                    'priority' => 0.6,
                ];
            }
        }

        $urls[] = [
            'loc' => $this->router->generate('contact', [], Router::ABSOLUTE_URL),
            'priority' => 0.5,
        ];

        $urls[] = [
            'loc' => $this->router->generate('plan', [], Router::ABSOLUTE_URL),
            'priority' => 0.4,
        ];

        return $urls;
    }
}