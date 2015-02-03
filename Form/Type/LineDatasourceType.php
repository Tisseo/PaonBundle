<?php

namespace Tisseo\DatawarehouseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LineDatasourceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'datasource',
            'entity',
            array(
                'class' => 'TisseoDatawarehouseBundle:Datasource',
                'property' => 'name',
                'label' => 'datasource.labels.name'
            )
        );
        $builder->add(
            'code',
            'text',
            array(
                'label' => 'datasource.labels.code'
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
                'data_class' => 'Tisseo\DatawarehouseBundle\Entity\LineDatasource'
            )
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'datawarehouse_line_datasource';
    }
}
