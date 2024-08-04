<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AmazonController extends Controller
{

    public function search(Request $request)
    {
        $priceMin = $request->query('price_min');
        $priceMax = $request->query('price_max');
        $reviewMin = $request->query('review_min');
        $reviewMax = $request->query('review_max');

        // Prepare the data for sending to ChatGPT
        $query = "Find Amazon links for items priced between $priceMin and $priceMax, with reviews between $reviewMin and $reviewMax.";

        // Log the query for debugging

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.chatgpt.key'),
        ])->withOptions([
                    'verify' => false, // Disable SSL verification, recommended only for temporary use
                ])->post('https://api.openai.com/v1/engines/davinci-codex/completions', [
                    'prompt' => $query,
                    'max_tokens' => 100,
                ]);

        // Check if the response is successful
        if ($response->successful()) {
            // Log the raw response for debugging
            dd($response->json());

            $responseBody = $response->json();

            if (isset($responseBody['choices']) && count($responseBody['choices']) > 0) {
                $links = trim($responseBody['choices'][0]['text']);
                return response()->json(['links' => $links]);
            } else {
                return response()->json(['error' => 'No links found in the response from ChatGPT.'], 500);
            }
        } else {
            // Log the error response for debugging
            return response()->json(['error' => 'Failed to retrieve response from ChatGPT.'], 500);
        }
    }

}
