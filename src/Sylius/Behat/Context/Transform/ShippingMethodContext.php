<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sylius\Behat\Context\Transform;

use Behat\Behat\Context\Context;
use Sylius\Component\Shipping\Repository\ShippingMethodRepositoryInterface;
use Webmozart\Assert\Assert;

final class ShippingMethodContext implements Context
{
    public function __construct(private ShippingMethodRepositoryInterface $shippingMethodRepository)
    {
    }

    /**
     * @Transform /^"([^"]+)" shipping method$/
     * @Transform /^shipping method "([^"]+)"$/
     * @Transform :shippingMethod
     */
    public function getShippingMethodByName($shippingMethodName)
    {
        $shippingMethods = $this->shippingMethodRepository->findByName($shippingMethodName, 'en_US');

        Assert::eq(
            count($shippingMethods),
            1,
            sprintf('%d shipping methods have been found with name "%s".', count($shippingMethods), $shippingMethodName),
        );

        return $shippingMethods[0];
    }
}
