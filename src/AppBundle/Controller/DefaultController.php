<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\News;
use AppBundle\Form\ContactType;
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
        $newsRepository = $this->getDoctrine()->getRepository('AppBundle:News');
        $pageRepository = $this->getDoctrine()->getRepository('AppBundle:Page');
        
        $news = $newsRepository->getNews();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $news, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            3/*limit per page*/
        );

        $page = $pageRepository->findOneBy(['code' => 'qui-sommes-nous']);
        
        return $this->render('default/index.html.twig', [
            'pagination' => $pagination,
            'page' => $page,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/actualite", name="news")
     */
    public function newsListAction(Request $request)
    {
        $newsRepository = $this->getDoctrine()->getRepository('AppBundle:News');

        $news = $newsRepository->getNews();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $news, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            3/*limit per page*/
        );

        return $this->render('default/news.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    /**
     * @param News $news
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/actualite/{slug}", name="new")
     */
    public function newsAction(News $news)
    {
        return $this->render('default/new.html.twig', [
            'news' => $news,
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
    public function contactAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($contact);
            $em->flush();

            $this->addFlash(
                'notice',
                'Votre message a bien été envoyé'
            );

            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
