<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DepotController extends Controller
{
    protected string $filePath;

    public function __construct()
    {
        $this->filePath = storage_path('app/depots.json');
    }

    /**
     * Data sampel default sesuai contoh pada Gambar Halaman 7 Modul GIS (Wilayah Indramayu)
     */
    protected function defaultSampleData(): array
    {
        return [
            [
                'id' => 'depot_indramayu',
                'name' => 'Depot Indramayu (Pusat)',
                'type' => 'depot',
                'lat' => -6.326389,
                'lng' => 108.320000,
                'description' => 'Titik awal distribusi utama (Depot Pusat)'
            ],
            [
                'id' => 'loc_patrol',
                'name' => 'Titik Patrol',
                'type' => 'lokasi',
                'lat' => -6.310600,
                'lng' => 107.994600,
                'description' => 'Titik tujuan wilayah barat'
            ],
            [
                'id' => 'loc_kandanghaur',
                'name' => 'Titik Kandanghaur',
                'type' => 'lokasi',
                'lat' => -6.353300,
                'lng' => 108.083100,
                'description' => 'Titik tujuan persinggahan jalur pantura'
            ],
            [
                'id' => 'loc_haurgeulis',
                'name' => 'Titik Haurgeulis',
                'type' => 'lokasi',
                'lat' => -6.458900,
                'lng' => 107.940900,
                'description' => 'Titik tujuan wilayah selatan'
            ],
            [
                'id' => 'loc_bangkir',
                'name' => 'Titik Bangkir',
                'type' => 'lokasi',
                'lat' => -6.402500,
                'lng' => 108.261900,
                'description' => 'Titik tujuan persimpangan Bangkir'
            ],
            [
                'id' => 'loc_jatibarang',
                'name' => 'Titik Jatibarang',
                'type' => 'lokasi',
                'lat' => -6.474400,
                'lng' => 108.314800,
                'description' => 'Titik tujuan simpul transportasi Jatibarang'
            ],
            [
                'id' => 'loc_karangampel',
                'name' => 'Titik Karangampel',
                'type' => 'lokasi',
                'lat' => -6.462200,
                'lng' => 108.452500,
                'description' => 'Titik tujuan wilayah timur'
            ],
        ];
    }

    /**
     * Membaca seluruh data depot / lokasi dari file JSON
     */
    protected function readData(): array
    {
        if (!File::exists($this->filePath)) {
            $data = $this->defaultSampleData();
            $this->writeData($data);
            return $data;
        }

        $content = File::get($this->filePath);
        $json = json_decode($content, true);

        return is_array($json) ? $json : [];
    }

    /**
     * Menyimpan data depot / lokasi ke file JSON
     */
    protected function writeData(array $data): void
    {
        $dir = dirname($this->filePath);
        if (!File::isDirectory($dir)) {
            File::makeDirectory($dir, 0755, true);
        }
        File::put($this->filePath, json_encode(array_values($data), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }

    /**
     * GET /api/depots
     */
    public function index()
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->readData()
        ]);
    }

    /**
     * POST /api/depots
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|in:depot,lokasi',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string|max:255',
        ]);

        $data = $this->readData();
        $newDepot = [
            'id' => 'pt_' . time() . '_' . mt_rand(100, 999),
            'name' => $validated['name'],
            'type' => $validated['type'],
            'lat' => (float) $validated['lat'],
            'lng' => (float) $validated['lng'],
            'description' => $validated['description'] ?? '',
        ];

        $data[] = $newDepot;
        $this->writeData($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Titik berhasil ditambahkan',
            'data' => $newDepot
        ], 201);
    }

    /**
     * PUT /api/depots/{id}
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|in:depot,lokasi',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'description' => 'nullable|string|max:255',
        ]);

        $data = $this->readData();
        $foundIndex = -1;

        foreach ($data as $idx => $item) {
            if ($item['id'] === $id) {
                $foundIndex = $idx;
                break;
            }
        }

        if ($foundIndex === -1) {
            return response()->json([
                'status' => 'error',
                'message' => 'Titik dengan ID tersebut tidak ditemukan'
            ], 404);
        }

        $data[$foundIndex]['name'] = $validated['name'];
        $data[$foundIndex]['type'] = $validated['type'];
        $data[$foundIndex]['lat'] = (float) $validated['lat'];
        $data[$foundIndex]['lng'] = (float) $validated['lng'];
        $data[$foundIndex]['description'] = $validated['description'] ?? '';

        $this->writeData($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Titik berhasil diperbarui',
            'data' => $data[$foundIndex]
        ]);
    }

    /**
     * DELETE /api/depots/{id}
     */
    public function destroy(string $id)
    {
        $data = $this->readData();
        $initialCount = count($data);

        $filtered = array_filter($data, fn($item) => $item['id'] !== $id);

        if (count($filtered) === $initialCount) {
            return response()->json([
                'status' => 'error',
                'message' => 'Titik dengan ID tersebut tidak ditemukan'
            ], 404);
        }

        $this->writeData($filtered);

        return response()->json([
            'status' => 'success',
            'message' => 'Titik berhasil dihapus'
        ]);
    }

    /**
     * POST /api/depots/reset-sample
     */
    public function resetSample()
    {
        $sample = $this->defaultSampleData();
        $this->writeData($sample);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil direset ke contoh Modul GIS (Indramayu)',
            'data' => $sample
        ]);
    }
}

