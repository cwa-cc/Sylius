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

namespace Sylius\Component\Core\Dashboard;

/**
 * @experimental
 */
final class SalesSummary implements SalesSummaryInterface
{
    public function __construct(
        /** @psalm-var array<string, string> */
        private array $intervalsSalesMap,
    ) {
    }

    public function getIntervals(): array
    {
        return array_keys($this->intervalsSalesMap);
    }

    public function getSales(): array
    {
        return array_values($this->intervalsSalesMap);
    }
}
