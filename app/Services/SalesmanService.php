<?php
declare(strict_types=1);

namespace App\Services;

use App\Enums\Gender;
use App\Enums\MaritalStatus;
use App\Enums\TitlesAfter;
use App\Enums\TitlesBefore;
use App\Exceptions\AlreadyExistsException;
use App\Exceptions\ForbiddenErrorException;
use App\Exceptions\InputDataBadFormatErrorException;
use App\Exceptions\InputDataOutOfRangeErrorException;
use App\Exceptions\NotFoundException;
use App\Models\Salesman;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Class SalesmanService
 * @package App\Service
 */
final class SalesmanService
{
    /**
     * @param TitlesBefore $titlesBefore
     * @param TitlesAfter $titlesAfter
     * @param Gender $gender
     * @param MaritalStatus $maritalStatus
     */
    public function __construct(
        private readonly TitlesBefore $titlesBefore,
        private readonly TitlesAfter $titlesAfter,
        private readonly Gender $gender,
        private readonly MaritalStatus $maritalStatus
    ) {
    }

    /**
     * @throws ForbiddenErrorException
     * @throws InputDataBadFormatErrorException
     */
    public function list(array $attributes): LengthAwarePaginator
    {
        if (Auth::user()->tokenCan('server:read') === false) {
            throw new ForbiddenErrorException(
                'You are not allowed to perform this action on Salesman.'
            );
        }

        $validator = Validator::make($attributes, [
            'page' => 'required|numeric',
            'per_page' => 'required|numeric',
            'sort' => Rule::in([
                'uuid', '-uuid', 'first_name', '-first_name', 'last_name', '-last_name',
                'titles_before', '-titles_before', 'titles_after', '-titles_after',
                'prosight_id', '-prosight_id', 'email', '-email', 'gender', '-gender',
                'marital_status', '-marital_status'
            ])
        ]);

        if ($validator->fails()){
            throw new InputDataBadFormatErrorException(
                sprintf(
                    'Bad format of input data. Fields: %s',
                    implode(' ', $validator->errors()->all())
                )
            );
        }

        $column = $attributes['sort'];
        $direction = 'asc';

        if (str_contains($attributes['sort'], '-')) {
            $column = str_replace('-', '', $attributes['sort']);
            $direction = 'desc';
        }

        return Salesman::query()
            ->orderBy($column, $direction)
            ->paginate($attributes['per_page']);
    }

    /**
     * @throws AlreadyExistsException
     * @throws ForbiddenErrorException|InputDataBadFormatErrorException
     * @throws InputDataOutOfRangeErrorException
     */
    public function create(array $attributes): Builder|Model
    {
        if (Auth::user()->tokenCan('server:create') === false) {
            throw new ForbiddenErrorException(
                'You are not allowed to perform this action on Salesman.'
            );
        }

        $validator = Validator::make($attributes, [
            'first_name' => 'required',
            'last_name' => 'required',
            'titles_before' => ['string', Rule::in($this->titlesBefore->get())],
            'titles_after' => ['string', Rule::in($this->titlesAfter->get())],
            'prosight_id' => 'required|numeric',
            'email' => 'required|email:rfc',
            'gender' => ['required', Rule::in($this->gender->get()['code'])],
            'marital_status' => Rule::in($this->maritalStatus->get()['code']),
        ]);

        if ($validator->fails()){
            throw new InputDataBadFormatErrorException(
                sprintf(
                    'Bad format of input data. Fields: %s',
                    implode(' ', $validator->errors()->all())
                )
            );
        }

        if (strlen($attributes['prosight_id']) !== 5) {
            throw new InputDataOutOfRangeErrorException(
                sprintf('Input data out of range. Field prosight_id of value %s is out of range. Acceptable range for this field is 5.', $attributes['prosight_id'])
            );
        }

        if (Salesman::query()->where(['prosight_id' => $attributes['prosight_id']])->exists() ||
            Salesman::query()->where(['email' => $attributes['email']])->exists())
        {
            throw new AlreadyExistsException(
                sprintf("Couldn't create a salesman. Salesman with Prosight ID: %s already exists.", $attributes['prosight_id'])
            );
        }

        $attributes['uuid'] = Str::uuid();

        return Salesman::query()->create($attributes);
    }

    /**
     * @param array $attributes
     * @return Salesman
     * @throws ForbiddenErrorException
     * @throws InputDataBadFormatErrorException
     * @throws InputDataOutOfRangeErrorException
     * @throws NotFoundException
     */
    public function update(array $attributes): Salesman
    {
        if (Auth::user()->tokenCan('server:update') === false) {
            throw new ForbiddenErrorException(
                'You are not allowed to perform this action on Salesman.'
            );
        }

        $salesman = Salesman::query()->where(['uuid' => $attributes['uuid']])->first();

        if (is_null($salesman)) {
            throw new NotFoundException('Salesman not found');
        }

        $validator = Validator::make($attributes, [
            'uuid' => 'uuid',
            'first_name' => 'string',
            'last_name' => 'string',
            'titles_before' => ['string', Rule::in($this->titlesBefore->get())],
            'titles_after' => ['string', Rule::in($this->titlesAfter->get())],
            'prosight_id' => 'numeric',
            'email' => 'email:rfc',
            'gender' => Rule::in($this->gender->get()['code']),
            'marital_status' => Rule::in($this->maritalStatus->get()['code']),
        ]);

        if ($validator->fails()){
            throw new InputDataBadFormatErrorException(
                sprintf(
                    'Bad format of input data. Fields: %s',
                    implode(' ', $validator->errors()->all())
                )
            );
        }

        if (isset($attributes['prosight_id']) && strlen($attributes['prosight_id']) !== 5) {
            throw new InputDataOutOfRangeErrorException(
                sprintf('Input data out of range. Field prosight_id of value %s is out of range. Acceptable range for this field is 5.', $attributes['prosight_id'])
            );
        }

        $salesman->fill($attributes)->save();

        /* @var Salesman $salesman */
        return $salesman;
    }

    /**
     * @param array $attributes
     * @throws ForbiddenErrorException
     * @throws InputDataBadFormatErrorException
     * @throws NotFoundException
     */
    public function delete(array $attributes): void
    {
        if (Auth::user()->tokenCan('server:delete') === false) {
            throw new ForbiddenErrorException(
                'You are not allowed to perform this action on Salesman.'
            );
        }

        $validator = Validator::make($attributes, [
            'uuid' => 'uuid'
        ]);

        if ($validator->fails()){
            throw new InputDataBadFormatErrorException(
                sprintf(
                    'Bad format of input data. Fields: %s',
                    implode(' ', $validator->errors()->all())
                )
            );
        }

        $salesman = Salesman::query()->where(['uuid' => $attributes['uuid']])->first();

        if (is_null($salesman)) {
            throw new NotFoundException(sprintf('Salesman %s not found.', $attributes['uuid']));
        }

        $salesman->delete();
    }
}
