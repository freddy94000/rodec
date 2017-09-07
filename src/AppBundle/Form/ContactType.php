<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', null, ['attr' => ['placeholder' => 'Prénom NOM *', 'class' => 'form-control']])
            ->add('phoneNumber', null, ['attr' => ['placeholder' => 'Téléphone *', 'class' => 'form-control']])
            ->add('email', EmailType::class, ['attr' => ['placeholder' => 'Email *', 'class' => 'form-control']])
            ->add('message', null, ['attr' => ['placeholder' => 'Votre message *', 'class' => 'form-control', 'rows' => 5]])
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Contact',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_contact';
    }


}
