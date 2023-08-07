<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'image_name' => $this->image_name,
            'profile_pic_url' => $this->profile_pic_url,
            'is_admin' => $this->is_admin,
            'is_evaluated'=>$this->is_evaluated,
            'status' => $this->status,
            'voting' => $this->voting
            
        ];    
    }
}