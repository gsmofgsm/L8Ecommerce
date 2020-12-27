<?php

namespace App\View\Components;

use Illuminate\View\Component;

class StockLevel extends Component
{
    private $quantity;
    public $stockLevel;
    public $badgeLevel;

    /**
     * Create a new component instance.
     *
     * @param $quantity
     */
    public function __construct($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        $this->getStockLevel();
        return view('components.stock-level');
    }

    protected function getStockLevel()
    {
        if ($this->quantity > setting('site.stock_threshold')) {
            $this->stockLevel = 'In Stock';
            $this->badgeLevel = 'success';
        } elseif ($this->quantity > 0) {
            $this->stockLevel = 'Low Stock';
            $this->badgeLevel = 'warning';
        } else {
            $this->stockLevel = 'Not Available';
            $this->badgeLevel = 'danger';
        }
    }
}
