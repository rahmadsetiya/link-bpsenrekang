<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
    public function run(): void
    {
        $tagNames = ['Kependudukan', 'Sensus', 'Publikasi', 'Statistik', 'IPM', 'Sosial', 'Ekonomi', 'PDRB', 'Kemiskinan', 'Ketenagakerjaan', 'Survei', 'Podes', 'Kecamatan'];

        $tags = [];
        foreach ($tagNames as $name) {
            $tag = Tag::where('name', $name)->first();
            if (!$tag) {
                $tag = new Tag(['name' => $name]);
                $tag->save();
            }
            $tags[$name] = $tag;
        }

        $links = [
            [
                'name' => 'Hasil Sensus Penduduk 2020',
                'url' => 'https://enrekangkab.bps.go.id',
                'year' => 2020,
                'tags' => ['Kependudukan', 'Sensus'],
            ],
            [
                'name' => 'Kabupaten Enrekang Dalam Angka 2023',
                'url' => 'https://enrekangkab.bps.go.id',
                'year' => 2023,
                'tags' => ['Publikasi', 'Statistik'],
            ],
            [
                'name' => 'Statistik Daerah Kabupaten Enrekang 2024',
                'url' => 'https://enrekangkab.bps.go.id',
                'year' => 2024,
                'tags' => ['Publikasi', 'Statistik'],
            ],
            [
                'name' => 'Indeks Pembangunan Manusia 2023',
                'url' => 'https://enrekangkab.bps.go.id',
                'year' => 2023,
                'tags' => ['IPM', 'Sosial'],
            ],
            [
                'name' => 'Produk Domestik Regional Bruto 2024',
                'url' => 'https://enrekangkab.bps.go.id',
                'year' => 2024,
                'tags' => ['Ekonomi', 'PDRB'],
            ],
            [
                'name' => 'Kemiskinan dan Ketimpangan 2024',
                'url' => 'https://enrekangkab.bps.go.id',
                'year' => 2024,
                'tags' => ['Kemiskinan', 'Sosial'],
            ],
            [
                'name' => 'Survei Angkatan Kerja Nasional 2023',
                'url' => 'https://enrekangkab.bps.go.id',
                'year' => 2023,
                'tags' => ['Ketenagakerjaan', 'Survei'],
            ],
            [
                'name' => 'Potensi Desa Enrekang 2021',
                'url' => 'https://enrekangkab.bps.go.id',
                'year' => 2021,
                'tags' => ['Podes', 'Kecamatan'],
            ],
        ];

        foreach ($links as $data) {
            $link = Link::create([
                'name' => $data['name'],
                'url' => $data['url'],
                'year' => $data['year'],
            ]);

            $tagIds = array_map(fn($name) => $tags[$name]->id, $data['tags']);
            $link->tags()->sync($tagIds);
        }
    }
}
