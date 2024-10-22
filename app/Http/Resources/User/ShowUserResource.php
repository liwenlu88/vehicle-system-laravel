<?php

namespace App\Http\Resources\User;

use App\Http\Resources\PositionStatus\ShowPositionStatusResource;
use App\Http\Resources\Role\ShowRoleResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowUserResource extends JsonResource
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
            'roles' => new ShowRoleResource($this->roles),
            'position_status' => new ShowPositionStatusResource($this->positionStatus)
        ];
    }
}
