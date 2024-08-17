<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;

class AmazonController extends Controller
{
    public function scrapeAmazon(Request $request)
    {
        $client = new Client();
        $keywords = urlencode($request->input('keywords'));
        $minPrice = $request->input('min_price', 0);
        $maxPrice = $request->input('max_price', PHP_INT_MAX);
        $minReview = $request->input('min_review', 0);
        $maxReview = $request->input('max_review', PHP_INT_MAX);

        $url = "https://www.amazon.com/s?k=" . $keywords;

        // ارسال درخواست به URL
        $crawler = $client->request('GET', $url);

        // استخراج لینک محصولات و اعمال فیلترها
        $products = $crawler->filter('.s-result-item')->each(function ($node) use ($minPrice, $maxPrice, $minReview, $maxReview) {
            $baseUrl = "https://www.amazon.com";
            $titleNode = $node->filter('h2 a');
            $priceNode = $node->filter('.a-price-whole');
            $reviewNode = $node->filter('.a-icon-alt');

            if ($titleNode->count() == 0 || $priceNode->count() == 0 || $reviewNode->count() == 0) {
                return null; // اگر هر یک از این عناصر وجود نداشت، null برگردان
            }

            $relativeUrl = $titleNode->attr('href');
            $fullUrl = $baseUrl . $relativeUrl;
            $title = $titleNode->text();

            // استخراج قیمت
            $price = floatval(str_replace(',', '', $priceNode->text()));

            // استخراج تعداد نظرات
            $reviewText = $reviewNode->text();
            $reviews = floatval(substr($reviewText, 0, 3));

            // اعمال فیلترهای قیمت و نظرات
            if ($price >= $minPrice && $price <= $maxPrice && $reviews >= $minReview && $reviews <= $maxReview) {
                return [
                    'title' => $title,
                    'link' => $fullUrl,
                    'price' => $price,
                    'reviews' => $reviews,
                ];
            }

            return null; // اگر محصول شرایط فیلترها را نداشت، null برگردان
        });

        // حذف محصولات null
        $filteredProducts = array_filter($products);

        return response()->json($filteredProducts);
    }
}
