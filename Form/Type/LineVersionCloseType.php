<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LineVersionCloseType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'line',
                'entity',
                array(
                    'class' => 'TisseoEndivBundle:Line',
                    'property' => 'number',
                    'label' => 'tisseo.paon.line.label.number',
                    'read_only' => true,
                    'disabled' => true
                )
            )
            ->add(
                'startDate',
                'date',
                array(
                    'label' => 'tisseo.paon.line_version.label.start_date',
                    'read_only' => true,
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy'
                )
            )
            ->add(
                'plannedEndDate',
                'date',
                array(
                    'label' => 'tisseo.paon.line_version.label.planned_end_date',
                    'read_only' => true,
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy'
                )
            )
            ->add(
                'version',
                'integer',
                array(
                    'label' => 'tisseo.paon.line_version.label.version',
                    'precision' => 0,
                    'read_only' => true
                )
            )
            ->add(
                'endDate',
                'tisseo_datepicker',
                array(
                    'label' => 'tisseo.paon.line_version.label.end_date',
                    'attr' => array(
                        'data-to-date' => true,
                        'autocomplete' => 'off'
                    )
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
                'data_class' => 'Tisseo\EndivBundle\Entity\LineVersion',
                'validation_groups' => array('LineVersion', 'close')
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'paon_line_version';
    }
}
