<?php
declare(strict_types=1);

namespace App\Services;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\TitlesAfter;
use App\Enums\TitlesBefore;
use App\Exceptions\ForbiddenErrorException;
use Illuminate\Support\Facades\Auth;

final class CodeListService
{
    public function __construct(
        private readonly TitlesBefore $titlesBefore,
        private readonly TitlesAfter $titlesAfter,
        private readonly Gender $gender,
        private readonly MaritalStatus $maritalStatus
    ) {
    }

    /**
     * @return array
     * @throws ForbiddenErrorException
     */
    public function list(): array
    {
        if (Auth::user()->tokenCan('server:read') === false) {
            throw new ForbiddenErrorException(
                'You are not allowed to perform this action on Salesman.'
            );
        }

        return [
            'marital_statuses' => $this->maritalStatus->get(),
            'genders' => $this->gender->get(),
            'titles_before' => $this->titlesBefore->get(),
            'titles_after' => $this->titlesAfter->get()
        ];
    }
}
