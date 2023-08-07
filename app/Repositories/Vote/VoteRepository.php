<?php

namespace App\Repositories\Vote;

use App\Models\Voting;
use Carbon\Carbon;

class VoteRepository implements VoteRepositoryInterface
{
     /**
     * Save a Vote
     *
     * @param array $voteData
     * @return Voting $vote|false
     */
    public function storeVote($voteData)
    {
        $vote = new Voting;
        foreach ($voteData as $key => $value) {
            $vote->$key = $value;
        }
        $vote->save();
        return $vote;
    }

    /**
     * month check
     *
     * @param array $voterId
     * @param array $candidateId
     * @return Voting $month|false
     */
    public function monthCheck($voterId, $candidateId)
    {
        $monthCheck = Voting::where('voter_id', $voterId)->where('candidate_id', $candidateId)->whereMonth('created_at', now()->month)->exists();
        return $monthCheck;
    }

    /**
     * show monthly result
     *
     * @param array $month
     * @param array $year
     * @return Voting $monthlyResult
     */
    public function monthlyResult($month, $year)
    {
        $monthlyResult = Voting::select('v1.candidate_id', 'latest_date', 'v1.total_cumulative_rating','id','voter_id')
        ->from(function ($query) use ($year, $month) {
            $query->select('candidate_id')
                ->selectRaw('SUM(cumulative_rating) as total_cumulative_rating')
                ->selectRaw('MAX(created_at) as latest_date')
                ->from('votings')
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $month)
                ->whereNull('deleted_at')
                ->groupBy('candidate_id');
        }, 'v1')
        ->join('votings', function ($join) {
            $join->on('votings.candidate_id', '=', 'v1.candidate_id')
                 ->on('votings.created_at', '=', 'v1.latest_date');
        })
        ->whereNull('votings.deleted_at')
        ->orderByDesc('v1.total_cumulative_rating')
        ->get();
        return $monthlyResult;
    }

    /**
     * To get date of first voting record
     */
    public function getfirstDataDate()
    {
        $firstRecordDate = Voting::select('created_at')
        ->orderBy('created_at', 'asc')
        ->value('created_at');
        $firstRecordDate = Carbon::parse($firstRecordDate);
        return $firstRecordDate;
    }
}