<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GridCalendarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                'text'
            )
            ->add(
                'monday',
                'checkbox',
                array(
                    'required' => false
                )
            )
            ->add(
                'tuesday',
                'checkbox',
                array(
                    'required' => false
                )
            )
            ->add(
                'wednesday',
                'checkbox',
                array(
                    'required' => false
                )
            )
            ->add(
                'thursday',
                'checkbox',
                array(
                    'required' => false
                )
            )
            ->add(
                'friday',
                'checkbox',
                array(
                    'required' => false
                )
            )
            ->add(
                'saturday',
                'checkbox',
                array(
                    'required' => false
                )
            )
            ->add(
                'sunday',
                'checkbox',
                array(
                    'required' => false
                )
            )
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'Tisseo\EndivBundle\Entity\GridCalendar'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'paon_grid_calendar';
    }
}
