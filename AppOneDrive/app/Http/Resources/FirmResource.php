<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FirmResource extends JsonResource
{
    public static $wrap = 'company';
    public function toArray(Request $request): array
    {
        return[
            'PIB' => $this->resource->PIB,
            'name' => $this->resource->Name,
            'adress' => $this->resource->Address,
            'founded' => $this->resource->CreatedAt,
            'founder' => new UserResource($this->resource->user)
        ];
    }
}
