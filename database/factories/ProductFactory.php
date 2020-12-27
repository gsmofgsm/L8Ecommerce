<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->getUniqueName();
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'details' => $this->faker->sentence,
            'price' => random_int(109999, 249999),
            'description' => $this->faker->paragraph,
            'image' => 'products/December2020/product.jpg',
            'featured' => true,
            'quantity' => 10,
        ];
    }

    /**
     * @return string
     * @throws \Exception
     */
    protected function getUniqueName(): string
    {
        $name = $this->faker->randomElement(['Laptop', 'Iphone', 'MacBook', 'Ipad', 'Desktop', 'Appliance']) . ' ' . random_int(1, 9);
        if (! Product::where('name', $name)->exists()) {
            return $name;
        }
        return $this->getUniqueName();
    }
}
