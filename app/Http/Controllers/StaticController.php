<?php

// app/Http/Controllers/StaticController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CategoryRepository;
use App\Repositories\SliderRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\BrandsRepository;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SliderResource;
use App\Http\Resources\ProductsResource;
use App\Http\Resources\BrandsResource;
use App\Models\Product;

class StaticController extends Controller {
    protected $categoryRepository;
    protected $sliderRepository;
    protected $productsRepository;
    protected $brandsRepository;

    public function __construct(CategoryRepository $categoryRepository, SliderRepository $sliderRepository, ProductsRepository $productsRepository, BrandsRepository $brandsRepository) {
        $this->categoryRepository = $categoryRepository;
        $this->sliderRepository = $sliderRepository;
        $this->productsRepository = $productsRepository;
        $this->brandsRepository = $brandsRepository;
    }

    public function index() {
        $categories = CategoryResource::collection($this->categoryRepository->getTopCategories());
        $slides = SliderResource::collection($this->sliderRepository->getSlides());
        $homepageProducts = ProductsResource::collection($this->productsRepository->getHomepageProducts());
        $newArrivalProducts = ProductsResource::collection($this->productsRepository->getNewArrivalProducts(6));
        $topProducts = ProductsResource::collection($this->productsRepository->getTopProducts(6));
        $bestProducts = ProductsResource::collection($this->productsRepository->getBestProducts(6));
        $featuredProducts = ProductsResource::collection($this->productsRepository->getFeaturedProducts(6));
        return response()->json([
            'categories' => $categories,
            'slides' => $slides,
            'homepage_products' => $homepageProducts,
            'topProducts' => $topProducts,
            'newArrivalProducts' => $newArrivalProducts,
            'bestProducts' => $bestProducts,
            'hotOfferProducts' => $featuredProducts
        ]);
    }

    // public function categories($page = 1) {
    //     $categories = CategoryResource::collection($this->categoryRepository->getTopCategories());
    //     $products = $this->productsRepository->getProducts($page);
    //     return response()->json([
    //         'categories' => $categories,
    //         'products' => $products
    //     ]);
    // }

    public function categories($page = 1) {
        $perPage = 15;

        $categories = CategoryResource::collection($this->categoryRepository->getTopCategories());
        $products = $this->productsRepository->getProducts($page, $perPage);

        $totalCount = $products->total();
        $currentPage = $products->currentPage();
        $lastPage = $products->lastPage();
        $prevPage = ($currentPage > 1) ? $currentPage - 1 : null;
        $nextPage = ($currentPage < $lastPage) ? $currentPage + 1 : null;

        $response = [
            'categories' => $categories,
            'products' => ProductsResource::collection($products),
            'pagination' => [
                'total' => $totalCount,
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => $lastPage,
                'prev_page' => $prevPage,
                'next_page' => $nextPage,
            ],
        ];

        return response()->json($response);
    }


    public function brands($page = 1)
    {
        $columns = []; // Add your conditions here if needed
        $perPage = 20;
        $relations = ['products']; // Add your relationships here
        $brands = $this->brandsRepository->getBrands($page);
        



        $response = [
            'brands' => $brands,
            
        ];

        return response()->json($response);
    }

    public function brand($slug, $page = 1) {
        return ProductsResource::collection($this->brandsRepository->getProducts($slug, $page, 20));
    }

    public function products($type, $page = 1) {
        switch($type) {
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

        $conditions = [$type => 1];
        $products = Product::where([$type => 1,'status' => 1])->paginate(10);
        return $products;
        // return ProductsResource::collection($this->productsRepository->paginateWhere($conditions, $page, 20));

    }

    public function searchProduct(Request $request, $page = 1){

        $perPage = 15;
        $categories = CategoryResource::collection($this->categoryRepository->getTopCategories());
        $brands = BrandsResource::collection($this->brandsRepository->all());
        $products = $this->productsRepository->filterProducts($request, $page, $perPage);

        $totalCount = $products->total();
        $currentPage = $products->currentPage();
        $lastPage = $products->lastPage();
        $prevPage = ($currentPage > 1) ? $currentPage - 1 : null;
        $nextPage = ($currentPage < $lastPage) ? $currentPage + 1 : null;

        $response = [
            'products' => $products,
            'pagination' => [
                'total' => $totalCount,
                'per_page' => $perPage,
                'current_page' => $currentPage,
                'last_page' => $lastPage,
                'prev_page' => $prevPage,
                'next_page' => $nextPage,
            ],
            'categories' => $categories,
            'brands' => $brands,
        ];

        return response()->json($response);
    }


    public function searchPr(Request $request,$page = 1)
    {
      $products = [];
      $perPage = 15;
      $products = $this->productsRepository->searchProducts($request, $page, $perPage);

      $totalCount = $products->total();
      $currentPage = $products->currentPage();
      $lastPage = $products->lastPage();
      $prevPage = ($currentPage > 1) ? $currentPage - 1 : null;
      $nextPage = ($currentPage < $lastPage) ? $currentPage + 1 : null;


      $response = [
          'products' => $products,
          'pagination' => [
              'total' => $totalCount,
              'per_page' => $perPage,
              'current_page' => $currentPage,
              'last_page' => $lastPage,
              'prev_page' => $prevPage,
              'next_page' => $nextPage,
          ]
      ];

      return response()->json($response);
    }
}
