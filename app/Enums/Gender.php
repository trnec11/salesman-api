<?php
declare(strict_types=1);

namespace App\Enums;

use App\Helpers\Diacritics;

class Gender
{
    const GENDER_STATUSES = [
        'm' => 'muz',
        'f' => 'zena',
    ];

    /**
     * @return string[]
     */
    public function get(): array
    {
        $result = [];
        $genderCodes = ['m', 'f'];

        foreach ($genderCodes as $genderCode) {
            $result['code'][] = $genderCode;
            $result['name']['m'][] = self::GENDER_STATUSES[$genderCode];
            $result['name']['f'][] = self::GENDER_STATUSES[$genderCode];
        }

        return $result;
    }

    /**
     * @param string $gender
     * @return string
     */
    public function findGender(string $gender): string
    {
        if (in_array($gender, array_keys(self::GENDER_STATUSES))) {
            return $gender;
        }

        if ($key = array_search(Diacritics::replaceDiacritics($gender), self::GENDER_STATUSES)) {
            return self::GENDER_STATUSES[$key];
        }

        return '';
    }
}
