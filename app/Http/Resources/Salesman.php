<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Salesman extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'self' => sprintf('/salesman/%s', $this->uuid),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'display_name' => sprintf(
                '%s %s %s %s', $this->titles_before, $this->first_name, $this->last_name, $this->titles_after
            ),
            'titles_before' => $this->titles_before,
            'titles_after' => $this->titles_after,
            'prosight_id' => $this->prosight_id,
            'email' => $this->email,
            'phone' => $this->phone,
            'gender' => $this->gender,
            'marital_status' => $this->marital_status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
