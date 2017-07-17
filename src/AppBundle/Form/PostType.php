<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class PostType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('menu', EntityType::class, array(
            'class' => 'AppBundle:Menu',
            'attr'    =>array('class'=>'form-control'),
            'choice_label' => 'name',
            'group_by'=>  function($type)
            {
              return  $services = $type->getService()->getName();
            },
        ))
        ->add('title', TextType::class, array('attr'=>array('class'=>'form-control')) )
        ->add('image', FileType::class, array('data_class' => null , 'required' => false , 'attr' => array('class' => 'form-control')))
        ->add('abstract', TextType::class ,array('attr'=>array('class'=>'form-control')) )
        ->add('content', TextareaType::class, array('attr' => array('class' => 'form-control')) )
        ->add('isActive', CheckboxType::class , array('attr'=> array('checked'=>'true')) );

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Post'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_post';
    }


}
