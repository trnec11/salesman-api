<?php
declare(strict_types=1);

namespace App\Enums;

class Gender
{
    const GENDER_STATUSES = [
        'm' => 'muž',
        'f' => 'žena',
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
            $result['m'][] = self::GENDER_STATUSES[$genderCode];
            $result['f'][] = self::GENDER_STATUSES[$genderCode];
        }

        return $result;
    }
}
