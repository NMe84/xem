<?php

/*
 * The Xross Entity Map
 * https://github.com/NMe84/xem
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Security;

class Role
{
    public const USER = 'ROLE_USER';
    public const POWER_USER = 'ROLE_POWER_USER';
    public const SUPER_USER = 'ROLE_SUPER_USER';
    public const ADMIN = 'ROLE_ADMIN';

    /**
     * Fetch the list of assignable user roles in ascending order of permissions.
     *
     * @return string[]
     */
    public static function all(): array
    {
        return [
            self::USER,
            self::POWER_USER,
            self::SUPER_USER,
            self::ADMIN,
        ];
    }
}
