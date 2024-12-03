<?php

namespace App\Services;

use App\Models\Candidate;
use App\Models\Job;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CandidateProcessingService
{
    private $cvParser;
    private $googleAI;

    public function __construct(CVParserService $cvParser, GoogleAIService $googleAI)
    {
        $this->cvParser = $cvParser;
        $this->googleAI = $googleAI;
    }

    public function processUnprocessedCandidates(): array
    {
        $candidates = Candidate::where('is_process', 0)->get();
        $results = [];

        foreach ($candidates as $candidate) {
            try {
                DB::beginTransaction();

                $result = $this->processSingleCandidate($candidate);

                if (!$candidate->save()) {
                    throw new \Exception("Failed to update candidate status");
                }

                DB::commit();
                $results[] = $result;
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error("Error processing candidate {$candidate->id}: " . $e->getMessage());
                $results[] = [
                    'candidate_id' => $candidate->id,
                    'status' => 'error',
                    'error' => $e->getMessage()
                ];
            }
        }

        return $results;
    }

    private function processSingleCandidate(Candidate $candidate): array
    {
        $job = Job::findOrFail($candidate->job_id);

        $cvPath = public_path('/storage/' . $candidate->cv);
        $cvContent = $this->cvParser->parseCV($cvPath);

        if (empty(trim($cvContent))) {
            throw new \Exception("No content extracted from CV");
        }

        $evaluation = $this->googleAI->evaluateCandidate(
            $job->title,
            $job->description,
            $cvContent
        );

        if (!$evaluation['success']) {
            throw new \Exception($evaluation['error']);
        }

        $this->updateCandidateStatus($candidate, $evaluation['status']);

        return [
            'candidate_id' => $candidate->id,
            'status' => $evaluation['status'],
            'aiResponse' => $evaluation['response']
        ];
    }

    private function updateCandidateStatus(Candidate $candidate, string $status): void
    {
        // Update is_process to 1 for accepted, 2 for not accepted
        $newStatus = $status == 'accepted' ? 1 : 2;
        $candidate->is_process = $newStatus;
    }
}
