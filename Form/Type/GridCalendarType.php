<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Doctrine\ORM\EntityRepository;

class GridCalendarType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'name',
            'text',
            array(
                'label' => ' '
            )
        );
        $builder->add(
            'monday',
            'checkbox',
            array(
                'label' => 'grid_calendar.labels.monday',
                'required' => false
            )
        );
        $builder->add(
            'tuesday',
            'checkbox',
            array(
                'label' => 'grid_calendar.labels.tuesday',
                'required' => false
            )
        );
        $builder->add(
            'wednesday',
            'checkbox',
            array(
                'label' => 'grid_calendar.labels.wednesday',
                'required' => false
            )
        );
        $builder->add(
            'thursday',
            'checkbox',
            array(
                'label' => 'grid_calendar.labels.thursday',
                'required' => false
            )
        );
        $builder->add(
            'friday',
            'checkbox',
            array(
                'label' => 'grid_calendar.labels.friday',
                'required' => false
            )
        );
        $builder->add(
            'saturday',
            'checkbox',
            array(
                'label' => 'grid_calendar.labels.saturday',
                'required' => false
            )
        );
        $builder->add(
            'sunday',
            'checkbox',
            array(
                'label' => 'grid_calendar.labels.sunday',
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
