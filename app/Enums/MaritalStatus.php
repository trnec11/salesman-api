<?php
declare(strict_types=1);

namespace App\Enums;

use App\Helpers\Diacritics;

/**
 * Class MaritalStatus
 * @package App\Service
 */
class MaritalStatus
{
    const M_MARITAL_STATUSES = [
        'single' => 'slobodny',
        'married' => 'zenaty',
        'divorced' => 'rozvedeny',
        'widowed' => 'vdovec',
    ];

    const F_MARITAL_STATUSES = [
        'single' => 'slobodna',
        'married' => 'vydata',
        'divorced' => 'rozvedena',
        'widowed' => 'vdova',
    ];

    const G_MARITAL_STATUSES = [
        'single' => 'slobodny / slobodna',
        'married' => 'zenaty / vydata',
        'divorced' => 'rozvedeny / rozveden',
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
            $result['name']['m'][] = self::M_MARITAL_STATUSES[$maritalStatus];
            $result['name']['f'][] = self::F_MARITAL_STATUSES[$maritalStatus];
            $result['name']['general'][] = self::G_MARITAL_STATUSES[$maritalStatus];
        }

        return $result;
    }

    /**
     * @param string $maritalStatus
     * @return string
     */
    public function findMaritalStatus(string $maritalStatus): string
    {
        if (in_array($maritalStatus, array_keys(self::M_MARITAL_STATUSES))) {
            return $maritalStatus;
        }

        $maritalStatus = Diacritics::replaceDiacritics($maritalStatus);

        if (in_array(Diacritics::replaceDiacritics($maritalStatus), self::M_MARITAL_STATUSES)) {
            foreach (self::M_MARITAL_STATUSES as $key => $item) {
                if ($item === Diacritics::replaceDiacritics($maritalStatus)) {
                    return $key;
                }
            }
        }

        if (in_array(Diacritics::replaceDiacritics($maritalStatus), self::F_MARITAL_STATUSES)) {
            foreach (self::F_MARITAL_STATUSES as $key => $item) {
                if ($item === Diacritics::replaceDiacritics($maritalStatus)) {
                    return $key;
                }
            }
        }

        if (in_array(Diacritics::replaceDiacritics($maritalStatus), self::G_MARITAL_STATUSES)) {
            foreach (self::G_MARITAL_STATUSES as $key => $item) {
                if ($item === Diacritics::replaceDiacritics($maritalStatus)) {
                    return $key;
                }
            }
        }

        return '';
    }
}
