<?php

namespace Tisseo\TidBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

use Tisseo\TidBundle\Form\DataTransformer\EntityToIntTransformer;

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

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
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
            
            if ( empty($childLine) )
                $lineNumber = "";
            else
                $lineNumber = $childLine["number"];



            $form->add('childLine', 'text',
                array(
                    'label' => 'line_version.labels.child_line',
                    'mapped' => false,
                    'read_only' => true,
                    'data' => $lineNumber
                )
            );
        });

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
                'lineGroupContents',
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
            )
            ->add(
                'name',
                'text',
                array(
                    'label' => 'line_version.labels.name'
                )
            )
            ->add(
                'forwardDirection',
                'text',
                array(
                    'label' => 'line_version.labels.forward'
                )
            )
            ->add(
                'backwardDirection',
                'text',
                array(
                    'label' => 'line_version.labels.backward'
                )
            )
            ->add(
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
            )
            ->add(
                'bgColor',
                'entity',
                array(
                    'label' => 'line_version.labels.bg_color',
                    'class' => 'TisseoEndivBundle:Color',
                    'property' => 'name',
                    'empty_value' => '',
                    'required' => true
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
                    'label' => 'line_version.labels.properties'
                )
            )
            ->add(
                'depot',
                'text',
                array(
                    'label' => 'line_version.labels.depot'
                )
            )
            ->add(
                'button_schematic',
                'button',
                array(
                    'label' => 'line_version.labels.choose_schematic',
                    'attr' => array(
                        'class' => 'choose-schematic'
                    )
                )
            )

            /*
             * This field is mandatory but must be hidden
             * it will be hiddent and positioned with css for allow use html5 validation
             * See classes specified into attribute : class
             * TODO :  This system will be removed
             */
            ->add(
                $builder->create(
                    'schematic',
                    'text',
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
                    'label' => 'line_version.labels.comment',
                    'required' => false
                )
            )
            ->add(
                'modifications',
                'collection',
                array(
                    'label' => 'line_version.labels.modifications',
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
                'validation_groups' => 'Default'
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
