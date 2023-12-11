<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public static $wrap = 'user';
    public function toArray(Request $request): array
    {
        return [
            'firstname' => $this->resource->Name,
            'lastname' => $this->resource->Surname,
            'email' => $this->resource->Email
        ];
    }
}
