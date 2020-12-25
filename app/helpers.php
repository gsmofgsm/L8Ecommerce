<?php

function presentPrice($price)
{
    return money_format('$%i', $price / 100);
}

function setActiveCategory($category, $output = 'active')
{
    return request()->category == $category ? $output : '';
}

function productImage($image)
{
    return $image && file_exists('storage/'.$image)
        ? asset('storage/'.$image)
        : asset('storage/img/not-found.png');
}
