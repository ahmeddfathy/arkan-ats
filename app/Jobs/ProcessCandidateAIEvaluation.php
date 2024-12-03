<?php

namespace App\Jobs;

use App\Models\Candidate;
use App\Models\Application;
use App\Services\CVParserService;
use App\Services\GoogleAIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessCandidateAIEvaluation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $candidate;

    public function __construct(Candidate $candidate)
    {
        $this->candidate = $candidate;
    }

    public function handle(CVParserService $cvParser, GoogleAIService $googleAI)
    {
        try {
            // Only process candidates with is_process = 0
            if ($this->candidate->is_process !== 0) {
                return;
            }

            // Get the CV content
            $cvPath = public_path('/storage/' . $this->candidate->cv);
            $cvContent = $cvParser->parseCV($cvPath);

            // Get the job details
            $job = $this->candidate->job;

            // Send to AI for evaluation
            $evaluation = $googleAI->evaluateCandidate(
                $job->title,
                $job->description,
                $cvContent
            );

            // Create application with AI response
            Application::create([
                'job_id' => $this->candidate->job_id,
                'candidate_id' => $this->candidate->id,
                'status' => $evaluation['status'] === 'accepted' ? 'shortlisted' : 'rejected',
                'notes' => $evaluation['response']
            ]);

            // Update candidate status
            $this->candidate->is_process = $evaluation['status'] === 'accepted' ? 1 : 2;
            $this->candidate->save();

            Log::info('AI evaluation completed for candidate: ' . $this->candidate->id);
        } catch (\Exception $e) {
            Log::error('AI evaluation failed for candidate ' . $this->candidate->id . ': ' . $e->getMessage());
            throw $e;
        }
    }
}
