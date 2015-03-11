<?php

namespace Tisseo\TidBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Tisseo\EndivBundle\Entity\LineVersion;
use Tisseo\EndivBundle\Form\DataTransformer\EntityToIntTransformer;

class PrintingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $entityTransformer = new EntityToIntTransformer($options["em"]);
        $entityTransformer->setEntityClass("Tisseo\\EndivBundle\\Entity\\LineVersion");
        $entityTransformer->setEntityRepository("TisseoEndivBundle:LineVersion");
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
                'data_class' => 'Tisseo\EndivBundle\Entity\Printing'
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
        return 'tid_printing';
    }
}
