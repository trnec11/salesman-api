<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class SalesmanCollection
 * @package App\Http\Resources
 */
class SalesmanCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'links' => [
                'first' => '/salesmen?page=1',
                'last' => '/salesmen?page=3',
                'prev' => '/salesmen?page=' . ($request['page'] > 1 ? $request['page'] - 1 : 1),
                'next' => '/salesmen?page=' . $request['page'] + 1,
            ],
        ];
    }
}
