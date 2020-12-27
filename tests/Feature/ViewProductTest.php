<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use TCG\Voyager\Facades\Voyager;
use Tests\TestCase;

class ViewProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_view_product_details()
    {
        $product = Product::factory()->create();

        $this->get('/shop/' . $product->slug)
            ->assertSuccessful()
            ->assertSee($product->name)
            ->assertSee($product->details)
            ->assertSee($product->description);
    }

    /** @test */
    public function it_shows_stock_level_high()
    {
        Voyager::shouldReceive('setting')->with('site.stock_threshold', null)->andReturn(5);
        Voyager::getFacadeRoot()->makePartial();
        $product = Product::factory()->create(['quantity' => 10]);

        $this->get('/shop/' . $product->slug)
            ->assertSuccessful()
            ->assertSee('In Stock');
    }

    /** @test */
    public function it_shows_stock_level_low()
    {
        Voyager::shouldReceive('setting')->with('site.stock_threshold', null)->andReturn(5);
        Voyager::getFacadeRoot()->makePartial();
        $product = Product::factory()->create(['quantity' => 5]);

        $this->get('/shop/' . $product->slug)
            ->assertSuccessful()
            ->assertSee('Low Stock');
    }

    /** @test */
    public function it_shows_stock_level_none()
    {
        Voyager::shouldReceive('setting')->with('site.stock_threshold', null)->andReturn(5);
        Voyager::getFacadeRoot()->makePartial();
        $product = Product::factory()->create(['name' => 'Test', 'quantity' => 0]);

        $this->get('/shop/' . $product->slug)
            ->assertSuccessful()
            ->assertSee('Not Available');
    }

    /** @test */
    public function it_shows_related_products()
    {
        $product1 = Product::factory()->create();
        $product2 = Product::factory()->create();

        $this->get('/shop/' . $product1->slug)
            ->assertSuccessful()
            ->assertSee($product2->name)
            ->assertViewHas('mightAlsoLike');
    }
}
