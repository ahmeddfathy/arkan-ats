<?php

namespace App\Services;

use App\Models\Application;
use App\Models\Candidate;
use App\Models\Job;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ApplicationStoreService
{
    public function storeApplication(array $data): Application
    {
        try {
            DB::beginTransaction();

            $candidate = Candidate::findOrFail($data['candidate_id']);
            $job = Job::findOrFail($data['job_id']);

            // Verify candidate is processed and accepted
            if ($candidate->is_process !== 1) {
                throw new \Exception('Only processed and accepted candidates can be added to applications.');
            }

            // Create the application
            $application = new Application();
            $application->job_id = $job->id;
            $application->candidate_id = $candidate->id;
            $application->status = 'shortlisted'; // Automatically set to shortlisted for accepted candidates
            $application->notes = $data['notes'] ?? null;

            if (!$application->save()) {
                throw new \Exception('Failed to save application');
            }

            DB::commit();
            return $application;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to store application: ' . $e->getMessage());
            throw $e;
        }
    }
}
