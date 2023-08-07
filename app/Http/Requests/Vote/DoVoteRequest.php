<?php

namespace App\Http\Requests\Vote;

use Illuminate\Foundation\Http\FormRequest;
use App\Repositories\Vote\VoteRepositoryInterface as VoteRepo;
use App\Repositories\User\UserRepositoryInterface as UserRepo;
use Illuminate\Http\Exceptions\HttpResponseException;

class DoVoteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(VoteRepo $voteRepo, UserRepo $userRepo)
    {
        $voterId =auth()->user()->id;
        $candidateId = request()->candidate_id;
        $isAdmin = $userRepo->getUser($candidateId)->is_admin;
        if(!$isAdmin){
            $monthCheck = $voteRepo-> monthCheck($voterId,$candidateId);
            if(!$monthCheck){
                return true;
            }
        }
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'candidate_id' => 'required|exists:users,id',
            'technical_skill' => 'required|numeric|min:1|max:10',
            'attitude' => 'required|numeric|min:1|max:10',
            'problem_solving_skill' => 'required|numeric|min:1|max:10',
        ];
    }
    
    /**
     * Handle a failed authorization attempt.
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     * @return void
     */
    protected function failedAuthorization()
    {
        $response = ['status' => false, 'message' => 'You are not authorised.', 'data' => []];
        throw new HttpResponseException(response()->json($response, 200));
    }

}