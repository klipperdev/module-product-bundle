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
use Klipper\Component\Content\ContentManagerInterface;
use Klipper\Component\SecurityOauth\Scope\ScopeVote;
use Klipper\Contracts\Model\ImagePathInterface;
use Klipper\Module\ProductBundle\Model\ProductInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class ApiProductController
{
    /**
     * Upload the image of current organization.
     *
     * @Entity("id", class="App:Product")
     *
     * @Route("/products/{id}/upload", methods={"POST"})
     */
    public function uploadImage(
        ControllerHelper $helper,
        ContentManagerInterface $contentManager,
        ProductInterface $id
    ): Response {
        if (class_exists(ScopeVote::class)) {
            $helper->denyAccessUnlessGranted(new ScopeVote('meta/product'));
        }

        return $contentManager->upload('product_image', $id);
    }

    /**
     * Download the image of current organization.
     *
     * @Entity("id", class="App:Product")
     *
     * @Route("/products/{id}.{ext}", methods={"GET"})
     */
    public function downloadImage(
        ControllerHelper $helper,
        ContentManagerInterface $contentManager,
        ProductInterface $id
    ): Response {
        if (!$id instanceof ImagePathInterface) {
            throw $helper->createNotFoundException();
        }

        if (class_exists(ScopeVote::class)) {
            $helper->denyAccessUnlessGranted(new ScopeVote(['meta/product', 'meta/product.readonly'], false));
        }

        return $contentManager->downloadImage(
            'product_image',
            $id->getImagePath(),
            $id->getName()
        );
    }
}
