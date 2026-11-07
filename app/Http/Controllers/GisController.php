<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GisController extends Controller
{
    public function index()
    {
        return view('map');
    }

    public function getGadmGeoJson(Request $request, string $level)
    {
        if (!in_array($level, ['0', '1', '2', '3', '4'])) {
            return response()->json(['message' => 'Level tidak valid'], 400);
        }

        // Path ke file JSON GADM sesuai level
        $filePath = storage_path("app/public/json/gadm/gadm41_IDN_{$level}.json");

        if (!file_exists($filePath)) {
            return response()->json([
                'message' => "File gadm41_IDN_{$level}.json tidak ditemukan pada path: {$filePath}"
            ], 404);
        }

        $fileMtime = filemtime($filePath);
        $etag = '"gadm41_IDN_' . $level . '_' . $fileMtime . '"';

        // Check ETag for 304 Not Modified
        if ($request->header('If-None-Match') === $etag) {
            return response('', 304, [
                'ETag' => $etag,
                'Cache-Control' => 'public, max-age=86400, stale-while-revalidate=604800',
            ]);
        }

        $acceptEncoding = $request->header('Accept-Encoding', '');
        $supportsGzip = str_contains($acceptEncoding, 'gzip');

        $headers = [
            'Content-Type' => 'application/json',
            'Cache-Control' => 'public, max-age=86400, stale-while-revalidate=604800',
            'ETag' => $etag,
            'Vary' => 'Accept-Encoding',
        ];

        // Serve or generate gzip precompressed cache when supported
        if ($supportsGzip) {
            $gzPath = storage_path("app/public/json/gadm/gadm41_IDN_{$level}.json.gz");
            if (!file_exists($gzPath) || filemtime($gzPath) < $fileMtime) {
                $rawContent = file_get_contents($filePath);
                $compressed = gzencode($rawContent, 6);
                file_put_contents($gzPath, $compressed);
            }

            $headers['Content-Encoding'] = 'gzip';
            $headers['Content-Length'] = filesize($gzPath);

            return response(file_get_contents($gzPath), 200, $headers);
        }

        return response()->file($filePath, $headers);
    }
}

