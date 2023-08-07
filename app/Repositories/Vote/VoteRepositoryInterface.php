<?php

namespace App\Repositories\Vote;
use App\Models\Voting;

interface VoteRepositoryInterface
{

     /**
     * Save a Vote
     *
     * @param array $voteData
     * @return Voting $vote|false
     */
     public function storeVote($voteData);

    /**
     * month check
     *
     * @param array $voterId
     * @param array $candidateId
     * @return Voting $month|false
     */
    public function monthCheck($voterId,$candidateId);

    /**
     * show monthly result
     *
     * @param array $month
     * @param array $year
     * @return Voting $monthlyResult
     */
    public function monthlyResult($month,$year);


    /**
     * To get date of first voting record
     */
    public function getfirstDataDate();
}