<?php

namespace Tisseo\TidBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LineSchemaType extends AbstractType
{
    /** @var bool $isBatch */
    protected $isBatch;

    /**
     * @param bool $isBatch
     */
    public function __construct($isBatch = false)
    {
        $this->isBatch = $isBatch;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($this->isBatch) {

            $builder->add('deprecated','checkbox', array(
                'label' => false,
                'required' => false
            ));

        } else {

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

            $builder->add('deprecated','hidden', array(
                'data' => 0
            ));

            $builder->setAction($options['action']);
        }


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
