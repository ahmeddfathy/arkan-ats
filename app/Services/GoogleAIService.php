<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GoogleAIService
{
    private const API_URL = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash-latest:generateContent";
    private const API_KEY = "AIzaSyA2fMiJFlnUJCg1mqaumMhK28mOo2oNzHM";

    private $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function evaluateCandidate(string $jobTitle, string $jobDescription, string $cvContent): array
    {
        if (empty(trim($cvContent))) {
            throw new \Exception("CV content is empty");
        }

        $evaluationQuestion = $this->formatEvaluationQuestion($jobTitle, $jobDescription, $cvContent);

        try {
            $response = $this->client->post(self::API_URL . "?key=" . self::API_KEY, [
                'json' => [
                    'contents' => [
                        ['parts' => [['text' => $evaluationQuestion]]]
                    ]
                ],
                'headers' => ['Content-Type' => 'application/json']
            ]);

            $responseBody = json_decode($response->getBody(), true);
            $aiResponse = $responseBody['candidates'][0]['content']['parts'][0]['text'] ?? 'No response';

            return [
                'success' => true,
                'response' => $aiResponse,
                'status' => $this->determineStatus($aiResponse)
            ];
        } catch (\Exception $e) {
            Log::error('Google AI API Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    private function formatEvaluationQuestion(string $jobTitle, string $jobDescription, string $cvContent): string
    {
        return "Please evaluate if the candidate is qualified for the following position. " .
               "Respond with 'ACCEPTED:' followed by your explanation if they are qualified, " .
               "or 'NOT_ACCEPTED:' followed by your explanation if they are not qualified.\n\n" .
               "Job Title: {$jobTitle}\n" .
               "Job Description: {$jobDescription}\n\n" .
               "Resume Content:\n{$cvContent}";
    }

    private function determineStatus(string $aiResponse): string
    {
        $response = strtoupper(trim($aiResponse));
        return str_starts_with($response, 'ACCEPTED:') ? 'accepted' : 'not_accepted';
    }
}
