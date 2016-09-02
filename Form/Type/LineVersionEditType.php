<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;
use Tisseo\CoreBundle\Form\DataTransformer\EntityToIntTransformer;

class LineVersionEditType extends AbstractType
{
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

        $builder
            ->addEventListener(FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($options) {
                    $form = $event->getForm();
                    $lineVersion = $event->getData();

                    $em = $options['em'];
                    $query = $em->createQuery("
                        SELECT l.number
                        FROM Tisseo\EndivBundle\Entity\LineGroupContent lgc
                        JOIN lgc.lineGroup lg
                        JOIN lg.lineGroupContents lgc2
                        JOIN lgc2.lineVersion lv
                        JOIN lv.line l
                        WHERE lgc.isParent = true
                        AND lgc.lineVersion = :lv
                        AND lgc2.isParent = false
                    ")
                    ->setParameter('lv', $lineVersion);
                    $childLine = $query->getOneOrNullResult();

                    if (empty($childLine))
                        $lineNumber = "";
                    else
                        $lineNumber = $childLine["number"];

                    $form->add('childLine', 'text',
                        array(
                            'label' => 'tisseo.paon.line_version.label.child_line',
                            'mapped' => false,
                            'read_only' => true,
                            'data' => $lineNumber
                        )
                    );
                }
            )
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
                'name',
                'text',
                array(
                    'label' => 'tisseo.paon.line_version.label.name'
                )
            )
            ->add(
                'forwardDirection',
                'text',
                array(
                    'label' => 'tisseo.paon.line_version.label.forward'
                )
            )
            ->add(
                'backwardDirection',
                'text',
                array(
                    'label' => 'tisseo.paon.line_version.label.backward'
                )
            )
            ->add(
                'fgColor',
                'entity',
                array(
                    'label' => 'tisseo.paon.line_version.label.fg_color',
                    'class' => 'TisseoEndivBundle:Color',
                    'property' => 'name',
                    'empty_value' => '',
                    'required' => true,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->where("c.name IN ('Blanc','Noir')");
                    }
                )
            )
            ->add(
                'bgColor',
                'entity',
                array(
                    'label' => 'tisseo.paon.line_version.label.bg_color',
                    'class' => 'TisseoEndivBundle:Color',
                    'property' => 'name',
                    'empty_value' => '',
                    'required' => true,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('c')
                            ->orderBy('c.name', 'ASC');
                    }
                )
            )
            ->add(
                'Property',
                'entity',
                array(
                    'class' => 'TisseoEndivBundle:Property',
                    'property' => 'name',
                    'expanded' => true,
                    'multiple' => true,
                    'label' => 'tisseo.paon.line_version.label.properties'
                )
            )
            ->add(
                'depot',
                'entity',
                array(
                    'class' => 'TisseoEndivBundle:Depot',
                    'property' => 'longName',
                    'empty_value' => '',
                    'label' => 'tisseo.paon.line_version.label.depot'
                )
            )
            ->add(
                'button_schematic',
                'button',
                array(
                    'label' => 'tisseo.paon.line_version.label.choose_schematic',
                    'attr' => array(
                        'class' => 'choose-schematic btn-default'
                    )
                )
            )
            ->add(
                'numAudio',
                'integer',
                array(
                    'label' => 'tisseo.paon.line_version.label.num_audio'
                )
            )
            ->add(
                'text2speech',
                'text',
                array(
                    'label' => 'tisseo.paon.line_version.label.text2speech'
                )
            )

            /*
             * This field is mandatory but must be hidden
             * it will be hidden and positioned with css for allow use html5 validation
             * See classes specified into attribute : class
             * TODO :  This system will be removed
             */
            ->add(
                $builder->create(
                    'schematic',
                    'hidden',
                    array(
                        'attr' => array(
                            'class' => 'input-hidden schematic-min-width-field'
                        ),
                    )
                )->addModelTransformer($transformer)
            )
            ->add(
                'comment',
                'textarea',
                array(
                    'label' => 'tisseo.paon.line_version.label.comment',
                    'required' => false
                )
            )
            ->add(
                'modifications',
                'collection',
                array(
                    'label' => 'tisseo.paon.line_version.label.modifications',
                    'type' => new ModificationType(),
                    'allow_add' => true,
                    'by_reference' => false
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
                'validation_groups' => array('LineVersion')
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
        return 'paon_line_version';
    }
}
