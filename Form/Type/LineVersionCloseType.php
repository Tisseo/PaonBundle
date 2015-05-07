<?php

namespace Tisseo\TidBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Tisseo\TidBundle\Form\DataTransformer\EntityToIntTransformer;

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
                    'label' => 'line.labels.number',
                    'read_only' => true,
                    'disabled' => true
                )
            )
            ->add(
                'startDate',
                'date',
                array(
                    'label' => 'line_version.labels.start_date',
                    'read_only' => true,
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy'
                )
            )
            ->add(
                'plannedEndDate',
                'date',
                array(
                    'label' => 'line_version.labels.planned_end_date',
                    'read_only' => true,
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy'
                )
            )
            ->add(
                'version',
                'integer',
                array(
                    'label' => 'line_version.labels.version',
                    'precision' => 0,
                    'read_only' => true
                )
            )
            ->add(
                'endDate',
                'datepicker_tid',
                array(
                    'label' => 'line_version.labels.end_date',
                    'attr' => array(
                        'data-to-date' => true
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
        return 'tid_line_version';
    }
}
