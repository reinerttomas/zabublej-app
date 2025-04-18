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
    public function equal(self $other): bool;

    /**
     * @param  T  $other
     */
    public function notEqual(self $other): bool;

    /**
     * @param  list<T>  $others
     */
    public function equalAll(array $others): bool;

    /**
     * @param  list<T>  $others
     */
    public function notEqualAll(array $others): bool;
}
