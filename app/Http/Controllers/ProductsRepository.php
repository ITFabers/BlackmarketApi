<?php
// app/Repositories/CategoryRepository.php

namespace App\Repositories;

use App\Models\Product;
use App\Http\Resources\ProductsResource;

class ProductsRepository extends FrontBaseRepository {
    public function __construct(Product $product) {
        parent::__construct($product);
    }

    public function getTopProducts($limit,$page = 1, $perPage = 15) {
        return $this->model::where(['is_top' => 1, 'status' => 1], [], $limit)->paginate($perPage, ['*'], 'page', $page);
    }

    public function getBestProducts($limit,$page = 1, $perPage = 15) {
        return $this->model::where(['is_best' => 1, 'status' => 1], [], $limit)->paginate($perPage, ['*'], 'page', $page);
    }

    public function getNewArrivalProducts($limit,$page = 1, $perPage = 15) {
        return $this->model::where(['new_product' => 1, 'status' => 1], [], $limit)->paginate($perPage, ['*'], 'page', $page);
    }

    public function getFeaturedProducts($limit,$page = 1, $perPage = 15) {
        return $this->model::where(['is_featured' => 1, 'status' => 1], [], $limit)->paginate($perPage, ['*'], 'page', $page);
    }

    public function getHomepageProducts($page = 1, $perPage = 15) {
        return $this->model::where(['show_homepage' => 1, 'status' => 1])->paginate($perPage, ['*'], 'page', $page);
    }

    public function getProducts($page = 1, $perPage = 15) {
        return $this->model::where(['status' => 1])->paginate($perPage, ['*'], 'page', $page);
    }

    public function filterProducts($filters, $page = 1, $perPage = 15)
    {
        $query = $this->model::query();

        foreach ($filters as $column => $value) {
            if ($value !== null) {
                // Apply filters only if the value is not null
                $query->where($column, $value);
            }
        }

        $offset = ($page - 1) * $perPage;

        $results = $query->skip($offset)
            ->take($perPage)
            ->get();

        return $results;
    }
}
