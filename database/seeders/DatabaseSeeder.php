<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Table;
use App\Models\MenuCategory;
use App\Models\Menu;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Hapus data lama agar tidak duplikat saat seed ulang
        Table::query()->delete();
        Menu::query()->delete();
        MenuCategory::query()->delete();

        // ==============================
        // BUAT DATA MEJA
        // ==============================
        $tables = [
            ['number' => '1', 'capacity' => 2],
            ['number' => '2', 'capacity' => 2],
            ['number' => '3', 'capacity' => 4],
            ['number' => '4', 'capacity' => 4],
            ['number' => '5', 'capacity' => 6],
            ['number' => '6', 'capacity' => 6],
            ['number' => '7', 'capacity' => 8],
            ['number' => '8', 'capacity' => 8],
            ['number' => 'VIP-1', 'capacity' => 10],
            ['number' => 'VIP-2', 'capacity' => 12],
        ];

        foreach ($tables as $table) {
            Table::create($table);
        }

        // ==============================
        // BUAT DATA KATEGORI MENU
        // ==============================
        $categories = [
            ['name' => 'Makanan Berat', 'icon' => '🍚', 'sort_order' => 1],
            ['name' => 'Makanan Ringan', 'icon' => '🍟', 'sort_order' => 2],
            ['name' => 'Minuman Dingin', 'icon' => '🥤', 'sort_order' => 3],
            ['name' => 'Minuman Hangat', 'icon' => '☕', 'sort_order' => 4],
            ['name' => 'Dessert', 'icon' => '🍰', 'sort_order' => 5],
        ];

        foreach ($categories as $cat) {
            MenuCategory::create($cat);
        }

        // ==============================
        // BUAT DATA MENU
        // ==============================
        $menus = [
            // Makanan Berat (category_id = 1)
            ['category_id' => 1, 'name' => 'Nasi Goreng Spesial', 'description' => 'Nasi goreng dengan telur, ayam, dan kerupuk', 'price' => 28000],
            ['category_id' => 1, 'name' => 'Nasi Gudeg Jogja', 'description' => 'Gudeg ayam khas Jogja dengan sambal krecek', 'price' => 32000],
            ['category_id' => 1, 'name' => 'Ayam Bakar Taliwang', 'description' => 'Ayam bakar bumbu Taliwang pedas khas Lombok', 'price' => 35000],
            ['category_id' => 1, 'name' => 'Rendang Sapi', 'description' => 'Rendang sapi empuk masakan Padang', 'price' => 38000],
            ['category_id' => 1, 'name' => 'Soto Betawi', 'description' => 'Soto betawi dengan kuah santan kental', 'price' => 25000],
            ['category_id' => 1, 'name' => 'Mie Goreng Jawa', 'description' => 'Mie goreng bumbu Jawa dengan sayuran', 'price' => 22000],
            ['category_id' => 1, 'name' => 'Ikan Bakar Rica-Rica', 'description' => 'Ikan mujair bakar sambal rica-rica', 'price' => 33000],

            // Makanan Ringan (category_id = 2)
            ['category_id' => 2, 'name' => 'Tahu Crispy', 'description' => 'Tahu goreng renyah dengan sambal kecap', 'price' => 12000],
            ['category_id' => 2, 'name' => 'Pisang Goreng Keju', 'description' => 'Pisang goreng tabur keju dan coklat', 'price' => 15000],
            ['category_id' => 2, 'name' => 'Lumpia Semarang', 'description' => 'Lumpia goreng isi rebung dan udang', 'price' => 18000],
            ['category_id' => 2, 'name' => 'Kentang Goreng', 'description' => 'French fries dengan saus sambal mayo', 'price' => 16000],
            ['category_id' => 2, 'name' => 'Cireng Isi', 'description' => 'Cireng goreng isi ayam suwir dan keju', 'price' => 14000],

            // Minuman Dingin (category_id = 3)
            ['category_id' => 3, 'name' => 'Es Teh Manis', 'description' => 'Teh manis dingin segar', 'price' => 8000],
            ['category_id' => 3, 'name' => 'Es Jeruk Segar', 'description' => 'Jus jeruk peras segar dengan es', 'price' => 12000],
            ['category_id' => 3, 'name' => 'Jus Alpukat', 'description' => 'Jus alpukat dengan susu coklat', 'price' => 18000],
            ['category_id' => 3, 'name' => 'Es Campur', 'description' => 'Es campur dengan kelapa, cincau, dan sirup', 'price' => 15000],
            ['category_id' => 3, 'name' => 'Smoothie Mangga', 'description' => 'Smoothie mangga segar blended', 'price' => 22000],

            // Minuman Hangat (category_id = 4)
            ['category_id' => 4, 'name' => 'Kopi Hitam', 'description' => 'Kopi hitam tubruk khas Nusantara', 'price' => 10000],
            ['category_id' => 4, 'name' => 'Teh Tarik', 'description' => 'Teh tarik creamy khas Mamak', 'price' => 15000],
            ['category_id' => 4, 'name' => 'Hot Chocolate', 'description' => 'Coklat panas dengan marshmallow', 'price' => 20000],
            ['category_id' => 4, 'name' => 'Wedang Jahe', 'description' => 'Wedang jahe hangat dengan gula merah', 'price' => 12000],

            // Dessert (category_id = 5)
            ['category_id' => 5, 'name' => 'Es Krim Vanilla', 'description' => 'Es krim vanilla dengan toping', 'price' => 15000],
            ['category_id' => 5, 'name' => 'Puding Coklat', 'description' => 'Puding coklat lembut dengan vla', 'price' => 12000],
            ['category_id' => 5, 'name' => 'Klepon Cake', 'description' => 'Kue klepon modern isi gula merah', 'price' => 18000],
            ['category_id' => 5, 'name' => 'Pisang Ijo', 'description' => 'Pisang ijo khas Makassar', 'price' => 14000],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }

        $this->command->info('Seeding selesai! 10 meja, 5 kategori, dan 27 menu berhasil dibuat.');
    }
}