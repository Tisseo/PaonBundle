<?php

namespace Tisseo\TidBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LineSchemaType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name','hidden', array(
            'label' => 'line_schema.labels.name',
        ));

        $date = new \DateTime('now');
        $builder->add('date','text', array(
            'label' => 'line_schema.labels.date',
            'read_only' => true,
            'data' => $date->format('d/m/Y'),
            'mapped' => false
        ));

        $builder->add('comment','textarea', array(
            'label' => 'line_schema.labels.comment'
        ));

        $builder->add('file','file', array(
            'label' => 'line_schema.labels.file',
            'required' => false
        ));

        $builder->add('save', 'submit', array(
            'attr' => array('class' => 'btn btn-success'),
            'label' => 'line_schema.labels.submit_file'
        ));

        $builder->setAction($options['action']);
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
        return 'tid_line_schema';
    }
}
