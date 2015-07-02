<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModificationType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'date',
            'tisseo_datepicker',
            array(
                'label' => 'modification.labels.date',
                'required' => true,
                'attr' => array(
                    'data-from-date' => false,
                    'class' => 'input-range'
                )
            )
        );
        $builder->add(
            'author',
            'text',
            array(
                'label' => 'modification.labels.author',
                'required' => true
            )
        );
        $builder->add(
            'description',
            'textarea',
            array(
                'label' => 'modification.labels.description',
                'required' => true
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
                'data_class' => 'Tisseo\EndivBundle\Entity\Modification'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'paon_modification';
    }
}
