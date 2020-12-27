<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ViewShopPageTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function shop_page_loads_correctly()
    {
        // Arrange

        // Act
        $response = $this->get('/shop');

        // Assert
        $response->assertStatus(200);
        $response->assertSee('Laravel Ecommerce');
    }

    /** @test */
    public function featured_product_is_visible()
    {
        // Arrange
        $featuredProduct = Product::factory()->create(['featured' => true]);

        // Act
        $response = $this->get('/shop');

        // Assert
        $response->assertStatus(200);
        $response->assertSee($featuredProduct->name);
    }

    /** @test */
    public function not_featured_product_is_not_visible()
    {
        // Arrange
        $product = Product::factory()->create(['featured' => false]);

        // Act
        $response = $this->get('/shop');

        // Assert
        $response->assertStatus(200);
        $response->assertDontSee($product->name);
    }

    /** @test */
    public function pagination_for_products_works()
    {
        // Arrange
        // first page products
        for ($i = 11; $i < 17; $i++) {
            Product::factory()->create(['name' => 'Product '.$i, 'slug' => 'product-'.$i, 'featured' => true]);
        }
        // second page products
        for ($i = 21; $i < 27; $i++) {
            Product::factory()->create(['name' => 'Product '.$i, 'slug' => 'product-'.$i, 'featured' => true]);
        }

        // Act
        $response = $this->get('/shop');

        // Assert
        $response->assertSee('Product 11');

        $response = $this->get('/shop?page=2');
        $response->assertSee('Product 21');
    }

    /** @test */
    public function sort_price_low_to_high()
    {
        Product::factory()->create(['price' => 1500, 'featured' => true]);
        Product::factory()->create(['price' => 1000, 'featured' => true]);
        Product::factory()->create(['price' => 2000, 'featured' => true]);

        $this->get('/shop?sort=low_high')->assertSeeInOrder([
            '10', '15', '20'
        ]);
    }

    /** @test */
    public function sort_price_high_to_low()
    {
        Product::factory()->create(['name' => 'Price Middle', 'price' => 1500, 'featured' => true]);
        Product::factory()->create(['name' => 'Price Low', 'price' => 1000, 'featured' => true]);
        Product::factory()->create(['name' => 'Price High', 'price' => 2000, 'featured' => true]);

        $this->get('/shop?sort=high_low')->assertSeeInOrder([
            'Price High', 'Price Middle', 'Price Low'
        ]);
    }

    /** @test */
    public function category_page_shows_correct_products()
    {
        $category1 = Category::factory()->create(['slug' => 'category1']);
        $category2 = Category::factory()->create(['slug' => 'category2']);
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();
        $product1->categories()->attach($category1);
        $product2->categories()->attach($category2);

        $this->get('/shop?category=category1')
            ->assertSee($product1->name)
            ->assertDontSee($product2->name);

        $this->get('/shop?category=category2')
            ->assertSee($product2->name)
            ->assertDontSee($product1->name);
    }
}
