<?php

namespace AppBundle\EventListener;

use \Doctrine\ORM\Event\LifecycleEventArgs;
use \AppBundle\Entity\Newsletter;

class DoctrineListener
{
    protected $mailer;

    /**
     * DoctrineListener constructor.
     */
    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof  Newsletter) {
            return;
        }

        $em = $args->getEntityManager();
        $emailRepository = $em->getRepository('AppBundle:Email');
        $informationRepository = $em->getRepository('AppBundle:Information');
        $from = $informationRepository->findOneBy(['dataKey' => 'email']);

        $emails = $emailRepository->findAll();
        
        foreach ($emails as $to) {
            $message = \Swift_Message::newInstance()
                ->setSubject($entity->getSubject())
                ->setFrom($from->getDataValue())
                ->setTo($to->getEmail())
                ->setBody($entity->getContent(), 'text/html');
            $this->mailer->send($message);
        }
    }

}