<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ClaudeVisionService
{
    protected $apiKey;
    protected $apiUrl;
    protected $model;

    public function __construct()
    {
        $this->apiKey = env('CLAUDE_API_KEY');
        $this->apiUrl = 'https://api.anthropic.com/v1/messages';
        $this->model = 'claude-3-sonnet-20240229'; // Update with the latest model as needed
        
        // Log configuration for debugging
        Log::info('ClaudeVisionService initialized');
        Log::info('API URL: ' . $this->apiUrl);
        Log::info('Model: ' . $this->model);
        Log::info('API Key exists: ' . ($this->apiKey ? 'Yes' : 'No'));
        Log::info('API Key first 5 chars: ' . (strlen($this->apiKey) > 5 ? substr($this->apiKey, 0, 5) : 'Too short'));
        Log::info('Current environment: ' . app()->environment());
    }

    /**
     * Analyze images with Claude Vision API
     *
     * @param array $data Analysis data containing images and contextual information
     * @return array Structured analysis results
     */
    public function analyzeImages(array $data)
    {
        try {
            Log::info('Starting Claude Vision analysis');
            
            if (empty($this->apiKey)) {
                Log::warning('Claude API Key is not set in .env file');
                throw new \Exception('API Key not configured. Please set CLAUDE_API_KEY in .env file.');
            }
            
            // Prepare images for API request
            $contents = [];
            
            // Add system message for context
            $systemPrompt = "You are a bird disease detection assistant specialized in Murai Batu (White-rumped Shama) birds. Your task is to analyze the images and additional information provided by the user to detect possible diseases or health issues affecting the bird. Always provide your analysis in Bahasa Indonesia.";
            
            Log::info('Processing ' . count($data['images']) . ' images');
            
            // Process each image
            foreach ($data['images'] as $imagePath) {
                Log::info('Processing image: ' . $imagePath);
                try {
                    $imageFullPath = Storage::disk('public')->path($imagePath);
                    Log::info('Full image path: ' . $imageFullPath);
                    
                    if (!file_exists($imageFullPath)) {
                        Log::error('Image file does not exist: ' . $imageFullPath);
                        throw new \Exception('Image file not found: ' . $imageFullPath);
                    }
                    
                    $mimeType = mime_content_type($imageFullPath);
                    Log::info('Image mime type: ' . $mimeType);
                    
                    $imageContent = file_get_contents($imageFullPath);
                    if ($imageContent === false) {
                        Log::error('Failed to read image file: ' . $imageFullPath);
                        throw new \Exception('Failed to read image file: ' . $imageFullPath);
                    }
                    
                    $base64Image = base64_encode($imageContent);
                    Log::info('Image encoded to base64, length: ' . strlen($base64Image));
                    
                    // Add image to contents
                    $contents[] = [
                        'type' => 'image',
                        'source' => [
                            'type' => 'base64',
                            'media_type' => $mimeType,
                            'data' => $base64Image
                        ]
                    ];
                    
                    Log::info('Image added to contents array');
                } catch (\Exception $imageEx) {
                    Log::error('Error processing image: ' . $imageEx->getMessage());
                    throw $imageEx;
                }
            }
            
            // Build user prompt
            Log::info('Building user prompt');
            $userPrompt = "Tolong analisis kesehatan burung Murai Batu (White-rumped Shama) dalam gambar ini berdasarkan informasi berikut:

Gejala yang terlihat: {$data['symptoms']}";

            // Add behaviors if available
            if (!empty($data['behaviors'])) {
                $behaviorText = implode(', ', $data['behaviors']);
                $userPrompt .= "\n\nPerubahan perilaku: {$behaviorText}";
            }

            // Add diet info if available
            if (!empty($data['diet_info'])) {
                $userPrompt .= "\n\nInformasi pakan & makan: {$data['diet_info']}";
            }

            // Add environment info if available
            if (!empty($data['environment_info'])) {
                $userPrompt .= "\n\nInformasi lingkungan kandang: {$data['environment_info']}";
            }

            // Add health history if available
            if (!empty($data['health_history'])) {
                $userPrompt .= "\n\nRiwayat kesehatan: {$data['health_history']}";
            }

            // Add instructions for structured response
            $userPrompt .= "\n\nBerikan analisis mendetail dalam format berikut:
1. Kemungkinan penyakit (urutkan dari probabilitas tertinggi ke terendah, dengan persentase kemungkinan untuk setiap penyakit)
2. Diagnosis lengkap (jelaskan analisis detil berdasarkan gambar dan informasi yang diberikan)
3. Rekomendasi penanganan (minimal 3 rekomendasi spesifik)

Pastikan analisismu akurat berdasarkan pengetahuan umum tentang penyakit burung Murai Batu dan sesuai dengan gejala yang terlihat di gambar.";

            Log::info('User prompt length: ' . strlen($userPrompt));
            Log::info('User prompt snippet: ' . Str::limit($userPrompt, 200));

            // Add user message to contents
            $contents[] = [
                'type' => 'text',
                'text' => $userPrompt
            ];

            // Prepare API request payload
            $payload = [
                'model' => $this->model,
                'system' => $systemPrompt,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $contents
                    ]
                ],
                'max_tokens' => 4000
            ];

            Log::info('Payload prepared, content array size: ' . count($contents));
            
            // Make API request
            Log::info('Sending request to Claude API: ' . $this->apiUrl);
            
            try {
                $response = Http::withHeaders([
                    'x-api-key' => $this->apiKey,
                    'anthropic-version' => '2023-06-01',
                    'content-type' => 'application/json'
                ])
                ->withoutVerifying()
                ->post($this->apiUrl, $payload);
                
                Log::info('Response received from Claude API');
                Log::info('Response status: ' . $response->status());
                
                // Check for errors
                if ($response->failed()) {
                    Log::error('Claude API error: ' . $response->status());
                    Log::error('Claude API error body: ' . $response->body());
                    throw new \Exception('Error connecting to Claude API: ' . $response->status() . ' - ' . $response->body());
                }
                
                Log::info('Successful API response');
                $responseData = $response->json();
                
                if (!isset($responseData['content']) || !isset($responseData['content'][0]) || !isset($responseData['content'][0]['text'])) {
                    Log::error('Unexpected response structure: ' . json_encode($responseData));
                    throw new \Exception('Unexpected response structure from Claude API');
                }
                
                // Extract the text response
                $analysisText = $responseData['content'][0]['text'];
                Log::info('Analysis text length: ' . strlen($analysisText));
                Log::info('Analysis text snippet: ' . Str::limit($analysisText, 200));
                
                // Parse the structured response
                $result = $this->parseAnalysisResponse($analysisText);
                Log::info('Analysis parsed successfully');
                
                return $result;
                
            } catch (\Exception $httpEx) {
                Log::error('HTTP request error: ' . $httpEx->getMessage());
                throw $httpEx;
            }
            
        } catch (\Exception $e) {
            Log::error('Claude Vision Service error: ' . $e->getMessage());
            Log::error('Exception trace: ' . $e->getTraceAsString());
            
            // FOR DEBUGGING: Always force using API in all environments
            // Comment or remove this to go back to normal behavior
            throw $e;
            
            // Original fallback behavior - only use in non-production
            // if (app()->environment('production')) {
            //     throw $e;
            // }
            
            Log::warning('Using mock response due to error');
            return $this->getMockResponse();
        }
    }

    /**
     * Parse the text response from Claude into structured data
     */
    protected function parseAnalysisResponse(string $text): array
    {
        Log::info('Parsing analysis response');
        
        // Initialize result structure
        $result = [
            'possible_diseases' => [],
            'diagnosis' => '',
            'recommendations' => []
        ];

        // Extract possible diseases with probabilities
        if (preg_match('/Kemungkinan penyakit.*?\n(.*?)(?:\n\n|\n\d\.)/s', $text, $matches)) {
            $diseasesText = $matches[1];
            Log::info('Extracted diseases text: ' . $diseasesText);
            
            $diseaseLines = explode("\n", $diseasesText);
            
            foreach ($diseaseLines as $line) {
                // Skip empty lines
                if (empty(trim($line))) continue;
                
                // Extract disease name and probability
                if (preg_match('/[•\-\*]?\s*([^()]+)\s*(?:\((\d+)%\))?/', $line, $diseaseMatch)) {
                    $diseaseName = trim($diseaseMatch[1]);
                    $probability = isset($diseaseMatch[2]) ? (int)$diseaseMatch[2] : 0;
                    
                    // If no percentage, estimate based on position
                    if ($probability === 0) {
                        $count = count($result['possible_diseases']);
                        
                        if ($count === 0) $probability = 85;
                        else if ($count === 1) $probability = 65;
                        else $probability = 40;
                    }
                    
                    $result['possible_diseases'][] = [
                        'name' => $diseaseName,
                        'probability' => $probability
                    ];
                    
                    Log::info('Parsed disease: ' . $diseaseName . ' with probability: ' . $probability);
                }
            }
        } else {
            Log::warning('Failed to extract diseases section');
        }
        
        // If no diseases were parsed, add a default entry
        if (empty($result['possible_diseases'])) {
            Log::warning('No diseases found, adding default');
            $result['possible_diseases'][] = [
                'name' => 'Tidak dapat menentukan penyakit spesifik',
                'probability' => 100
            ];
        }
        
        // Sort diseases by probability (highest first)
        usort($result['possible_diseases'], function($a, $b) {
            return $b['probability'] <=> $a['probability'];
        });

        // Extract diagnosis
        if (preg_match('/Diagnosis lengkap.*?\n(.*?)(?:\n\n|\n\d\.)/s', $text, $matches)) {
            $result['diagnosis'] = trim($matches[1]);
            Log::info('Extracted diagnosis, length: ' . strlen($result['diagnosis']));
        } else {
            Log::warning('Failed to extract diagnosis section');
            $result['diagnosis'] = "Tidak dapat menghasilkan diagnosis lengkap.";
        }

        // Extract recommendations
        if (preg_match('/Rekomendasi penanganan.*?\n(.*?)(?:\n\n|$)/s', $text, $matches)) {
            $recommendationsText = $matches[1];
            Log::info('Extracted recommendations text: ' . $recommendationsText);
            
            $recommendationLines = explode("\n", $recommendationsText);
            
            foreach ($recommendationLines as $line) {
                // Skip empty lines
                if (empty(trim($line))) continue;
                
                // Extract recommendation text
                if (preg_match('/[•\-\*]?\s*(.+)/', $line, $recMatch)) {
                    $rec = trim($recMatch[1]);
                    $result['recommendations'][] = $rec;
                    Log::info('Parsed recommendation: ' . $rec);
                }
            }
        } else {
            Log::warning('Failed to extract recommendations section');
        }
        
        // Ensure at least one recommendation
        if (empty($result['recommendations'])) {
            Log::warning('No recommendations found, adding defaults');
            $result['recommendations'][] = "Konsultasikan dengan dokter hewan untuk penanganan yang tepat.";
            $result['recommendations'][] = "Jaga kondisi kandang tetap bersih dan nyaman.";
            $result['recommendations'][] = "Berikan nutrisi yang cukup dan air bersih.";
        }

        Log::info('Analysis parsing completed');
        return $result;
    }

    /**
     * Provide a mock response for development/testing
     */
    protected function getMockResponse(): array
    {
        Log::info('Returning mock response');
        return [
            'possible_diseases' => [
                [
                    'name' => 'Snot (Coryza)',
                    'probability' => 85
                ],
                [
                    'name' => 'Aspergillosis',
                    'probability' => 65
                ],
                [
                    'name' => 'Infeksi Saluran Pernapasan',
                    'probability' => 40
                ]
            ],
            'diagnosis' => "Berdasarkan gambar dan informasi yang diberikan, burung Murai Batu menunjukkan gejala-gejala yang konsisten dengan infeksi saluran pernapasan, kemungkinan besar Snot (Coryza). Terlihat adanya sekresi di sekitar hidung dan mata, serta postur burung yang menunjukkan ketidaknyamanan (bulu mengembang, kurang aktif). Perubahan perilaku seperti kurang aktif dan tidak berkicau juga mendukung diagnosis ini. Kondisi kandang yang lembab dan perubahan cuaca mungkin menjadi faktor pemicu.",
            'recommendations' => [
                "Isolasi burung dari burung lain untuk mencegah penyebaran penyakit.",
                "Konsultasikan dengan dokter hewan untuk mendapatkan antibiotik yang sesuai, biasanya Enrofloxacin atau Doxycycline diberikan untuk infeksi saluran pernapasan.",
                "Tempatkan burung di lingkungan yang hangat dan kering dengan sirkulasi udara yang baik.",
                "Berikan vitamin dan suplemen untuk meningkatkan daya tahan tubuh, seperti vitamin B kompleks.",
                "Jaga kebersihan kandang dengan desinfeksi rutin untuk mencegah infeksi sekunder."
            ]
        ];
    }
}