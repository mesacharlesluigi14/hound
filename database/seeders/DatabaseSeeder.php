<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Clear existing data to avoid duplication
        DB::statement('DELETE FROM users');
        DB::statement('DELETE FROM categories');
        DB::statement('DELETE FROM products');
        DB::statement('DELETE FROM popups');

        // 2. Seed Users
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Admin',
                'lname' => 'User',
                'email' => 'admin@hound.com',
                'password' => Hash::make('password123'),
                'role_as' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'name' => 'John',
                'lname' => 'Doe',
                'email' => 'user@hound.com',
                'password' => Hash::make('password123'),
                'role_as' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'name' => 'Inventory',
                'lname' => 'Manager',
                'email' => 'inventory@hound.com',
                'password' => Hash::make('password123'),
                'role_as' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 4,
                'name' => 'Order',
                'lname' => 'Manager',
                'email' => 'order@hound.com',
                'password' => Hash::make('password123'),
                'role_as' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 5,
                'name' => 'Marketing',
                'lname' => 'Manager',
                'email' => 'marketing@hound.com',
                'password' => Hash::make('password123'),
                'role_as' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 6,
                'name' => 'Store',
                'lname' => 'Manager',
                'email' => 'store@hound.com',
                'password' => Hash::make('password123'),
                'role_as' => 5,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // 3. Scan Category Images
        $categoryDir = public_path('assets/uploads/category');
        $categoryImages = [];
        if (is_dir($categoryDir)) {
            $files = scandir($categoryDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
                    $categoryImages[] = $file;
                }
            }
        }

        // Define categories
        $categoriesData = [
            ['name' => 'Rings', 'slug' => 'rings', 'description' => 'Beautiful engagement, wedding, and fashion rings.'],
            ['name' => 'Necklaces', 'slug' => 'necklaces', 'description' => 'Elegant gold, silver, and gemstone necklaces.'],
            ['name' => 'Bracelets', 'slug' => 'bracelets', 'description' => 'Classic bangles, charm bracelets, and cuffs.'],
            ['name' => 'Earrings', 'slug' => 'earrings', 'description' => 'Exquisite studs, hoops, and dangle earrings.'],
            ['name' => 'Pendants', 'slug' => 'pendants', 'description' => 'Luxury diamond and ruby pendants.'],
            ['name' => 'Bestsellers', 'slug' => 'bestsellers', 'description' => 'Our highly rated customer favorites.']
        ];

        $categories = [];
        foreach ($categoriesData as $index => $cat) {
            // Assign image from scan or default
            $image = !empty($categoryImages[$index % count($categoryImages)]) 
                ? $categoryImages[$index % count($categoryImages)] 
                : 'placeholder.png';

            $catId = $index + 1;
            DB::table('categories')->insert([
                'id' => $catId,
                'name' => $cat['name'],
                'slug' => $cat['slug'],
                'description' => $cat['description'],
                'status' => 1, // active
                'popular' => 1, // popular (trending)
                'image' => $image,
                'meta_title' => $cat['name'] . ' - Hound Jewelry Shop',
                'meta_descrip' => $cat['description'],
                'meta_keywords' => $cat['slug'] . ', jewelry, luxury, gold, silver',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $categories[] = [
                'id' => $catId,
                'name' => $cat['name'],
                'slug' => $cat['slug']
            ];
        }

        // 4. Scan Product Images
        $productDir = public_path('assets/uploads/products');
        $productImages = [];
        if (is_dir($productDir)) {
            $files = scandir($productDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
                    $productImages[] = $file;
                }
            }
        }

        // Jewelry name sets to make titles realistic
        $namesByCategory = [
            'rings' => [
                'Golden Eternity Ring', 'Classic Diamond Solitaire', 'Halo Sapphire Ring', 
                'Emerald Cut Silver Ring', 'Rose Gold Promise Ring', 'Vintage Floral Band',
                'Princess Cut Ring', 'Amethyst Accent Ring', 'Double Band Wedding Ring'
            ],
            'necklaces' => [
                'Pearl Pendant Choker', 'Gold Link Chain', 'Diamond Drop Necklace', 
                'Vintage Locket Necklace', 'Silver Heart Pendant', 'Crystal Statement Collar',
                'Gold Herringbone Chain', 'Emerald Solitaire Necklace'
            ],
            'bracelets' => [
                'Diamond Tennis Bracelet', 'Gold Bangle Set', 'Silver Charm Chain', 
                'Leather Cuff Bracelet', 'Rose Gold Link Bracelet', 'Infinity Charm Bangle',
                'Delicate Pearl Bracelet', 'Cubic Zirconia Tennis Cuff'
            ],
            'earrings' => [
                'Diamond Stud Earrings', 'Gold Hoop Earrings', 'Pearl Drop Earrings', 
                'Silver Dangle Earrings', 'Emerald Drop Studs', 'Rose Gold Ear Crawlers',
                'Sapphire Chandelier Earrings', 'Classic Huggie Hoops'
            ],
            'pendants' => [
                'Heart Diamond Pendant', 'Cross Gold Pendant', 'Vintage Ruby Pendant', 
                'Moon & Star Silver Pendant', 'Zodiac Gold Medallion', 'Opal Halo Pendant'
            ],
            'bestsellers' => [
                'Hound Premium Ring', 'Signature Gold Necklace', 'Hound Classic Bangle',
                'Sparkling Halo Studs', 'Luminous Pearl Drop Pendant'
            ]
        ];

        // Seed products dynamically based on scans
        if (count($productImages) > 0) {
            foreach ($productImages as $index => $image) {
                // Determine category (round-robin)
                $category = $categories[$index % count($categories)];
                $catSlug = $category['slug'];
                
                // Select name
                $namePool = $namesByCategory[$catSlug] ?? ['Hound Fine Jewelry'];
                $baseName = $namePool[$index % count($namePool)];
                // Append index to keep name unique
                $productName = $baseName . ' ' . (intval($index / count($namePool)) + 1);
                $productSlug = Str::slug($productName);

                $originalPrice = rand(2500, 15000);
                $sellingPrice = intval($originalPrice * (rand(75, 95) / 100)); // 5% to 25% discount

                DB::table('products')->insert([
                    'cate_id' => $category['id'],
                    'name' => $productName,
                    'slug' => $productSlug,
                    'small_description' => 'Exquisite ' . strtolower($category['name']) . ' handcrafted with exceptional precision and luxury finishes.',
                    'description' => 'Discover the beauty of our ' . $productName . '. Each item in our collection is carefully curated and made from the finest materials to highlight elegance, beauty, and timeless luxury. Highly durable and perfect for formal events or adding a touch of class to everyday outfits.',
                    'original_price' => (string) $originalPrice,
                    'selling_price' => (string) $sellingPrice,
                    'image' => $image,
                    'qty' => (string) rand(10, 45),
                    'tax' => '12', // 12% standard VAT
                    'status' => 1, // active
                    'trending' => ($index % 4 === 0) ? 1 : 0, // 25% trending
                    'meta_title' => $productName . ' | Hound Jewelry Shop',
                    'meta_keywords' => strtolower($category['name']) . ', jewelry, luxury, gift, ' . $productSlug,
                    'meta_description' => 'Buy the beautiful ' . $productName . ' online at Hound Jewelry Shop. Free shipping and certificate of authenticity included.',
                    'view_count' => rand(15, 850),
                    'created_at' => now()->subDays(rand(1, 45)), // some will be new (last 30 days)
                    'updated_at' => now(),
                ]);
            }
        }

        // 5. Seed Popups
        $popupDir = public_path('assets/uploads/popups');
        $popupImages = [];
        if (is_dir($popupDir)) {
            $files = scandir($popupDir);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && preg_match('/\.(jpg|jpeg|png|gif|webp)$/i', $file)) {
                    $popupImages[] = $file;
                }
            }
        }

        if (count($popupImages) > 0) {
            DB::table('popups')->insert([
                [
                    'title' => 'Welcome to Hound Jewelry Shop!',
                    'content' => 'Sign up today to explore our exclusive gold and diamond catalog, and receive 10% off your first purchase!',
                    'image' => $popupImages[0],
                    'expiry_date' => now()->addDays(30)->toDateString(),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
            if (isset($popupImages[1])) {
                DB::table('popups')->insert([
                    [
                        'title' => 'Grand Reopening Special Offer',
                        'content' => 'Use code REOPEN30 at checkout to save 30% on our signature luxury rings and necklace collections.',
                        'image' => $popupImages[1],
                        'expiry_date' => now()->addDays(15)->toDateString(),
                        'is_active' => true,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ]);
            }
        }
    }
}
