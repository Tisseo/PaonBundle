<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LineType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'number',
                'text',
                array(
                    'label' => 'tisseo.paon.line.label.number'
                )
            )
            ->add(
                'physicalMode',
                'entity',
                array(
                    'class' => 'TisseoEndivBundle:PhysicalMode',
                    'property' => 'name',
                    'label' => 'tisseo.paon.line.label.physical_mode'
                )
            )
            ->add(
                'priority',
                'integer',
                array(
                    'label' => 'tisseo.paon.line.label.priority',
                )
            )
            ->add(
                'codeTicketing',
                'text',
                array(
                    'label' => 'tisseo.paon.line.label.code_ticketing',
                    'required' => false,
                )
            )
            ->add(
                'publicationDate',
                'datepicker',
                array(
                    'label' => 'tisseo.paon.line.label.publication_date',
                    'required' => false,
                )
            )
            ->add(
                'lineDatasources',
                'collection',
                array(
                    'type' => new LineDatasourceType(),
                    'by_reference' => false
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
                'data_class' => 'Tisseo\EndivBundle\Entity\Line'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'paon_line';
    }
}
