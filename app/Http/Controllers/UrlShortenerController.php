<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class UrlShortenerController extends Controller
{
    private function getUrlMap()
    {
        if (Storage::exists('urls.json')) {
            $data = json_decode(Storage::get('urls.json'), true);
            return $data ?: [];
        }
        return [];
    }

    private function saveUrlMap($urlMap)
    {
        Storage::put('urls.json', json_encode($urlMap));
    }

    private static $inMemoryMap = []; // For in-memory storage

    public function encode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url' => 'required|url',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $longUrl = $request->input('url');
        $shortCode = Str::random(6);

        if (Storage::exists('urls.json')) {
            $urlMap = $this->getUrlMap();
            $urlMap[$shortCode] = $longUrl;
            $this->saveUrlMap($urlMap);
        } else {
            self::$inMemoryMap[$shortCode] = $longUrl;
        }

        $shortUrl = url('/short/' . $shortCode);

        return response()->json(['short_url' => $shortUrl]);
    }

    public function decode(Request $request, $shortCode)
    {
        if (Storage::exists('urls.json')) {
            $urlMap = $this->getUrlMap();
            if (isset($urlMap[$shortCode])) {
                return response()->json(['original_url' => $urlMap[$shortCode]]);
            }
        } else {
            if (isset(self::$inMemoryMap[$shortCode])) {
                return response()->json(['original_url' => self::$inMemoryMap[$shortCode]]);
            }
        }

        return response()->json(['error' => 'Short URL not found'], 404);
    }

    public function redirect($shortCode)
    {
        if (Storage::exists('urls.json')) {
            $urlMap = $this->getUrlMap();
            if (isset($urlMap[$shortCode])) {
                return redirect()->away($urlMap[$shortCode]);
            }
        } else {
            if (isset(self::$inMemoryMap[$shortCode])) {
                return redirect()->away(self::$inMemoryMap[$shortCode]);
            }
        }

        abort(404);
    }

    public function showUrlMap()
    {
        if (Storage::exists('urls.json')) {
            return response()->json($this->getUrlMap());
        } else {
            return response()->json(self::$inMemoryMap);
        }

    }
}