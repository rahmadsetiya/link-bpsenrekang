<?php

namespace Database\Seeders;

use App\Models\Link;
use App\Models\Tag;
use Illuminate\Database\Seeder;

class LinkSeeder extends Seeder
{
  public function run(): void
  {
    \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=0;');
    Link::truncate();
    \Illuminate\Support\Facades\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

    $tagNames = ['Kependudukan', 'Sensus', 'Publikasi', 'Statistik', 'IPM', 'Sosial', 'Ekonomi', 'PDRB', 'Kemiskinan', 'Ketenagakerjaan', 'Survei', 'Podes', 'Kecamatan'];

    $tags = [];
    foreach ($tagNames as $name) {
      $tags[$name] = Tag::firstOrCreate(['name' => $name]);
    }

    $links = [
      [
        'name' => 'Hasil Sensus Penduduk 2020',
        'url' => 'https://enrekangkab.bps.go.id/id/publication/2021/01/29/sensus-penduduk-2020.html',
        'year' => 2020,
        'tags' => ['Kependudukan', 'Sensus'],
      ],
      [
        'name' => 'Kabupaten Enrekang Dalam Angka 2023',
        'url' => 'https://enrekangkab.bps.go.id/id/publication/2023/02/28/enrekang-dalam-angka-2023.html',
        'year' => 2023,
        'tags' => ['Publikasi', 'Statistik'],
      ],
      [
        'name' => 'Statistik Daerah Kabupaten Enrekang 2024',
        'url' => 'https://enrekangkab.bps.go.id/id/publication/2024/09/26/statistik-daerah-enrekang-2024.html',
        'year' => 2024,
        'tags' => ['Publikasi', 'Statistik'],
      ],
      [
        'name' => 'Indeks Pembangunan Manusia 2023',
        'url' => 'https://enrekangkab.bps.go.id/id/publication/2023/12/01/ipm-enrekang-2023.html',
        'year' => 2023,
        'tags' => ['IPM', 'Sosial'],
      ],
      [
        'name' => 'Produk Domestik Regional Bruto 2024',
        'url' => 'https://enrekangkab.bps.go.id/id/publication/2024/10/31/pdrb-enrekang-2024.html',
        'year' => 2024,
        'tags' => ['Ekonomi', 'PDRB'],
      ],
      [
        'name' => 'Kemiskinan dan Ketimpangan 2024',
        'url' => 'https://enrekangkab.bps.go.id/id/publication/2024/07/01/kemiskinan-enrekang-2024.html',
        'year' => 2024,
        'tags' => ['Kemiskinan', 'Sosial'],
      ],
      [
        'name' => 'Survei Angkatan Kerja Nasional 2023',
        'url' => 'https://enrekangkab.bps.go.id/id/publication/2023/11/30/sakernas-enrekang-2023.html',
        'year' => 2023,
        'tags' => ['Ketenagakerjaan', 'Survei'],
      ],
      [
        'name' => 'Potensi Desa Enrekang 2021',
        'url' => 'https://enrekangkab.bps.go.id/id/publication/2021/07/01/podes-enrekang-2021.html',
        'year' => 2021,
        'tags' => ['Podes', 'Kecamatan'],
      ],
    ];

    foreach ($links as $data) {
      $link = Link::create([
        'name' => $data['name'],
        'url'  => $data['url'],
        'year' => $data['year'],
      ]);

      $tagIds = array_map(fn($name) => $tags[$name]->id, $data['tags']);
      $link->tags()->sync($tagIds);
    }
  }
}
