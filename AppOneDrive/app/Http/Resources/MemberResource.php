<?php

namespace App\Http\Resources;

use App\Models\Firm;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    public static $wrap = 'employees';
    public function toArray(Request $request): array
    {
        return[
             'employer' => User::find($this->resource->user_id),
             //'company' => Firm::find($request->PIB),
             'company' => $this->resource->firm_pib,
             'hired' => $this->resource->AddedAt,
             'role' => $this->resource->privileges
        ];
    }
}
