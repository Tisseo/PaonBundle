<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ListSchematicType extends AbstractType
{
    protected $isBatch;

    protected $groupGis;

    /**
     * @param bool $isBatch
     */
    public function __construct($isBatch = false, $groupGis = false)
    {
        $this->isBatch = $isBatch;
        $this->groupGis = $groupGis;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'schematics',
                'collection', array(
                    'type' => new SchematicType($this->isBatch, $this->groupGis),
                    'label' => false
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
                'data_class' => 'Tisseo\PaonBundle\Entity\SchematicList',
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'paon_list_schematic';
    }
}
