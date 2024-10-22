<?php

namespace App\Http\Resources\User;

use App\Http\Resources\PositionStatus\PositionStatusResource;
use App\Http\Resources\PositionStatus\ShowPositionStatusResource;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\Role\ShowRoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            ...parent::toArray($request),
            'roles' => $this->roles?->name,
            'position_status' => $this->positionStatus?->name,
        ];
    }
}
