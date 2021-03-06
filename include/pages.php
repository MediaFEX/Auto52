<?php
/**
 * Created by PhpStorm.
 * User: andrus.jakobson
 * Date: 9.03.2017
 * Time: 13:20
 */

$pages = [
    //User page
    'user' => [
        'name' => 'Kasutajad',
        'url' => 'page/user',
        'path' => 'user/user.php'
    ],
    'users' => [
        'name' => 'Kasutaja',
        'url' => 'page/users',
        'path' => 'user/users.php'
    ],
    'delete-user' => [
        'name' => 'Kustuta Kasutaja',
        'url' => 'page/delete-user',
        'path' => 'user/delete-user.php'
    ],
    //ADMIN START PAGES
    'categories' => [
        'name' => 'Kategooriad',
        'url' => 'page/categories',
        'path' => 'category/categories.php'
    ],
    'category' => [
        'name' => 'Loo kategooria',
        'url' => 'page/category',
        'path' => 'category/category.php'
    ],
    'delete' => [
        'name' => 'Kustuta kategooria',
        'url' => 'page/delete',
        'path' => 'category/delete.php'
    ],
    //products pages
    'delete-product' => [
        'name' => 'Kustuta toode',
        'url' => 'page/delete-product',
        'path' => 'product/delete.php'
    ],
    'products' => [
        'name' => 'Tooted',
        'url' => 'page/products',
        'path' => 'product/products.php'
    ],
    'product' => [
        'name' => 'Toode',
        'url' => 'page/product',
        'path' => 'product/product.php'
    ],
    //galerii
    'pictures' => [
        'name' => 'Galerii',
        'url' => 'page/pictures',
        'path' => 'pictures/pictures.php'
    ],
    //MAIN PAGES
    'home' => [
        'name' => 'Avaleht',
        'url' => 'page/home',
        'path' => 'home/home.php'
    ],

    'product-view' => [
        'name' => 'Toode',
        'url' => 'page/product-view',
        'path' => 'home/product.php'
    ],
];