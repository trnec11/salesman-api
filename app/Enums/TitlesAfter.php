<?php
declare(strict_types=1);

namespace App\Enums;

class TitlesAfter
{
    /**
     * @return string[]
     */
    public function get(): array
    {
        return [
            'CSc.', 'DrSc.', 'PhD.', 'ArtD.', 'DiS', 'DiS.art', 'FEBO', 'MPH', 'BSBA', 'MBA', 'DBA', 'MHA',
            'FCCA', 'MSc.', 'FEBU', 'LL.M'
        ];
    }
}
