<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class UserType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username', EmailType::class, array ('attr'=>array('class'=>'form-control')) )
        ->add('password', PasswordType::class, array ('attr'=>array('class'=>'form-control')) )
        ->add('role' , ChoiceType::class,
          array(
                'choices' => array(
                    'miembro'       => 'ROLE_MEMBER',
                    'redactor'      => 'ROLE_EDITOR',
                    'editor'        => 'ROLE_PUBLISHER',
                    'administrador' => 'ROLE_ADMIN'),
                'attr'    =>array(
                    'class'         =>'form-control')
                )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
