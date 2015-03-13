<?php

namespace Tisseo\TidBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModificationLinkType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'modification',
            'collection',
            array(
                'type' => new ModificationType(),
                'allow_add' => true,
                'by_reference' => false
            )
        );
        $builder->add(
            'date',
            'datepicker_tid',
            array(
                'label' => 'modification_link.labels.date',
                'attr' => array(
                    'data-from-date' => false
                )
            )
        );
        $builder->setAction($options['action']);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Tisseo\EndivBundle\Entity\ModificationLink'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tid_modification_link';
    }
}
