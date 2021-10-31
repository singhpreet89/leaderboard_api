<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request): array
    {   
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'email'     => $this->email,
            'age'       => $this->age,
            'points'    => $this->points ?? 0,
            'address'   =>  [
                'line1'         => $this->line1,
                'line2'         => $this->line2,
                'city'          => $this->city,
                'province'      => $this->province,
                'country'       => $this->country,
                'postal_code'   => $this->postal_code,
            ], 
        ];
    }
}
