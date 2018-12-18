<?php

namespace Tisseo\PaonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Tisseo\EndivBundle\Entity\LineVersion;
use Tisseo\CoreBundle\Form\DataTransformer\EntityToIntTransformer;
use Doctrine\Common\Persistence\ObjectManager;
use Tisseo\EndivBundle\Entity\PrintingType as EntityPrintingType;

class PrintingType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var ObjectManager $om */
        $om = $options['em'];
        $entityTransformer = new EntityToIntTransformer($om);
        $entityTransformer->setEntityClass('Tisseo\\EndivBundle\\Entity\\LineVersion');
        $entityTransformer->setEntityRepository('TisseoEndivBundle:LineVersion');
        $entityTransformer->setEntityType('lineVersion');


        $printing_type = $om->getRepository(EntityPrintingType::class)->findAll();
        $printingTyeChoiceList = [];
        foreach ($printing_type as $key => $item) {
          $printingTyeChoiceList[$item->getId()] = 'tisseo.paon.printing_type.label.' . $item->getLabel();
        }

        $builder
            ->add(
                'quantity',
                'number',
                array(
                    'label' => 'tisseo.paon.printing.label.quantity',
                    'precision' => 0
                )
            )
            ->add(
                'date',
                'tisseo_datepicker',
                array(
                    'label' => 'tisseo.paon.printing.label.date',
                    'attr' => array(
                        'data-from-date' => true
                    )
                )
            )
            ->add(
                'comment',
                'textarea',
                array(
                    'label' => 'tisseo.paon.printing.label.comment',
                    'required' => false
                )
            )
            ->add(
                $builder->create(
                    'lineVersion',
                    'hidden'
                )->addModelTransformer($entityTransformer)
            )
            ->add(
              'printingType',
              'choice',
              [
                'choices' => $printingTyeChoiceList,
                'placeholder' => 'tisseo.paon.printing_type.label.your_choice',
                'label' => 'tisseo.paon.printing_type.label.printing_type',
                'required' => false,
              ]
            )
            ->setAction($options['action'])
        ;

        $builder->get('printingType')->addModelTransformer(new CallbackTransformer(
          function($entity2int) {
            if (null === $entity2int) {
              return null;
            } else {
              $entity2int->getPrintingType()->getId();
            }
          },
          function($int2entity) use ($om) {
            if (!$int2entity) return;
            $printingType = $om->getRepository(EntityPrintingType::class)->findOneBy(['id' => $int2entity]);
            return $printingType;
          }
        ));

    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'Tisseo\EndivBundle\Entity\Printing'
            ))
            ->setRequired(array(
                'em',
            ))
            ->setAllowedTypes(array(
                'em' => 'Doctrine\Common\Persistence\ObjectManager',
            ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'paon_printing';
    }
}
