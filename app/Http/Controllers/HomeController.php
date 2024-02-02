<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Slider;
use App\Models\Category;
use App\Models\Product;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Faq;
use App\Models\TermsAndCondition;
use App\Models\Subscriber;
use App\Mail\SubscriptionVerification;
use App\Mail\ContactMessageInformation;
use App\Helpers\MailHelper;
use App\Models\EmailTemplate;
use App\Models\ProductSpecification;
use App\Models\ProductGallery;
use App\Models\Setting;
use App\Models\ContactMessage;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use App\Models\Order;
use App\Models\Wishlist;
use Mail;
use Str;
use Session;
use Cart;
use Carbon\Carbon;
use Route;
use Auth;
use App\Models\FooterSocialLink;
use App\Models\FooterLink;
use App\Models\Footer;
use App\Http\Resources\SliderResource;
use App\Repositories\SliderRepository;
use App\Repositories\CategoryRepository;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductsResource;
use App\Repositories\ProductsRepository;
class HomeController extends Controller
/**
 * @OA\Get(
 *     path="/api/productByCategory/{id}",
 *     summary="Получить продукты по категории",
 *     description="Получает продукты, относящиеся к указанной категории.",
 *     operationId="productByCategory",
 *     tags={"Products"},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="Идентификатор категории",
 *         required=true,
 *         @OA\Schema(
 *             type="integer",
 *             format="int64"
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Успешный запрос. Возвращает информацию о категории и продуктах.",
 *         @OA\JsonContent(
 *             @OA\Property(
 *                 property="category",
 *                 type="object",
 *                 description="Информация о категории",
 *                 @OA\Property(property="id", type="integer"),
 *                 @OA\Property(property="name", type="string"),
 *                 @OA\Property(property="slug", type="string"),
 *                 // Другие свойства категории
 *             ),
 *             @OA\Property(
 *                 property="products",
 *                 type="array",
 *                 description="Список продуктов",
 *                 @OA\Items(
 *                     type="object",
 *                     @OA\Property(property="id", type="integer"),
 *                     @OA\Property(property="name", type="string"),
 *                     @OA\Property(property="slug", type="string"),
 *                     // Другие свойства продукта
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="Категория не найдена"
 *     )
 * )
 */
{

    protected $categoryRepository;
    protected $sliderRepository;
    protected $productsRepository;
    protected $brandsRepository;

    public function __construct(SliderRepository $sliderRepository, CategoryRepository $categoryRepository, ProductsRepository $productsRepository) {
        $this->sliderRepository = $sliderRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productsRepository = $productsRepository;
    }

    public function homepage(){
        $product = Product::where(['show_homepage' => 1,'status' => 1])->get();
        return response()->json(['product'=> $product]);
    }

    public function websiteSetup(){
        $language = include(resource_path('lang/en/user.php'));
        $setting = Setting::select('logo','favicon','phone_number_required','default_phone_code','currency_icon','currency_name')->first();
        return response()->json([
            'language' => $language,
            'setting' => $setting
        ]);
    }

    protected function getCategoryWithSubcategories($category, $currentDepth, $maxDepth)
    {
        // Check if the maximum depth is reached
        if ($currentDepth > $maxDepth) {
            return null;
        }
        // Fetch subcategories for the current category
        $subcategories = Category::where(['parent_id' => $category->id, 'status' => 1])->get();
        // Initialize an array to store the category data
        $categoryData = [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'icon' => $category->icon,
            'status' => $category->status,
            'parent_id' => $category->parent_id,
            'image' => $category->image,
            'created_at' => $category->created_at,
            'updated_at' => $category->updated_at,
            'subcategories' => [],
        ];

        // Recursively fetch subcategories for each subcategory
        foreach ($subcategories as $subcategory) {
            $subcategoryData = $this->getCategoryWithSubcategories($subcategory, $currentDepth + 1, $maxDepth);
            if ($subcategoryData !== null) {
                $categoryData['subcategories'][] = $subcategoryData;
            }
        }

        return $categoryData;
    }

    public function wishlist(){
        $user = Auth::guard('api')->user();
        $wishlists = Wishlist::with('product')->where(['user_id' => $user->id])->paginate(10);
        return response()->json(['wishlists' => $wishlists]);
    }
    public function products(){
        $products = Product::where(['status' => 1])->paginate(10);
        return response()->json(['products' => $products]);
    }

    public function getFooter()
    {
      $first_col_links = FooterLink::where('column',1)->get();
      $footer = Footer::first();
      $columnTitle = $footer->first_column;
      $footer_first_col = array(
          'col_links' => $first_col_links,
          'columnTitle' => $columnTitle
      );
      $footer_first_col = (object)$footer_first_col;
      $second_col_links = Blog::where(['status' => 1])->latest()->take(3)->get();
      $columnTitle = $footer->second_column;
      $footer_second_col = array(
          'col_links' => $second_col_links,
          'columnTitle' => $columnTitle
      );
      $footer_second_col = (object)$footer_second_col;
      $third_col_links = FooterLink::where('column',3)->get();
      $columnTitle = $footer->third_column;
      $footer_third_col = array(
          'col_links' => $third_col_links,
          'columnTitle' => $columnTitle
      );
      $footer_third_col = (object)$footer_third_col;
      $social_links = FooterSocialLink::all();
      return response()->json([
        'footer_first_col' => $footer_first_col,
        'footer_second_col' => $footer_second_col,
        'footer_third_col' => $footer_third_col,
        'footer' => $footer
      ]);

    }

    public function subCategoriesByCategory($id){
        $subCategories = Category::where(['parent_id' => $id, 'status' => 1])->get();
        return response()->json(['subCategories' => $subCategories]);
    }

    public function categoryList(){
        $categories = Category::where('status', 1)->get();
        return response()->json(['categories' => $categories]);
    }

    public function category($id){
        $category = Category::find($id);
        return response()->json(['category' => $category]);
    }

    public function productByCategory($id){
        $category = Category::find($id);
        $products = Product::with('attributes')->where(['category_id' => $id, 'approve_by_admin' => 1])->orderBy('id','desc')->get();
        return response()->json(['category' => $category, 'products' => $products]);
    }


    public function getSlider()
    {
      $sliders = Slider::orderBy('serial','asc')->where(['status' => 1])->get();
      return response()->json(['sliders' => $sliders]);
    }

    public function index()
    {
        $sliders = SliderResource::collection($this->sliderRepository->getSlides());
        $brands = Brand::where(['status' => 1])->get()->take(9);
        $topRatedProducts = ProductsResource::collection($this->productsRepository->getTopProducts(6));
        $newArrivalProducts = ProductsResource::collection($this->productsRepository->getNewArrivalProducts(6));
        $bestProducts = ProductsResource::collection($this->productsRepository->getBestProducts(6));
        $homepage_categories = CategoryResource::collection($this->categoryRepository->getTopCategories());
        return response()->json([
            'sliders' => $sliders,
            'homepage_categories' => $homepage_categories,
            'brands' => $brands,
            'topRatedProducts' => $topRatedProducts,
            'newArrivalProducts' => $newArrivalProducts,
            'bestProducts' => $bestProducts,
        ]);
    }

    public function sendContactMessage(Request $request){

        $rules = [

            'name'=>'required',

            'email'=>'required',

            'subject'=>'required',

            'message'=>'required',

        ];

        $this->validate($request, $rules);



        $setting = Setting::first();

        if($setting->enable_save_contact_message == 1){

            $contact = new ContactMessage();

            $contact->name = $request->name;

            $contact->email = $request->email;

            $contact->subject = $request->subject;

            $contact->phone = $request->phone;

            $contact->message = $request->message;

            $contact->save();

        }



        MailHelper::setMailConfig();

        $template = EmailTemplate::where('id',2)->first();

        $message = $template->description;

        $subject = $template->subject;

        $message = str_replace('{{name}}',$request->name,$message);

        $message = str_replace('{{email}}',$request->email,$message);

        $message = str_replace('{{phone}}',$request->phone,$message);

        $message = str_replace('{{subject}}',$request->subject,$message);

        $message = str_replace('{{message}}',$request->message,$message);



        Mail::to($setting->contact_email)->send(new ContactMessageInformation($message,$subject));



        $notification = trans('user_validation.Message send successfully');

        return response()->json(['notification' => $notification]);

    }



    public function blog(Request $request){

        $blogs = Blog::orderBy('id','desc')->where(['status' => 1]);



        if($request->search){

            $blogs = $blogs->where('title','LIKE','%'.$request->search.'%');

        }



        if($request->category){

            $category = BlogCategory::where('slug',$request->category)->first();

            $blogs = $blogs->where('blog_category_id', $category->id);

        }



        $blogs = $blogs->paginate(15);

        return response()->json(['blogs' => $blogs]);

    }



    public function blogDetail($slug){

        $blog = Blog::where(['status' => 1, 'slug'=>$slug])->first();

        // $blog->views += 1;

        // $blog->save();
        $categories = BlogCategory::where(['status' => 1])->get();
        return response()->json(['blog' => $blog, 'categories' => $categories]);

    }

    public function faq(){

        $faqs = FAQ::orderBy('id','desc')->where('status',1)->get();

        return response()->json(['faqs' => $faqs]);

    }

    public function termsAndCondition(){

        $terms_conditions = TermsAndCondition::select('terms_and_condition')->first();

        return response()->json(['terms_conditions'=> $terms_conditions]);

    }

    public function privacyPolicy(){

        $privacyPolicy = TermsAndCondition::select('privacy_policy')->first();

        return response()->json(['privacyPolicy'=> $privacyPolicy]);

    }

    public function variantItemsByVariant($name){

        $variantItemsForSearch = ProductVariantItem::with('product','variant')->groupBy('name')->select('name','id')->where('product_variant_name', $name)->get();



        return response()->json(['variantItemsForSearch' => $variantItemsForSearch]);

    }

    public function product(Request $request){
        // DB::enableQueryLog();
        $productsQuery = $this->productsRepository->getProducts($request);
        // return response()->json([
        //     'categories' => $productsQuery,

        // ]);
        $paginateQty = 10; // Default to 10 if not set
        $productsQuery->select('id','short_name', 'price', 'short_description', 'slug', 'thumb_image');
        $products = $productsQuery->paginate($paginateQty);
        // dd(DB::getQueryLog());
        // Additional data retrieval
        $categories = Category::where(['status' => 1])->select('id', 'name', 'slug', 'icon')->get();
        $brands = Brand::where(['status' => 1])->select('id', 'name', 'slug')->get();
        $activeVariants = ProductVariant::with('activeVariantItems')->select('name','id')->groupBy('name')->get();

        // Prepare response
        return response()->json([
            'categories' => $categories,
            'activeVariants' => $activeVariants,
            'brands' => $brands,
            'products' => $products
        ]);
    }

    public function searchProduct1(Request $request){
        $brandIds = $request->input('brand_ids', []);
        $categoryIds = $request->input('category_ids', []);
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $page = $request->input('page', 1);
        $categories = Category::where(['status' => 1])->select('id','name','slug','icon')->get();

        $brands = Brand::where(['status' => 1])->select('id','name','slug')->get();
        $query = Product::query();
         if (!empty($brandIds)) {
            $query->whereIn('brand_id', $brandIds);
         }

         if (!empty($categoryIds)) {
            $query->whereIn('category_id', $categoryIds);
         }

         if (!is_null($minPrice)) {
            $query->where('price', '>=', $minPrice);
         }

         if (!is_null($maxPrice)) {
            $query->where('price', '<=', $maxPrice);
         }

         if($request->per_page){

            $products = $query->paginate($request->per_page);

         }else{

           $products = $query->paginate(15);

         }
        return response()->json([
           'products' => $products,
           'brands' => $brands,
           'categories' => $categories
        ]);
    }

    public function searchProduct(Request $request){

        $products = Product::where('status',1)->where('approve_by_admin',1);



        if($request->shorting_id){

            if($request->shorting_id == 1){

                $products = $products->orderBy('id','desc');

            }else if($request->shorting_id == 2){

                $products = $products->orderBy('price','asc');

            }else if($request->shorting_id == 3){

                $products = $products->orderBy('price','desc');

            }

        }else{

            $products = $products->orderBy('id','desc');

        }





        if($request->category) {

            $category = Category::where('slug',$request->category)->first();

            $products = $products->where('category_id', $category->id);

        }



        if($request->brand) {

            $brand = Brand::where('slug',$request->brand)->first();

            $products = $products->where('brand_id', $brand->id);

        }



        $brandSortArr = [];

        if($request->brands){

            foreach($request->brands as $brand){

                $brandSortArr[] = $brand;

            }

            $products = $products->whereIn('brand_id', $brandSortArr);

        }



        $categorySortArr = [];

        if($request->categories){
            $category = $request->categories;

            $products->whereHas('categories', function ($q) use ($category) {
                foreach($category as $key => $value) {
                    if($key == 0) {
                        $q->whereRaw("FIND_IN_SET(?, categories_ids)", [$value]);
                    } else {
                        $q->orWhereRaw("FIND_IN_SET(?, categories_ids)", [$value]);
                    }
                }
            });

            // $products = $products->whereIn('category_id', $categorySortArr);

        }




        if($request->highlight){



            if($request->highlight == 'top_product'){

                $products = $products->where('is_top',1);

            }



            if($request->highlight == 'new_arrival'){

                $products = $products->where('new_product',1);

            }



            if($request->highlight == 'featured_product'){

                $products = $products->where('is_featured',1);

            }



            if($request->highlight == 'best_product'){

                $products = $products->where('is_best',1);

            }



        }





        if($request->min_price){

            if($request->min_price > 0){

                $products = $products->where('price', '>=', $request->min_price);

            }

        }



        if($request->max_price){

            if($request->max_price > 0){

                $products = $products->where('price', '<=', $request->max_price);

            }

        }







        if($request->search){

            $products = $products->where('name', 'LIKE', '%'. $request->search. "%")

                                ->orWhere('long_description','LIKE','%'.$request->search.'%');

        }



        $products = $products->select('id','name', 'short_name', 'slug', 'thumb_image','qty','sold_qty', 'price', 'offer_price','is_undefine','is_featured','new_product', 'is_top', 'is_best','brand_id');

        if($request->per_page){

            $products = $products->paginate($request->per_page);

        }else{

            $products = $products->paginate(15);

        }



        $products = $products->appends($request->all());



        return response()->json(['products' => $products]);



    }




    public function searchPr(Request $request,$page = 1)
    {
      $products = [];
      $perPage = 15;
      if($request->search){
        $searchTerm = $request->search;

        $products = Product::with(['attributes.variant', 'attributes.variantItem'])
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
          }else {
            $products = Product::where('status', 1)->paginate(10);
          }
      $totalCount = $products->total();
      $currentPage = $products->currentPage();
      $lastPage = $products->lastPage();
      $prevPage = ($currentPage > 1) ? $currentPage - 1 : null;
      $nextPage = ($currentPage < $lastPage) ? $currentPage + 1 : null;
      return response()->json(['products' => $products,
      'pagination' => [
          'total' => $totalCount,
          'per_page' => $perPage,
          'current_page' => $currentPage,
          'last_page' => $lastPage,
          'prev_page' => $prevPage,
          'next_page' => $nextPage,
      ]]);

    }
    public function productDetail($slug){

        $product = Product::where('slug' , $slug)->with('brand','gallery','specifications','attributes')->first();

        if(!$product){

            $notification = trans('user_validation.Something went wrong');

            return response()->json(['message' => $notification],403);

        }


      $relatedProducts = Product::where(['category_id' => $product->category_id, 'status' => 1,'approve_by_admin' => 1])->where('id' , '!=', $product->id)->get()->take(3);

        return response()->json([

            'product' => $product,

            'similarProducts' => $relatedProducts,
        ]);

    }

    public function subscribeRequest(Request $request){

        if($request->email != null){

            $isExist = Subscriber::where('email', $request->email)->count();

            if($isExist == 0){

                $subscriber = new Subscriber();

                $subscriber->email = $request->email;

                $subscriber->verified_token = random_int(100000, 999999);

                $subscriber->save();



                MailHelper::setMailConfig();



                $template=EmailTemplate::where('id',3)->first();

                $message=$template->description;

                $subject=$template->subject;

                Mail::to($subscriber->email)->send(new SubscriptionVerification($subscriber,$message,$subject));



                return response()->json(['message' => trans('user_validation.Subscription successfully, please verified your email')]);



            }else{

                return response()->json(['message' => trans('user_validation.Email already exist'),403],403);

            }

        }else{

            return response()->json(['message' => trans('user_validation.Email Field is required')],403);

        }

    }



    public function subscriberVerifcation($email, $token){

        $subscriber = Subscriber::where(['verified_token' => $token, 'email' => $email])->first();

        if($subscriber){

            $subscriber->verified_token = null;

            $subscriber->is_verified = 1;

            $subscriber->save();



            $setting = Setting::first();

            $frontend_url = $setting->frontend_url;



            return redirect($frontend_url);

        }else{

            $setting = Setting::first();

            $frontend_url = $setting->frontend_url;

            return redirect($frontend_url);

        }
    }

}
