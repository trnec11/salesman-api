<?php
declare(strict_types=1);

namespace App\Service;

use App\Exceptions\AlreadyExistsException;
use App\Exceptions\NotFoundException;
use App\Models\Salesman;
use Illuminate\Http\Response;

final class SalesmanService
{
    public function list()
    {

    }

    /**
     * @throws AlreadyExistsException
     */
    public function create(array $attributes): int
    {
        if (Salesman::query()->find(['prosight_id' => $attributes['prosight_id']])->exists() ||
            Salesman::query()->find(['email' => $attributes['email']])->exists())
        {
            throw new AlreadyExistsException("Couldn't create a salesman. Salesman already exists.");
        }

        Salesman::query()->create($attributes);

        return Response::HTTP_CREATED;
    }

    /**
     * @throws NotFoundException
     */
    public function update(string $uuid, array $attributes): int
    {
        $salesman = Salesman::query()->find(['uuid' => $uuid]);

        if (is_null($salesman)) {
            throw new NotFoundException('Salesman not found');
        }

        $salesman->fill($attributes)->save();

        return Response::HTTP_OK;
    }

    /**
     * @throws NotFoundException
     */
    public function delete(string $uuid): int
    {
        $salesman = Salesman::query()->find(['uuid' => $uuid]);

        if (is_null($salesman)) {
            throw new NotFoundException(sprintf('Salesman %s not found.', $uuid));
        }

        $salesman->delete();

        return Response::HTTP_NO_CONTENT;
    }
}
