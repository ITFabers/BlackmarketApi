<?php

namespace App\Repositories;

use App\Models\Brand;

class BrandsRepository extends FrontBaseRepository {
    public function __construct(Brand $brand) {
        parent::__construct($brand);
    }    

    public function getBrands($page = 1) {
        $brands = $this->paginate($page, 21);
        return $brands;
    }

    public function findBySlug($slug) {
        return $this->takeOneWhere(['slug' => $slug], ['products']);
    }

    public function getProducts($slug, $page, $perPage) {
        return $this->paginateRelation(['slug' => $slug], $page, $perPage, ['products']);
    }
    
}
