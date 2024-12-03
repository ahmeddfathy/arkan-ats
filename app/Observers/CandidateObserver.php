<?php

namespace App\Observers;

use App\Models\Candidate;
use App\Jobs\ProcessCandidateAIEvaluation;
use Illuminate\Support\Facades\Log;

class CandidateObserver
{
    public function created(Candidate $candidate)
    {
        // Only dispatch for candidates with is_process = 0
        if ($candidate->is_process == 0) {
            ProcessCandidateAIEvaluation::dispatch($candidate);
            Log::info('AI evaluation job dispatched for candidate: ' . $candidate->id);
        }
    }
}
