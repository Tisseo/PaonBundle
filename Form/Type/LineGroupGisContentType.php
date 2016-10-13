<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Extension\Core\View\ChoiceView;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Tisseo\EndivBundle\Entity\LineGroupGisContent;

class LineGroupGisContentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'line',
                'entity',
                array(
                    'label' => 'tisseo.paon.line_group_gis.label.line',
                    'property' => 'number',
                    'em' => $options['em'],
                    'class' => 'Tisseo\EndivBundle\Entity\Line',
                    'empty_value' => '',
                    'attr' => array(
                        'class' => 'line-select'
                    )
                )
            )
            ->addEventListener(FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($options) {
                    $form = $event->getForm();
                    $lineGroupGisContent = $event->getData();

                    $schematics = $selectedSchematic = null;
                    if ($lineGroupGisContent instanceof LineGroupGisContent && $lineGroupGisContent->getLine() !== null)
                    {
                        $em = $options['em'];
                        $query = $em->createQuery("
                            SELECT s
                            FROM Tisseo\EndivBundle\Entity\Schematic s
                            WHERE s.line = :line
                            AND s.file IS NOT NULL
                            AND s.deprecated = FALSE
                        ")
                        ->setParameter('line', $lineGroupGisContent->getLine());
                        $schematics = $query->getResult();

                        foreach ($schematics as $schematic)
                        {
                            if ($schematic->getGroupGis() && $lineGroupGisContent->getLine() == $schematic->getLine())
                            {
                                $selectedSchematic = $schematic;
                                break;
                            }
                        }
                    }
                    $form
                        ->add(
                            'schematic',
                            'entity',
                            array(
                                'label' => 'tisseo.paon.line_group_gis.label.schematic',
                                'mapped' => false,
                                'class' => 'Tisseo\EndivBundle\Entity\Schematic',
                                'choices' => $schematics,
                                'property' => 'datestring',
                                'data' => $selectedSchematic,
                                'attr' => array(
                                    'class' => 'schematic-select'
                                )
                            )
                        )
                    ;
                }
            )
            ->add(
                'delete',
                'button'
            )
            ->setAction($options['action'])
        ;
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

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Tisseo\EndivBundle\Entity\LineGroupGisContent',
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
        return 'paon_line_group_gis_content';
    }
}
