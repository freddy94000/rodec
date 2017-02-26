<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\News;
use AppBundle\Entity\Newsletter;
use AppBundle\Form\ContactType;
use AppBundle\Form\NewsletterType;
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
        $informationRepository = $this->getDoctrine()->getRepository('AppBundle:Information');
        $em = $this->getDoctrine()->getManager();
        
        $news = $newsRepository->getNews();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $news, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            3/*limit per page*/
        );

        $page = $pageRepository->findOneBy(['code' => 'qui-sommes-nous']);

        $lienEspaceClient = $informationRepository->findOneBy(['dataKey' => 'lien-espace-client']);
        
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            $em->persist($newsletter);
            $em->flush();

            $this->addFlash(
                'notice',
                'Votre inscription à la newsletter a bien été enregistrée'
            );
            
            return $this->redirectToRoute('homepage');
        }
        
        
        return $this->render('default/index.html.twig', [
            'pagination' => $pagination,
            'page' => $page,
            'form' => $form->createView(),
            'lien' => $lienEspaceClient
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
        $informationRepository = $this->getDoctrine()->getRepository('AppBundle:Information');
        $lienEspaceClient = $informationRepository->findOneBy(['dataKey' => 'lien-espace-client']);
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);

        $news = $newsRepository->getNews();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $news, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            4/*limit per page*/
        );

        return $this->render('default/news.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView(),
            'lien' => $lienEspaceClient
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
        $informationRepository = $this->getDoctrine()->getRepository('AppBundle:Information');
        $lienEspaceClient = $informationRepository->findOneBy(['dataKey' => 'lien-espace-client']);
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);

        return $this->render('default/new.html.twig', [
            'news' => $news,
            'form' => $form->createView(),
            'lien' => $lienEspaceClient
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
        $informationRepository = $this->getDoctrine()->getRepository('AppBundle:Information');
        $pageRepository = $this->getDoctrine()->getRepository('AppBundle:Page');
        $nodeRepository = $this->getDoctrine()->getRepository('AppBundle:Node');
        $lienEspaceClient = $informationRepository->findOneBy(['dataKey' => 'lien-espace-client']);
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);

        $page = $pageRepository->findOneBy(['code' => $code]);

        if (!$page) {
            $node = $nodeRepository->findOneBy(['url' => $code]);
            return $this->render('default/node.html.twig', [
                'node' => $node,
                'form' => $form->createView(),
                'lien' => $lienEspaceClient
            ]);
        }

        return $this->render('default/page.html.twig', [
            'page' => $page,
            'form' => $form->createView(),
            'lien' => $lienEspaceClient
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contactAction(Request $request)
    {
        $informationRepository = $this->getDoctrine()->getRepository('AppBundle:Information');
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
            'informationRepository' => $informationRepository
        ]);
    }
}
