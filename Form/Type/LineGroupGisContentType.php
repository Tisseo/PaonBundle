<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Tisseo\PaonBundle\Form\DataTransformer\EntityToIntTransformer;

class LineGroupGisContentType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('line','entity', array(
            'label' => 'line_group_gis.labels.line',
            'property' => 'number',
            'em' => $options['em'],
            'class' => 'Tisseo\EndivBundle\Entity\Line',
        ));

        $builder->setAction($options['action']);
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