<?php

namespace App\Http\Controllers;

use App\Http\Requests\Vote\DoVoteRequest;
use App\Http\Resources\VoteResource;
use App\Repositories\Vote\VoteRepositoryInterface as VoteRepo;

class VottingController extends Controller
{

    /**
     * @OA\Post(
     *      path="/vote_now",
     *      operationId="voteNow",
     *      tags={"Votting Management"},
     *      summary="Do Vote",
     *      security={
     *         {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          required=true,
     *          name="candidate_id",
     *          in="query",
     *          description="Enter candidate id",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          required=true,
     *          name="technical_skill",
     *          in="query",
     *          description="Enter rating for technical skill (scale 1-10)",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Parameter(
     *          required=true,
     *          name="attitude",
     *          in="query",
     *          description="Enter rating for attitude (scale 1-10)",
     *          @OA\Schema(
     *              type="number"
     *          )
     *      ),
     *      @OA\Parameter(
     *          required=true,
     *          name="problem_solving_skill",
     *          in="query",
     *          description="Enter rating for problem solving skill (scale 1-10)",
     *          @OA\Schema(
     *              type="number"
     *          )
     *     ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\MediaType(
     *           mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *   )
     * )
     *
     *   
     * To vote an employee
     */
    public function doVote(DoVoteRequest $request, VoteRepo $voteRepo)
    {
        $voterId = auth()->user()->id;
        $votingData = [
            'voter_id' => $voterId,
            'candidate_id' => $request->candidate_id,
            'technical_skill' => $request->technical_skill,
            'attitude' => $request->attitude,
            'problem_solving_skill' => $request->problem_solving_skill,
            'total' => $request->technical_skill + $request->attitude +  $request->para_3,
            'cumulative_rating' => ($request->technical_skill + $request->attitude + $request->para_3) / 3,
        ];

        $storeVote = $voteRepo->storeVote($votingData);
        if (!empty($storeVote)) {
            $data = new VoteResource($storeVote);
            $response = ['status' => true,  'message' => 'Voted Successfully', 'data' => $data];
            return response()->json($response, 200);
        }
        $response = ['status' => false, 'message' => 'Unable to Vote', 'data' => []];
        return response()->json($response, 200);
    }
}
