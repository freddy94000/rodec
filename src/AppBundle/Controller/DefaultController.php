<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use AppBundle\Entity\Email;
use AppBundle\Entity\News;
use AppBundle\Form\ContactType;
use AppBundle\Form\EmailFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/", name="homepage")
     * @Route("/contact", name="contact")
     * @Route("/le-cabinet", name="lecabinet")
     * @Route("/qui-sommes-nous", name="quisommesnous")
     * @Route("/nos-valeurs", name="nosvaleurs")
     * @Route("/vos-interlocuteurs", name="vosinterlocuteurs")
     */
    public function homeAction(Request $request)
    {
        $nodeRepository = $this->getDoctrine()->getRepository('AppBundle:Node');
        $node = $nodeRepository->findOneBy(['url' => 'homepage']);

        $teamRepository = $this->getDoctrine()->getRepository('AppBundle:Team');
        $newsRepository = $this->getDoctrine()->getRepository('AppBundle:News');

        $teams = $teamRepository->findAll();
        $news = $newsRepository->getNews();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $news, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            4/*limit per page*/
        );
        
        return $this->render('default/home.html.twig', [
            'node' => $node,
            'teams' => $teams,
            'pagination' => $pagination
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/actualite", name="actualite")
     */
    public function actualitesAction(Request $request)
    {
        $nodeRepository = $this->getDoctrine()->getRepository('AppBundle:Node');
        $node = $nodeRepository->findOneBy(['url' => 'actualite']);

        $newsRepository = $this->getDoctrine()->getRepository('AppBundle:News');
        $news = $newsRepository->getNews();

        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $news, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            12/*limit per page*/
        );

        return $this->render('default/actualites.html.twig', [
            'pagination' => $pagination,
            'node' => $node
        ]);
    }

    /**
     * @param News $news
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     *
     * @Route("/actualite/{slug}", name="new")
     */
    public function newsAction(News $news)
    {
        $nodeRepository = $this->getDoctrine()->getRepository('AppBundle:Node');
        $node = $nodeRepository->findOneBy(['url' => 'actualite']);

        if ($news->getPublishAt() > new \DateTime()) {
            throw new \Exception("L'Actualité n'est pas encore disponible");
        }

        return $this->render('default/new.html.twig', [
            'news' => $news,
            'node' => $node
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/accompagnement-international", name="accompagnement-international")
     * @Route("/pour-qui", name="pourqui")
     * @Route("/comment", name="comment")
     */
    public function internationalAction(Request $request)
    {
        $nodeRepository = $this->getDoctrine()->getRepository('AppBundle:Node');
        $node = $nodeRepository->findOneBy(['url' => 'accompagnement-international']);

        return $this->render('default/international.html.twig', [
            'node' => $node,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/nos-prestations", name="nos-prestations")
     * @Route("/comptabilite", name="comptabilite")
     * @Route("/travaux-courants-de-comptabilite", name="travauxcourants")
     * @Route("/conseil-adapte", name="conseiladapte")
     * @Route("/controle-de-gestion", name="controlegestion")
     * @Route("/droit-du-travail-et-administration-du-personnel", name="administrationdupersonnel")
     * @Route("/gestion-courante-du-personnel", name="gestioncourante")
     * @Route("/conseil-specifique", name="conseilspecifique")
     * @Route("/fiscalite-d-entreprise", name="fiscalite")
     * @Route("/conseil-fiscal", name="fiscal")
     * @Route("/accompagnement-fiscal-specifique", name="specifique")
     * @Route("/fiscalite-des-particuliers", name="particuliers")
     * @Route("/creation-d-entreprise", name="entreprise")
     * @Route("/remplacement", name="remplacement")
     */
    public function prestationsAction(Request $request)
    {
        $nodeRepository = $this->getDoctrine()->getRepository('AppBundle:Node');
        $node = $nodeRepository->findOneBy(['url' => 'nos-prestations']);
        
        return $this->render('default/prestations.html.twig', [
            'node' => $node,
        ]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/mention-legale", name="mention-legale")
     */
    public function mentionAction(Request $request)
    {
        $pageRepository = $this->getDoctrine()->getRepository('AppBundle:Page');
        $page = $pageRepository->findOneBy(['code' => 'mention-legale']);

        return $this->render('default/mention.html.twig', [
            'page' => $page,
        ]);
    }

    /**
     * @Route("/plan-du-site", name="plan")
     */
    public function planAction()
    {
        $nodeRepository = $this->getDoctrine()->getRepository('AppBundle:Node');
        $nodes = $nodeRepository->getNodesParent();

        return $this->render('default/plan.html.twig', [
            'nodes' => $nodes,
        ]);
    }

    /**
     * @Route("/sitemap.{_format}", name="sitemap", Requirements={"_format" = "xml"})
     */
    public function siteMapAction()
    {
        $urls = $this->get('app.service.site_map')->getUrls();

        return $this->render('default/sitemap.xml.twig', ['urls' => $urls]);
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route("/form-contact", name="form-contact")
     */
    public function contactAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $informationRepository = $em->getRepository('AppBundle:Information');
        $email = $informationRepository->findOneBy(['dataKey' => 'email']);
        
        $contact = new Contact();
        
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        
        if ($form->isValid()) {
            if (!$this->captchaverify($request->get('g-recaptcha-response'))) {
                $this->addFlash(
                    'error',
                    'Merci de cocher la case.'
                );

                return $this->redirectToRoute('homepage');
            }

            $em->persist($contact);
            $em->flush();

            $message = \Swift_Message::newInstance()
                ->setSubject($contact->getName() . ' <' . $contact->getEmail() . ' - ' . $contact->getPhoneNumber(). '> vous a envoyé un message depuis rodecconseils.fr' )
                ->setFrom($email->getDataValue())
                ->setTo($email->getDataValue())
                ->setBody($contact->getMessage());
            $this->get('mailer')->send($message);
            
            $this->addFlash(
                'notice',
                'Votre message a bien été envoyé'
            );
        } else {
            $this->addFlash(
                'error',
                'Une erreur est survenue lors de l\'envoi de votre message'
            );
        }
        
        return $this->redirectToRoute('homepage');
    }

    /**
     * @param $recaptcha
     * @return mixed
     */
    private function captchaverify($recaptcha) {
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array("secret"=>"6LcGdX0UAAAAANfMtyRZ6j8Y2ex_Ca7W_vGZwJEL","response"=>$recaptcha));
        $response = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($response);

        return $data->success;
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route("/form-newsletter", name="form-newsletter")
     */
    public function newsletterAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $emailRepository = $em->getRepository('AppBundle:Email');

        $newsletter = new Email();

        $form = $this->createForm(EmailFormType::class, $newsletter);
        $form->handleRequest($request);

        if ($form->isValid()) {
            if (!$emailRepository->findOneBy(['email' => $newsletter->getEmail()])) {
                $em->persist($newsletter);
                $em->flush();
            }

            $this->addFlash(
                'notice',
                'Votre inscription à la newsletter a bien été enregistrée'
            );
        } else {
            $this->addFlash(
                'error',
                'Une erreur est survenue lors de l\'envoi de votre message'
            );
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function footerAction()
    {
        $informationRepository = $this->getDoctrine()->getRepository('AppBundle:Information');
        
        $contact = new Contact();
        $contactForm = $this->createForm(ContactType::class, $contact);
        
        $newsletter = new Email();
        $newsletterForm = $this->createForm(EmailFormType::class, $newsletter);
        
        return $this->render('default/footer.html.twig', [
            'contactForm' => $contactForm->createView(),
            'informationRepository' => $informationRepository,
            'newsletterForm' => $newsletterForm->createView(),
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function headerAction()
    {
        $informationRepository = $this->getDoctrine()->getRepository('AppBundle:Information');
        $nodeRepository = $this->getDoctrine()->getRepository('AppBundle:Node');

        $nodes = $nodeRepository->getNodesParent();

        return $this->render('default/header.html.twig', [
            'nodes' => $nodes,
            'informationRepository' => $informationRepository,
        ]);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function metaAction()
    {
        $informationRepository = $this->getDoctrine()->getRepository('AppBundle:Information');

        $description = $informationRepository->findOneBy(['dataKey' => 'meta-description']);
        $keyword = $informationRepository->findOneBy(['dataKey' => 'meta-keyword']);

        return $this->render('default/meta.html.twig', [
            'description' => $description,
            'keyword' => $keyword,
        ]);
    }

}
