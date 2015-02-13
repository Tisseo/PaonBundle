<?php

namespace Tisseo\DatawarehouseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Tisseo\DatawarehouseBundle\Entity\LineVersion;
use Tisseo\DatawarehouseBundle\Form\DataTransformer\EntityToIntTransformer;

class PrintingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityTransformer = new EntityToIntTransformer($options["em"]);
        $entityTransformer->setEntityClass("Tisseo\\DatawarehouseBundle\\Entity\\LineVersion");
        $entityTransformer->setEntityRepository("TisseoDatawarehouseBundle:LineVersion");
        $entityTransformer->setEntityType("lineVersion");

        $builder->add(
            'quantity',
            'number',
            array(
                'label' => 'printing.labels.quantity',
                'precision' => 0
            )
        );
        $builder->add(
            'date',
            'datepicker',
            array(
                'label' => 'printing.labels.date',
                'attr' => array(
                    'data-from-date' => true
                )
            )
        );
        $builder->add(
            'comment',
            'textarea',
            array(
                'label' => 'printing.labels.comment',
                'required' => false
            )
        );
        $builder->add(
            $builder->create(
                'lineVersion',
                'hidden'
            )->addModelTransformer($entityTransformer)
        );
        $builder->setAction($options['action']);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'Tisseo\DatawarehouseBundle\Entity\Printing'
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
        return 'datawarehouse_printing';
    }
}
