<?php

namespace Tisseo\TidBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tisseo\TidBundle\Validator\Constraints\SplitedMail;

class MailType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('to','text', array(
            'label' => 'Destinataire',
            'constraints' => new splitedMail()
        ));

        $builder->add('body','textarea', array(
            'label' => 'Message',
            'max_length' => 255
        ));

        $builder->setAction($options['action']);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'attr' => array(
                    'enctype' => 'multipart/form-data'
                )
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tid_mail';
    }
}
