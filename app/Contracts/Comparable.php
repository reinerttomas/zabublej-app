<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * @template T of Comparable
 */
interface Comparable
{
    /**
     * @param  T  $other
     */
    public function isEqual(self $other): bool;
}
