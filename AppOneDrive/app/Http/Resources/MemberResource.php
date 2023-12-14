<?php

namespace App\Http\Resources;

use App\Models\Firm;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public static $wrap = 'employees';
    public function toArray(Request $request): array
    {
        return[
             'employer' => new UserResource($this->resource->user),
             'company' => Firm::find($request->PIB),
             'hired' => $this->resource->AddedAt,
             'role' => $this->resource->privileges
        ];
    }
}
