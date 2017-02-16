<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="default")
     */
    public function defaultAction()
    {
        return $this->render('default/default.html.twig');
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/accueil", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function headerAction()
    {
        $nodeRepository = $this->getDoctrine()->getRepository('AppBundle:Node');
        
        $nodes = $nodeRepository->getNodesParent();

        return $this->render('default/header.html.twig', ['nodes' => $nodes]);
    }

    /**
     * @param $code
     * @return \Symfony\Component\HttpFoundation\Response
     * 
     * @Route("/{code}", name="page")
     */
    public function pageAction($code)
    {
        $pageRepository = $this->getDoctrine()->getRepository('AppBundle:Page');
        $nodeRepository = $this->getDoctrine()->getRepository('AppBundle:Node');

        $page = $pageRepository->findOneBy(['code' => $code]);

        if (!$page) {
            $node = $nodeRepository->findOneBy(['url' => $code]);
            return $this->render('default/node.html.twig', ['node' => $node]);
        }

        return $this->render('default/page.html.twig', ['page' => $page]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction()
    {
        return $this->render('default/contact.html.twig');
    }
}
