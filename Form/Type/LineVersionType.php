<?php

namespace Tisseo\DatawarehouseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Tisseo\DatawarehouseBundle\Entity\Line;
use Tisseo\DatawarehouseBundle\Entity\LineVersion;

class LineVersionType extends AbstractType
{
    private $new;
    private $secondStape;
    private $lineVersion;

    public function __construct($new, $secondStape, $lineVersion)
    {
        $this->new = $new;
        $this->secondStape = $secondStape;
        $this->lineVersion = $lineVersion;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->new)
        {
            $builder->add(
                'line',
                'entity',
                array(
                    'class' => 'TisseoDatawarehouseBundle:Line',
                    'property' => 'number',
                    'label' => 'line.labels.number',
                    'empty_value' => ($this->lineVersion->getLine() ? $this->lineVersion->getLine()->getNumber() : ''),
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('l')
                            ->orderBy('l.number', 'ASC');
                    }
                )
            );
        }
        if (!$this->new && !$this->secondStape)
        {
            $builder->add(
                'line',
                'entity',
                array(
                    'class' => 'TisseoDatawarehouseBundle:Line',
                    'property' => 'number',
                    'label' => 'line.labels.number',
                    'empty_value' => ($this->lineVersion->getLine() ? $this->lineVersion->getLine()->getNumber() : ''),
                    'read_only' => true,
                    'disabled' => true
                )
            );
        }
        if ($this->secondStape)
        {
            $builder->add(
                'startDate',
                'datepicker',
                array(
                    'label' => 'line_version.labels.start_date',
                    'attr' => array(
                        'data-from-start' => true
                    )
                )
            );
            $builder->add(
                'plannedEndDate',
                'datepicker',
                array(
                    'label' => 'line_version.labels.planned_end_date',
                    'attr' => array(
                        'data-from-start' => true
                    )
                )
            );
        }
        else
        {
            $builder->add(
                'startDate',
                'date',
                array(
                    'label' => 'line_version.labels.start_date',
                    'read_only' => true,
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy'
                )
            );
            $builder->add(
                'plannedEndDate',
                'date',
                array(
                    'label' => 'line_version.labels.planned_end_date',
                    'read_only' => true,
                    'widget' => 'single_text',
                    'format' => 'dd/MM/yyyy'
                )
            );
        }
        $builder->add(
            'version',
            'integer',
            array(
                'label' => 'line_version.labels.version',
                'precision' => 0,
                'read_only' => true
            )
        );
        $builder->add(
            'childLine',
            'entity',
            array(
                'label' => 'line_version.labels.child_line',
                'class' => 'TisseoDatawarehouseBundle:Line',
                'property' => 'number',
                'empty_value' => '',
                'required' => false,
                'query_builder' => function(EntityRepository $er) {
                    return $er->createQueryBuilder('l')
                        ->orderBy('l.number', 'ASC');
                }
            )
        );
        $builder->add(
            'name',
            'text',
            array(
                'label' => 'line_version.labels.name'
            )
        );
        $builder->add(
            'forwardDirection',
            'text',
            array(
                'label' => 'line_version.labels.forward'
            )
        );
        $builder->add(
            'backwardDirection',
            'text',
            array(
                'label' => 'line_version.labels.backward'
            )
        );
        $builder->add(
            'fgColor',
            'text',
            array(
                'label' => 'line_version.labels.fg_color'
            )
        );
        $builder->add(
            'fgHexaColor',
            'text',
            array(
                'label' => 'line_version.labels.fg_hexa_color'
            )
        );
        $builder->add(
            'bgColor',
            'text',
            array(
                'label' => 'line_version.labels.bg_color'
            )
        );
        $builder->add(
            'bgHexaColor',
            'text',
            array(
                'label' => 'line_version.labels.bg_hexa_color'
            )
        );
        $builder->add(
            'cartoFile',
            'file',
            array(
                'label' => 'line_version.labels.carto_file',
                'data_class' => null,
                'required' => false
            )
        );
        $builder->add(
            'accessibility',
            'checkbox',
            array(
                'label' => 'line_version.labels.accessibility',
                'required' => false
            )
        );
        $builder->add(
            'airConditioned',
            'checkbox',
            array(
                'label' => 'line_version.labels.air_conditioned',
                'required' => false
            )
        );
        $builder->add(
            'certified',
            'checkbox',
            array(
                'label' => 'line_version.labels.certified',
                'required' => false
            )
        );
        $builder->add(
            'depot',
            'text',
            array(
                'label' => 'line_version.labels.depot'
            )
        );
        $builder->add(
            'comment',
            'textarea',
            array(
                'label' => 'line_version.labels.comment',
                'required' => false
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
                'data_class' => 'Tisseo\DatawarehouseBundle\Entity\LineVersion'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'datawarehouse_line_version';
    }
}
