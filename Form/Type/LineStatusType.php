<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Tisseo\PaonBundle\Form\Type\LineStatus;


class LineStatusType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'comment',
                'textarea',
                array(
                    'label' => 'tisseo.paon.line_status.label.comment',
                    'required' => false,
                )
            )
            ->setAction($options['action'])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Tisseo\EndivBundle\Entity\LineStatus'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'paon_line_status';
    }
}
