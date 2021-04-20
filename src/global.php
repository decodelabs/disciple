<?php

/**
 * @package Disciple
 * @license http://opensource.org/licenses/MIT
 */

declare(strict_types=1);

/**
 * global helpers
 */
namespace DecodeLabs\Disciple
{
    use DecodeLabs\Disciple;
    use DecodeLabs\Veneer;

    // Register the Veneer facade
    Veneer::register(Context::class, Disciple::class);
}
