<?php

/*
 * This file is part of the Klipper package.
 *
 * (c) François Pluchino <francois.pluchino@klipper.dev>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Klipper\Module\ProductBundle\Doctrine\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\UnitOfWork;
use Klipper\Module\ProductBundle\Model\Traits\SelectPriceListableInterface;

/**
 * Doctrine subscriber for the select pricelistable.
 *
 * @author François Pluchino <francois.pluchino@klipper.dev>
 */
class SelectPriceListableSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::onFlush,
        ];
    }

    public function onFlush(OnFlushEventArgs $args): void
    {
        $em = $args->getEntityManager();
        $uow = $em->getUnitOfWork();

        foreach ($uow->getScheduledEntityInsertions() as $entity) {
            $this->updatePriceList($uow, $entity);
        }

        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            $this->updatePriceList($uow, $entity);
        }
    }

    private function updatePriceList(UnitOfWork $uow, object $entity): void
    {
        if ($entity instanceof SelectPriceListableInterface) {
            if (null !== $old = $entity->getPriceList()) {
                $entity->setPriceList(null);
                $uow->propertyChanged($entity, 'priceList', $old, null);
            }
        }
    }
}
