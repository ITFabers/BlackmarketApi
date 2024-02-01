<?php
// app/Repositories/CategoryRepository.php

namespace App\Repositories;

use App\Models\Category;

class CategoryRepository extends FrontBaseRepository {
    public function __construct(Category $category) {
        parent::__construct($category);
    }

    public function getTopCategories() {
        return $this->takeWhere(['parent_id' => 0, 'status' => 1]);
    }

    public function findBySlug($slug) {
        return $this->takeOneWhere(['slug' => $slug], ['takeProducts']);
    }

    public function getChildCategories($id) {
        return $this->takeWhere(['parent_id' => $id]);
    }

    public function getProductsByCategoryId($categoryId, $currentDepth = 1, $maxDepth = PHP_INT_MAX)
    {
        $category = Category::find($categoryId);

        if (!$category) {
            return [];
        }

        $products = $category->takeProducts()->take(20)->get();
        
        // return $products;
        // if(c)
        if ($currentDepth < $maxDepth) {
            $subcategories = $category->getChilds();
            foreach ($subcategories as $subcategory) {
                $products = $products->merge(
                    $this->getProductsByCategoryId($subcategory->id, $currentDepth + 1, $maxDepth)
                );
            }
        }

        return $products->take(20);
    }
}
