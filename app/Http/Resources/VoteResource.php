<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VoteResource extends JsonResource
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
            'voter_id' => $this->voter_id,
            'candidate_id' => $this->candidate_id,
            'technical_skill' => $this->technical_skill,
            'attitude' => $this->attitude,
            'problem_solving_skill' => $this->problem_solving_skill,
            'total' => $this->total,
            'cumulative rating' => $this->cumulative_rating,
            'created_at'=>$this->created_at,
        ];    
    }
}