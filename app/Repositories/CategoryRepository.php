<?php

namespace App\Repositories;

use App\Interfaces\CategoryRepositoryInterface;
use App\Models\Category;
use App\Models\City;

class CategoryRepository implements CategoryRepositoryInterface{
    public function getAllCategories(){
        return Category::all();
    }
}