<?php

namespace App\Http\Controllers\Peternak;

use App\Http\Controllers\Controller;
use App\Services\ClaudeVisionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DeteksiPenyakitController extends Controller
{
    protected $claudeService;

    public function __construct(ClaudeVisionService $claudeService)
    {
        $this->claudeService = $claudeService;
    }

    /**
     * Display the main detection page
     */
    public function index(): \Illuminate\View\View
    {
        return view('peternak.deteksi-penyakit.index');
    }

    /**
     * Process the disease detection request
     */
    public function process(Request $request)
    {
        // Validate request
        $request->validate([
            'images.*' => 'required|image|mimes:jpeg,png,jpg|max:5120', // Max 5MB
            'images' => 'required|array|min:1|max:5',
            'symptoms' => 'required|string|min:20',
            'behavior' => 'nullable|array',
            'other_behavior' => 'nullable|string',
            'diet_info' => 'nullable|string',
            'environment_info' => 'nullable|string',
            'health_history' => 'nullable|string',
        ]);

        try {
            // Process images and save to storage
            $imagePaths = [];

            foreach ($request->file('images') as $imageFile) {
                $path = $imageFile->store('deteksi-penyakit', 'public');
                $imagePaths[] = $path;
            }

            // Prepare behavior data
            $behaviors = $request->behavior ?? [];
            if ($request->other_behavior) {
                $behaviors[] = $request->other_behavior;
            }

            // Format data for Claude Vision analysis
            $analysisData = [
                'images' => $imagePaths,
                'symptoms' => $request->symptoms,
                'behaviors' => $behaviors,
                'diet_info' => $request->diet_info,
                'environment_info' => $request->environment_info,
                'health_history' => $request->health_history,
            ];

            // Call Claude Vision Service
            $analysisResult = $this->claudeService->analyzeImages($analysisData);

            // Format results for view without saving to DB
            $result = [
                'images' => $imagePaths,
                'symptoms' => $request->symptoms,
                'behaviors' => $behaviors,
                'diet_info' => $request->diet_info,
                'environment_info' => $request->environment_info,
                'health_history' => $request->health_history,
                'possible_diseases' => $analysisResult['possible_diseases'],
                'diagnosis' => $analysisResult['diagnosis'],
                'recommendations' => $analysisResult['recommendations'],
                'created_at' => now(),
            ];

            // Store result in session for accessing in result page
            session(['detection_result' => $result]);

            // Redirect to result page
            return redirect()->route('peternak.deteksi-penyakit.result');

        } catch (\Exception $e) {
            // Log the error
            Log::error('Error processing detection: '.$e->getMessage());
            Log::error('Error trace: '.$e->getTraceAsString());

            // Handle exceptions
            return redirect()->route('peternak.deteksi-penyakit.index')
                ->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Display detection result
     */
    public function result()
    {
        $result = session('detection_result');

        if (! $result) {
            return redirect()->route('peternak.deteksi-penyakit.index')
                ->with('error', 'Tidak ada hasil deteksi untuk ditampilkan.');
        }

        return view('peternak.deteksi-penyakit.result', compact('result'));
    }
}
