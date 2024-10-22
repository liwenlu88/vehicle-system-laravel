<?php

namespace App\Http\Resources\Permission;

use App\Http\Resources\Menu\ShowMenuResource;
use App\Http\Resources\Method\ShowMethodResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowPermissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'menus' => new showMenuResource($this->resource->menus),
            'methods' => ShowMethodResource::collection($this->resource->methods())
        ];
    }
}
