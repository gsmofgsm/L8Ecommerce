<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Nicolaslopezj\Searchable\SearchableTrait;

class Product extends Model
{
    use HasFactory, SearchableTrait;
//    use Searchable;

    protected $fillable = ['quantity'];
    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'products.name' => 10,
            'products.details' => 5,
            'products.description' => 2,
        ],
    ];

    public function presentPrice()
    {
        return presentPrice($this->price);
    }

    public function scopeMightAlsoLike($query)
    {
        return $query->inRandomOrder()->take(4);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        // Customize array
        $extraFields = [
            'categories' => $this->categories->pluck('name')->toArray()
        ];
        // do not forget to add this in algolia configuration > attributes for faceting

        return array_merge($array, $extraFields);
    }
}
