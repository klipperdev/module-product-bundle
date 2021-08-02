<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Module\ProductBundle\Controller;

use Klipper\Bundle\ApiBundle\Controller\ControllerHelper;
use Klipper\Component\Resource\Converter\ConverterRegistryInterface;
use Klipper\Module\ProductBundle\Exception\ProductCombinationCreatorException;
use Klipper\Module\ProductBundle\Form\Type\ProductCombinationCreatorType;
use Klipper\Module\ProductBundle\Product\ProductManagerInterface;
use Klipper\Module\ProductBundle\Representation\ProductCombinationCreator;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ApiProductCombinationController
{
    /**
     * Create the product combination with attributes from a reference.
     *
     * @Route(
     *     "/product_combinations/create-from-reference",
     *     methods={"POST"},
     *     defaults={
     *         "_priority": 10
     *     }
     * )
     */
    public function createFromReference(
        Request $request,
        ConverterRegistryInterface $converterRegistry,
        FormFactoryInterface $formFactory,
        ProductManagerInterface $productManager,
        ControllerHelper $helper,
        TranslatorInterface $translator
    ): Response {
        $form = $formFactory->create(ProductCombinationCreatorType::class);
        $data = $converterRegistry->get('json')->convert((string) $request->getContent());

        $form->submit($data, false);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                /** @var ProductCombinationCreator $data */
                $data = $form->getData();
                $combination = $productManager->createProductCombinationFromReference(
                    (string) $data->getReference(),
                    $data->getProduct(),
                    $data->getSeparator()
                );

                return $helper->view($combination);
            } catch (ProductCombinationCreatorException $e) {
                $form->addError(new FormError(
                    $e->getMessage(),
                    $e->getMessage(),
                    [],
                    null,
                    $e
                ));
            } catch (\Throwable $e) {
                $form->addError(new FormError(
                    $translator->trans('domain.database_error', [], 'KlipperResource')
                ));
            }
        }

        return $helper->view($helper->createView(
            $helper->formatFormErrors($form),
            Response::HTTP_BAD_REQUEST
        ));
    }
}
