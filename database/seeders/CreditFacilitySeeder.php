<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CreditFacilitySeeder extends Seeder
{
    public function run(): void
    {
        // Data master fasilitas kredit
        $facilities = [
            [
                'kode' => 'KMG',
                'nama' => 'Kredit Multiguna',
                'deskripsi' => 'Fasilitas kredit konsumtif dengan agunan.',
                'max_jangka_waktu' => 60,
                'tiers' => [
                    ['min' => 0, 'max' => 50000000, 'bunga' => 2.50],
                    ['min' => 50000000.01, 'max' => 150000000, 'bunga' => 2.00],
                    ['min' => 150000000.01, 'max' => 500000000, 'bunga' => 1.70],
                    ['min' => 500000000.01, 'max' => 1000000000, 'bunga' => 1.50],
                ],
            ],
            [
                'kode' => 'KMK',
                'nama' => 'Kredit Modal Kerja',
                'deskripsi' => 'Fasilitas kredit untuk kebutuhan modal usaha.',
                'max_jangka_waktu' => 36,
                'tiers' => [
                    ['min' => 0, 'max' => 50000000, 'bunga' => 2.00],
                    ['min' => 50000000.01, 'max' => 150000000, 'bunga' => 1.70],
                    ['min' => 150000000.01, 'max' => 500000000, 'bunga' => 1.50],
                    ['min' => 500000000.01, 'max' => 1000000000, 'bunga' => 1.30],
                ],
            ],
            [
                'kode' => 'KI',
                'nama' => 'Kredit Investasi',
                'deskripsi' => 'Fasilitas kredit jangka menengah/panjang untuk pembiayaan investasi.',
                'max_jangka_waktu' => 60,
                'tiers' => [
                    ['min' => 0, 'max' => 50000000, 'bunga' => 1.70],
                    ['min' => 50000000.01, 'max' => 150000000, 'bunga' => 1.50],
                    ['min' => 150000000.01, 'max' => 500000000, 'bunga' => 1.30],
                    ['min' => 500000000.01, 'max' => 1000000000, 'bunga' => 1.10],
                ],
            ],
        ];

        foreach ($facilities as $facility) {
            // Insert facility utama
            $facilityId = DB::table('credit_facilities')->insertGetId([
                'kode' => $facility['kode'],
                'nama' => $facility['nama'],
                'deskripsi' => $facility['deskripsi'],
                'max_jangka_waktu' => $facility['max_jangka_waktu'],
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Insert tier untuk masing-masing facility
            foreach ($facility['tiers'] as $tier) {
                DB::table('credit_facility_tiers')->insert([
                    'credit_facility_id' => $facilityId,
                    'min_plafond' => $tier['min'],
                    'max_plafond' => $tier['max'],
                    'bunga' => $tier['bunga'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
