<?php
declare(strict_types=1);

namespace App\Enums;

class TitlesBefore
{
    /**
     * @return string[]
     */
    public function get(): array
    {
        return [
            'code' => [
                'Bc.', 'Mgr.', 'Ing.', 'JUDr.', 'MVDr.', 'MUDr.', 'PaedDr.', 'prof.', 'doc.', 'dipl.', 'MDDr.', 'Dr.',
                'Mgr. art.', 'ThLic.', 'PhDr.', 'PhMr.', 'RNDr.', 'ThDr.', 'RSDr.', 'arch.', 'PharmDr.'
            ],
            'name' => [
                'Bc.', 'Mgr.', 'Ing.', 'JUDr.', 'MVDr.', 'MUDr.', 'PaedDr.', 'prof.', 'doc.', 'dipl.', 'MDDr.', 'Dr.',
                'Mgr. art.', 'ThLic.', 'PhDr.', 'PhMr.', 'RNDr.', 'ThDr.', 'RSDr.', 'arch.', 'PharmDr.'
            ],
        ];
    }
}
