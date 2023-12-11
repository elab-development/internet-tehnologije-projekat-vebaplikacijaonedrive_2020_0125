<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersResource extends JsonResource
{
    public static $wrap = 'users';
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
