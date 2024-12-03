<?php

namespace App\Services;

use Smalot\PdfParser\Parser;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use Illuminate\Support\Facades\Log;

class CVParserService
{
    public function parseCV(string $cvPath): ?string
    {
        if (!file_exists($cvPath)) {
            throw new \Exception("CV file not found: {$cvPath}");
        }

        $extension = strtolower(pathinfo($cvPath, PATHINFO_EXTENSION));

        return match($extension) {
            'pdf' => $this->parsePDF($cvPath),
            'doc', 'docx' => $this->parseWord($cvPath),
            default => throw new \Exception("Unsupported file format: {$extension}")
        };
    }

    private function parsePDF(string $path): string
    {
        try {
            $parser = new Parser();
            $pdf = $parser->parseFile($path);
            $text = $pdf->getText();

            return $this->cleanText($text);
        } catch (\Exception $e) {
            Log::error("PDF parsing error: " . $e->getMessage());
            throw new \Exception("Failed to parse PDF file: " . $e->getMessage());
        }
    }

    private function parseWord(string $path): string
    {
        try {
            $phpWord = IOFactory::load($path);
            $text = '';

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $element) {
                    if ($element instanceof Text) {
                        $text .= $element->getText() . "\n";
                    } elseif ($element instanceof TextRun) {
                        foreach ($element->getElements() as $textElement) {
                            if ($textElement instanceof Text) {
                                $text .= $textElement->getText();
                            }
                        }
                        $text .= "\n";
                    }
                }
            }

            return $this->cleanText($text);
        } catch (\Exception $e) {
            Log::error("Word parsing error: " . $e->getMessage());
            throw new \Exception("Failed to parse Word file: " . $e->getMessage());
        }
    }

    private function cleanText(string $text): string
    {
        // Remove extra whitespace and normalize line endings
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);

        // Remove any non-printable characters
        $text = preg_replace('/[\x00-\x1F\x7F]/u', '', $text);

        return $text;
    }
}
