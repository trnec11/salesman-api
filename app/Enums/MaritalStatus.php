<?php
declare(strict_types=1);

namespace App\Enums;

/**
 * Class MaritalStatus
 * @package App\Service
 */
class MaritalStatus
{
    const M_MARITAL_STATUSES = [
        'single' => 'slobodný',
        'married' => 'ženatý',
        'divorced' => 'rozvedený',
        'widowed' => 'vdovec',
    ];

    const F_MARITAL_STATUSES = [
        'single' => 'slobodná',
        'married' => 'vydatá',
        'divorced' => 'rozvedená',
        'widowed' => 'vdova',
    ];

    const G_MARITAL_STATUSES = [
        'single' => 'slobodný / slobodná',
        'married' => 'ženatý / vydatá',
        'divorced' => 'rozvedený / rozvedená',
        'widowed' => 'vdovec / vdova',
    ];

    /**
     * @return string[]
     */
    public function get(): array
    {
        $result = [];
        $maritalStatuses = ['single', 'married', 'divorced', 'widowed'];

        foreach ($maritalStatuses as $maritalStatus) {
            $result['code'][] = $maritalStatus;
            $result['m'][] = self::M_MARITAL_STATUSES[$maritalStatus];
            $result['f'][] = self::F_MARITAL_STATUSES[$maritalStatus];
            $result['general'][] = self::G_MARITAL_STATUSES[$maritalStatus];
        }

        return $result;
    }
}
