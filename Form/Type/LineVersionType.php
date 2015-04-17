<?php

namespace Tisseo\TidBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\View\ChoiceView;
use Doctrine\ORM\EntityRepository;
use Tisseo\TidBundle\Form\DataTransformer\EntityToIntTransformer;

use Tisseo\EndivBundle\Entity\Line;
use Tisseo\EndivBundle\Entity\LineVersion;
use Tisseo\EndivBundle\Entity\Schematic;

class LineVersionType extends AbstractType
{
    private $new;
    private $secondStape;
    private $lineVersion;
    private $close;

    public function __construct($lineVersion, $new, $secondStape, $close)
    {
        $this->new = $new;
        $this->secondStape = $secondStape;
        $this->close = $close;
        $this->lineVersion = $lineVersion;
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        usort($view->children['line']->vars['choices'], function(ChoiceView $choice1, ChoiceView $choice2) {
            $line1 = $choice1->data;
            $line2 = $choice2->data;

            if ($line1->getPriority() == $line2->getPriority())
                return strnatcmp($line1->getNumber(), $line2->getNumber());
            if ($line1->getPriority() > $line2->getPriority())
                return 1;
            if ($line1->getPriority() < $line2->getPriority())
                return -1;
        });
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $transformer = new EntityToIntTransformer($options['em']);
        $transformer->setEntityClass("Tisseo\\EndivBundle\\Entity\\Schematic");
        $transformer->setEntityRepository("TisseoEndivBundle:Schematic");
        $transformer->setEntityType("schematic");

        if ($this->new)
        {
            $builder->add('extra', 'hidden', array('mapped' => false));

            $builder->add(
                'line',
                'entity',
                array(
                    'class' => 'TisseoEndivBundle:Line',
                    'property' => 'number',
                    'label' => 'line.labels.number',
                    'empty_value' => ($this->lineVersion->getLine() ? $this->lineVersion->getLine()->getNumber() : ''),
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('l')
                            ->orderBy('l.priority', 'ASC');
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
                    'class' => 'TisseoEndivBundle:Line',
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
                'datepicker_tid',
                array(
                    'label' => 'line_version.labels.start_date',
                    'attr' => array(
                        'data-from-date' => false
                    )
                )
            );
            $builder->add(
                'plannedEndDate',
                'datepicker_tid',
                array(
                    'label' => 'line_version.labels.planned_end_date',
                    'attr' => array(
                        'data-to-date' => true
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
            if ($this->close)
            {
                $builder->add(
                    'endDate',
                    'datepicker_tid',
                    array(
                        'label' => 'line_version.labels.end_date',
                        'attr' => array(
                            'data-to-date' => true
                        )
                    )
                );
            }
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
        if (!$this->close)
        {
            $builder->add(
                'childLine',
                'entity',
                array(
                    'label' => 'line_version.labels.child_line',
                    'class' => 'TisseoEndivBundle:Line',
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
                'entity',
                array(
                    'label' => 'line_version.labels.fg_color',
                    'class' => 'TisseoEndivBundle:Color',
                    'property' => 'name',
                    'empty_value' => '',
                    'required' => true,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->where("c.name IN ('Blanc','Noir')");
                    }
                )
            );
            $builder->add(
                'bgColor',
                'entity',
                array(
                    'label' => 'line_version.labels.bg_color',
                    'class' => 'TisseoEndivBundle:Color',
                    'property' => 'name',
                    'empty_value' => '',
                    'required' => true
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
                'button_schematic',
                'button',
                array(
                    'label' => 'line_version.labels.choose_schematic',
                    'attr' => array(
                        'class' => 'choose-schematic'
                    )
                )
            );

            /*
             * This field is mandatory but must be hidden
             * it will be hiddent and positioned with css for allow use html5 validation
             * See classes specified into attribute : class
             * TODO :  This system will must be removed
             */
            $builder->add(
                $builder->create(
                    'schematic',
                    'text',
                    array(
                        'attr' => array(
                            'class' => 'input-hidden schematic-min-width-field'
                        ),
                    )
                )->addModelTransformer($transformer)
            );
            $builder->add(
                'comment',
                'textarea',
                array(
                    'label' => 'line_version.labels.comment',
                    'required' => false
                )
            );
            $builder->add(
                'modifications',
                'collection',
                array(
                    'label' => 'line_version.labels.modifications',
                    'type' => new ModificationType(),
                    'allow_add' => true,
                    'by_reference' => false
                )
            );
        }
        $builder->setAction($options['action']);
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Tisseo\EndivBundle\Entity\LineVersion',
                'validation_groups' => function(FormInterface $form) {
                    $data = $form->getExtraData();
                    if ($data) {
                        return array('registration');
                    }
                    return array('Default');
                }
            )
        );

        $resolver->setRequired(array(
            'em'
        ));

        $resolver->setAllowedTypes(array(
            'em' => 'Doctrine\Common\Persistence\ObjectManager',
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'tid_line_version';
    }
}
