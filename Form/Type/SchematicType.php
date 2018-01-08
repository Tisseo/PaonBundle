<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class SchematicType extends AbstractType
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
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->isBatch) {
            $builder->add(
                'deprecated',
                'checkbox',
                array(
                    'label' => false,
                    'required' => false
                )
            );
        } elseif ($this->groupGis) {
            $builder->add(
                'groupGis',
                'checkbox',
                array(
                    'label' => false,
                    'required' => false
                )
            );
        } else {
            $builder
                ->add(
                    'name',
                    'hidden',
                    array(
                        'label' => 'tisseo.paon.schematic.label.name'
                    )
                )
                ->add(
                    'comment',
                    'textarea',
                    array(
                        'label' => 'tisseo.paon.schematic.label.comment'
                    )
                )
                ->add(
                    'deprecated',
                    'hidden',
                    array(
                        'data' => 0
                    )
                )
                ->add(
                    'date',
                    'tisseo_datetimepicker',
                    array(
                        'label' => 'tisseo.paon.schematic.label.date',
                        'attr' => array(
                            'class' => 'input-date'
                        )
                    )
                )
                ->add(
                    'file',
                    'file',
                    array(
                        'label' => 'tisseo.paon.schematic.label.file',
                        'required' => false
                    )
                )
            ;

            $builder->setAction($options['action']);
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Tisseo\EndivBundle\Entity\Schematic',
                'attr' => array(
                    'enctype' => 'multipart/form-data'
                )
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'paon_schematic';
    }
}
