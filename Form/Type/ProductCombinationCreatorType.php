<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Module\ProductBundle\Form\Type;

use Klipper\Component\Metadata\MetadataManagerInterface;
use Klipper\Module\ProductBundle\Model\ProductCombinationInterface;
use Klipper\Module\ProductBundle\Representation\ProductCombinationCreator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ProductCombinationCreatorType extends AbstractType
{
    private MetadataManagerInterface $metadataManager;

    public function __construct(MetadataManagerInterface $metadataManager)
    {
        $this->metadataManager = $metadataManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $combinationMeta = $this->metadataManager->get(ProductCombinationInterface::class);
        $productField = $combinationMeta->getAssociation('product');

        $builder
            ->add('reference', TextType::class)
            ->add('product', $productField->getFormType(), $productField->getFormOptions())
            ->add('separator', TextType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductCombinationCreator::class,
        ]);
    }
}
