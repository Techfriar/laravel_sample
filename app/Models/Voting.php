<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voting extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'voter_id',
        'candidate_id',
        'technical_skill',
        'attitude',
        'problem_solving_skill',
        'total',
        'cumulative_rating'
    ];

    /**
     * Relation to include candidate datails
     */
    public function candidate()
    {
        return $this->hasMany(User::class, 'id', 'candidate_id');
    }
}
