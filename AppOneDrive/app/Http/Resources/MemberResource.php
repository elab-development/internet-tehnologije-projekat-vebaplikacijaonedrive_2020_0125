<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public static $wrap = 'employee';
    public function toArray(Request $request): array
    {
        return[
            'employer' => new UserResource($this->resource->user),
            'company' => new FirmResource($this->resource->firm),
            'hired' => $this->resource->AddedAt,
            'role' => $this->resource->privileges
        ];
    }
}
