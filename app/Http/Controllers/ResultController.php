<?php

namespace App\Http\Controllers;

use App\Http\Requests\Result\ShowResultRequest;
use App\Http\Resources\ResultResource;
use App\Repositories\Vote\VoteRepositoryInterface as VoteRepo;

class ResultController extends Controller
{
    
    /**
     * @OA\Post(
     *      path="/get_result",
     *      operationId="getResult",
     *      tags={"Result Management"},
     *      summary="Get monthly result",
     *      security={
     *         {"bearerAuth": {}}
     *      },
     *      @OA\Parameter(
     *          required=true,
     *          name="month",
     *          in="query",
     *          description="Enter month (January => 1 - December => 12)",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Parameter(
     *          required=true,
     *          name="year",
     *          in="query",
     *          description="Enter year",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
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
     * To get montly result
     */
    public function getResult(ShowResultRequest $request,VoteRepo $voteRepo)
    {
        $result = $voteRepo->monthlyResult($request->month,$request->year);
        if(!empty($result)){
            $data = ResultResource::collection($result);
            $response = ['status' => true,  'message' => 'Monthly result', 'data' => $data];
            return response()->json($response, 200);
        }
        $response = ['status' => false, 'message' => 'Unable to fetch monthly result', 'data' => []];
        return response()->json($response, 200);
    }


    /**
     * @OA\Get(
     *      path="/get_date",
     *      operationId="getDate",
     *      tags={"Result Management"},
     *      summary="Get Date",
     *      security={
     *         {"bearerAuth": {}}
     *      },
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
     * To get date of first voting record
     */
    public function showResultMonth(VoteRepo $voteRepo)
    {
        $date = $voteRepo->getfirstDataDate();
        if(!empty( $date)){
            $response = ['status' => true,  'message' => 'First voting date', 'data' => $date];
            return response()->json($response, 200);
        }
        $response = ['status' => false, 'message' => 'Unable to fetch first voting date', 'data' => []];
        return response()->json($response, 200);
    }

}