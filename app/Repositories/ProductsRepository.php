<?php
// app/Repositories/CategoryRepository.php

namespace App\Repositories;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use App\Models\PopularCategory;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
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

    // public function getProducts($page = 1, $perPage = 15) {
    //     return $this->model::where(['status' => 1])->paginate($perPage, ['*'], 'page', $page);
    // }

    public function getProducts(Request $request)//: Builder
    {
        $query = Product::with(['categories', 'attributes.variant', 'attributes.variantItem'])
                        ->where(['status' => 1, 'approve_by_admin' => 1]);

        if (!empty($request->category)) {
            $category = Category::where('slug', $request->category)->first();
                $query->whereHas('categories', function ($q) use ($category) {
                    $q->whereRaw("FIND_IN_SET(?, categories_ids)", [$category->id]);
                });
        }
        if(!empty($request->categories) && is_array($request->categories)) {
            $categories = $request->categories;
            
            $query->whereHas('categories', function ($q) use ($categories) {
                foreach($categories as $key => $value) {
                    if($key == 0) {
                        $q->whereRaw("FIND_IN_SET(?, categories_ids)", [$value]);
                    } else {
                        $q->orWhereRaw("FIND_IN_SET(?, categories_ids)", [$value]);
                    }
                }
            });
        }

        $this->applyHighlightFilters($query, $request);

        if (!empty($request->brand)) {
            $brand = Brand::where('slug', $request->brand)->firstOrFail();
            $query->where('brand_id', $brand->id);                
        }
        if(!empty($request->brands) && is_array($request->brands)) {
            $brands = $request->brands;
            foreach($brands as $key => $brand) {
                if($key == 0)
                    $query->where('brand_id', $brand);
                else
                    $query->orWhere('brand_id', $brand);
            }
        }

        if (!empty($request->search)) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        return $query;
    }

    protected function applyHighlightFilters(Builder $query, Request $request): void
    {
        if ($request->has('highlight')) {
            switch ($request->highlight) {
                case 'popular_category':
                    $categoryIds = PopularCategory::pluck('category_id');
                    $query->whereHas('categories', function ($q) use ($categoryIds) {
                        $q->whereIn('categories.id', $categoryIds);
                    });
                    break;
                case 'top_product':
                    $query->where('is_top', 1);
                    break;
                case 'new_arrival':
                    $query->where('new_product', 1);
                    break;
                case 'featured_product':
                    $query->where('is_featured', 1);
                    break;
                case 'best_product':
                    $query->where('is_best', 1);
                    break;
            }
        }
    }

    public function filterProducts($filters, $page = 1, $perPage = 15)
    {
        $query = $this->model::query();
        $brandIds = $filters->brands??[];
        $categoryIds = $filters->categories??[];
        $minPrice = $filters->min_price;
        $maxPrice = $filters->max_price;
        switch($filters->type) {
            case 'new':
                $type = 'new_product';
                break;
            case 'top':
                $type = 'is_top';
                break;
            case 'best':
                $type = 'is_best';
                break;
        }
        // foreach ($filters as $column => $value) {
        //     if ($value !== null) {
        //         // Apply filters only if the value is not null
        //         if($column == 'min_price')
        //             $query->where('price', '>=', $value);
        //         elseif($column == 'max_price')
        //             $query->where('price', '<=', $value);
        //         else
        //             $query->where($column, $value);
        //     }
        // }
        if (!empty($brandIds)) {
            $query->whereIn('brand_id', $brandIds);
        }

        if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
        }

        if (!is_null($minPrice)) {
            $query->where('price', '>=', $minPrice);
        }
        if (isset($type)) {
            $query->where($type, 1);
        }
        if (!is_null($maxPrice)) {
            $query->where('price', '<=', $maxPrice);
        }
        if (empty($brandIds) && empty($categoryIds) && is_null($minPrice) && is_null($maxPrice)) {
          $results = $this->model->paginate($perPage, ['*'], 'page', $page);
        }
        $results = $query->paginate($perPage, ['*'], 'page', $page);

        return $results;
    }
    public function searchProducts($request, $page = 1, $perPage = 15)
    {
          $searchTerm = $request->search;
          $q = $this->model::query();

          $q->with(['attributes.variant', 'attributes.variantItem'])
          ->where(function ($query) use ($searchTerm) {
          $query->where('name', 'like', "%{$searchTerm}%")
              ->orWhereHas('attributes', function ($query) use ($searchTerm) {
                  $query->where('text', 'like', "%{$searchTerm}%")
                      ->orWhereHas('variant', function ($query) use ($searchTerm) {
                          $query->where('name', 'like', "%{$searchTerm}%");
                      })
                      ->orWhereHas('variantItem', function ($query) use ($searchTerm) {
                          $query->where('name', 'like', "%{$searchTerm}%");
                      });
              });
            })
            ->get();
            $results = $q->paginate($perPage, ['*'], 'page', $page);

            return $results;

    }
}
