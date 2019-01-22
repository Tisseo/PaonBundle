<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tisseo\CoreBundle\Form\DataTransformer\EntityToIntTransformer;

class PrintingLineGroupGisType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityTransformer = new EntityToIntTransformer($options['em']);
        $entityTransformer->setEntityClass('Tisseo\\EndivBundle\\Entity\\LineGroupGis');
        $entityTransformer->setEntityRepository('TisseoEndivBundle:LineGroupGis');
        $entityTransformer->setEntityType('lineGroupGis');

        $builder
            ->add(
                'quantity',
                'number',
                array(
                    'label' => 'tisseo.paon.printing.label.quantity',
                    'precision' => 0
                )
            )
            ->add(
                'date',
                'tisseo_datepicker',
                array(
                    'label' => 'tisseo.paon.printing.label.date',
                    'attr' => array(
                        'data-from-date' => true
                    )
                )
            )
            ->add(
                'comment',
                'textarea',
                array(
                    'label' => 'tisseo.paon.printing.label.comment',
                    'required' => false
                )
            )
            ->add(
                $builder->create(
                    'lineGroupGis',
                    'hidden'
                )->addModelTransformer($entityTransformer)
            )
            ->setAction($options['action'])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'Tisseo\EndivBundle\Entity\PrintingLineGroupGis'
            ))
            ->setRequired(array(
                'em',
            ))
            ->setAllowedTypes(array(
                'em' => 'Doctrine\Common\Persistence\ObjectManager',
            ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'paon_printing_line_group_gis';
    }
}
