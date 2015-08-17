<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LineSchemaType extends AbstractType
{
    /** @var bool $isBatch */
    protected $isBatch;

    protected $addInfo;

    /**
     * @param bool $isBatch
     */
    public function __construct($isBatch = false, $addInfo = false)
    {
        $this->isBatch = $isBatch;
        $this->addInfo = $addInfo;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->isBatch)
        {
            $builder->add(
                'deprecated',
                'checkbox',
                array(
                    'label' => false,
                    'required' => false
                )
            );
        }
        else
        {
            $builder
                ->add(
                    'name',
                    'hidden',
                    array(
                        'label' => 'line_schema.labels.name'
                    )
                )
                ->add(
                    'comment',
                    'textarea',
                    array(
                        'label' => 'line_schema.labels.comment'
                    )
                )
                ->add(
                    'deprecated',
                    'hidden',
                    array(
                        'data' => 0
                    )
                )
            ;
            if ($this->addInfo)
            {
                $builder
                    ->add(
                        'date',
                        'date',
                        array(
                            'label' => 'line_schema.labels.date',
                            'widget' => 'single_text',
                            'read_only' => true,
                            'format' => 'dd/MM/yyyy'
                        )
                    )
                ;
            }
            else
            {
                $builder
                    ->add(
                        'date',
                        'tisseo_datepicker',
                        array(
                            'label' => 'line_schema.labels.date',
                            'attr' => array(
                                'class' => 'input-date'
                            )
                        )
                    )
                    ->add(
                        'file',
                        'file',
                        array(
                            'label' => 'line_schema.labels.file',
                            'required' => true
                        )
                    )
                ;
            }
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
        return 'paon_line_schema';
    }
}
