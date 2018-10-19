<?php
use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        User::truncate();
        Product::truncate();
        Transaction::truncate();
        Category::truncate();
        DB::table('category_product')->truncate();

        $userQuantity=100;
        $categoriesQuantity=20;
        $productsQuantity=100;
        $transactionsQuantity=100;

        factory(User::class,$userQuantity)->create();
        factory(Category::class,$categoriesQuantity)->create();
        factory(Product::class,$productsQuantity)->create()->each(
            function($product){
                $categories=Category::all()->random(mt_rand(1,5))->pluck('id');
                $product->categories()->attach($categories);
            }
        );
        
        factory(Transaction::class,$transactionsQuantity)->create();
        
        
    }
}
