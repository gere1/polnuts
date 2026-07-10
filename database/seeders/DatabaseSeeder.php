<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\MenuItem;
use App\Models\Page;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'gere.erani@gmail.com'],
            ['name' => 'Admin', 'password' => Hash::make('password')]
        );

        $page = Page::firstOrCreate(
            ['slug' => 'home'],
            ['title' => 'მთავარი', 'is_home' => true]
        );

        if ($page->rows()->count() === 0) {
            $slider = $page->rows()->create([
                'type' => 'slider',
                'position' => 0,
                'title' => null,
                'settings' => [],
            ]);

            $slider->items()->createMany([
                ['position' => 0, 'title' => 'კეთილი იყოს თქვენი მობრძანება', 'subtitle' => 'ხარისხიანი პროდუქცია, საიმედო სერვისი'],
                ['position' => 1, 'title' => 'ჩვენი გამოცდილება თქვენს სამსახურში', 'subtitle' => 'წლების გამოცდილება ბაზარზე'],
                ['position' => 2, 'title' => 'დაგვიკავშირდით დღესვე', 'subtitle' => 'ჩვენი გუნდი მზადაა დაგეხმაროთ'],
            ]);

            $page->rows()->create([
                'type' => 'news',
                'position' => 1,
                'title' => 'სიახლეები',
                'subtitle' => 'უახლესი ამბები კომპანიიდან',
                'settings' => ['limit' => 3, 'article_ids' => []],
            ]);

            $page->rows()->create([
                'type' => 'text',
                'position' => 2,
                'title' => 'ჩვენ შესახებ',
                'body' => "ჩვენი კომპანია მუშაობს მაღალი ხარისხის სტანდარტებით და ორიენტირებულია მომხმარებელზე.\nდაგვიკავშირდით და გაეცანით ჩვენს პროდუქციას.",
                'settings' => ['text_align' => 'center'],
            ]);
        }

        if ($page->rows()->where('title', 'საკონტაქტო ინფორმაცია')->doesntExist()) {
            $columns = $page->rows()->create([
                'type' => 'text',
                'position' => 3,
                'title' => 'საკონტაქტო ინფორმაცია',
                'settings' => ['columns' => 3, 'text_align' => 'center'],
            ]);

            $columns->items()->createMany([
                ['position' => 0, 'title' => 'მისამართი', 'body' => "ქ. თბილისი\nსაქართველო"],
                ['position' => 1, 'title' => 'ტელეფონი', 'body' => '+995 555 00 00 00'],
                ['position' => 2, 'title' => 'ელ. ფოსტა', 'body' => 'info@example.com'],
            ]);
        }

        if (Product::count() === 0) {
            Product::create(['name' => 'თხილი ნაჭუჭით', 'slug' => 'txili-nachuchit', 'price' => 12.50, 'excerpt' => 'პრემიუმ ხარისხის თხილი, 16-21მმ კალიბრით.', 'position' => 0]);
            Product::create(['name' => 'გაცხადებული ნაღდი გული', 'slug' => 'txilis-guli', 'price' => 22.00, 'excerpt' => 'დაუმუშავებელი, ნატურალური თხილის გული.', 'position' => 1]);
            Product::create(['name' => 'შემწვარი თხილის გული', 'slug' => 'shemtsvari-txili', 'price' => 24.00, 'excerpt' => 'თერმულად დამუშავებული, შემწვარი თხილის გული.', 'position' => 2]);
            Product::create(['name' => 'ნუში', 'slug' => 'nushi', 'price' => 18.00, 'excerpt' => 'მაღალი ხარისხის ნუში.', 'position' => 3]);
        }

        if ($page->rows()->where('type', 'products')->doesntExist()) {
            $page->rows()->create([
                'type' => 'products',
                'position' => 1,
                'title' => 'ჩვენი პროდუქცია',
                'subtitle' => 'შერჩეული ხარისხის ნატურალური პროდუქტები',
                'settings' => ['limit' => 4, 'columns' => 3, 'layout' => 'carousel', 'product_ids' => []],
            ]);

            // Shift subsequent rows down to make room.
            $page->rows()->where('type', '!=', 'products')->where('position', '>=', 1)->increment('position');
        }

        if (MenuItem::count() === 0) {
            MenuItem::create(['label' => 'მთავარი', 'page_id' => $page->id, 'position' => 0]);
            MenuItem::create(['label' => 'პროდუქცია', 'url' => '/products', 'position' => 1]);
            MenuItem::create(['label' => 'სიახლეები', 'url' => '/news', 'position' => 2]);
        }

        if (Article::count() === 0) {
            Article::create([
                'title' => 'კომპანიამ დაიწყო ახალი პროექტი',
                'slug' => 'axali-proeqti',
                'excerpt' => 'მოკლე აღწერა ახალი პროექტის შესახებ.',
                'body' => 'სრული ტექსტი ახალი პროექტის შესახებ. აქ შეგიძლიათ ჩასვათ დეტალური ინფორმაცია.',
                'published_at' => now()->subDays(2),
            ]);

            Article::create([
                'title' => 'მონაწილეობა გამოფენაში',
                'slug' => 'gamofena',
                'excerpt' => 'ჩვენი კომპანია მონაწილეობდა საერთაშორისო გამოფენაში.',
                'body' => 'დეტალური ინფორმაცია გამოფენაში მონაწილეობის შესახებ.',
                'published_at' => now()->subDays(7),
            ]);

            Article::create([
                'title' => 'ახალი პარტნიორობა',
                'slug' => 'axali-partnioroba',
                'excerpt' => 'ვაცხადებთ ახალ პარტნიორობას.',
                'body' => 'დეტალური ინფორმაცია ახალი პარტნიორობის შესახებ.',
                'published_at' => now()->subDays(14),
            ]);
        }
    }
}
