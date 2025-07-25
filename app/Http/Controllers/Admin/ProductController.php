<?php

namespace App\Http\Controllers\Admin;
use App\Events\NewProductCreated;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Notification;
use App\Models\OrderProduct;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductGallery;
use App\Models\ProductSpecification;
use App\Models\ProductSpecificationKey;
use App\Models\ProductSubcategory;
use App\Models\ProductVariant;
use App\Models\ProductVariantItem;
use App\Models\Setting;
use App\Models\ShoppingCart;
use App\Models\ShoppingCartVariant;
use App\Models\Wishlist;
use Exception;
use File;
use Illuminate\Http\Request;
use Image;
use Str;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        $products = Product::orderBy('id','desc')->get();
        $orderProducts = OrderProduct::all();
        $setting = Setting::first();
        $frontend_url = $setting->frontend_url;
        $frontend_url = $frontend_url.'/single-product?slug=';
        return view('admin.product',compact('products','orderProducts','setting','frontend_url'));
    }



    public function stockoutProduct(){
        $products = Product::with('brand')->get();
        $orderProducts = OrderProduct::all();
        $setting = Setting::first();
        $frontend_url = $setting->frontend_url;
        $frontend_url = $frontend_url.'/single-product?slug=';

        return view('admin.stockout_product',compact('products','orderProducts','setting','frontend_url'));

    }



    public function create()
    {
        $categories = Category::where('parent_id',0)->get();
        $brands = Brand::all();
        $variants = ProductVariant::with('variantItems')->get();
        $specificationKeys = ProductSpecificationKey::all();

        return view('admin.create_product',compact('categories','variants','brands','specificationKeys'));
    }

    public function store(Request $request)
    {
        $rules = [
            'short_name' => 'required',
            'name' => 'required',
            'slug' => 'required|unique:products',
            'subcategories' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
            'price' => 'required|numeric',
        ];
        $customMessages = [
            'short_name.required' => trans('admin_validation.Short name is required'),
            'short_name.unique' => trans('admin_validation.Short name is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'category.required' => trans('admin_validation.Category is required'),
            'short_description.required' => trans('admin_validation.Short description is required'),
            'long_description.required' => trans('admin_validation.Long description is required'),
            'price.required' => trans('admin_validation.Price is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        $product = new Product();
        if($request->thumb_image){
            $extention = $request->thumb_image->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::make($request->thumb_image)
                ->save(public_path().'/'.$image_name);
            $product->thumb_image=$image_name;
        }

        $product->short_name = $request->short_name;
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->brand_id = $request->brand ? $request->brand : 0;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->offer_price = $request->offer_price;
        $product->short_description = $request->short_description;
        $product->long_description = $request->long_description;
        $product->status = $request->status??0;
        $product->is_undefine = 1;
        $product->is_specification = $request->is_specification ? 1 : 0;
        $product->seo_title = $request->seo_title ? $request->seo_title : $request->name;
        $product->seo_description = $request->seo_description ? $request->seo_description : $request->name;
        $product->is_top = $request->top_product ? 1 : 0;
        $product->new_product = $request->new_arrival ? 1 : 0;
        $product->is_best = $request->best_product ? 1 : 0;
        $product->is_featured = $request->is_featured ? 1 : 0;
        $product->approve_by_admin = 1;
        $product->show_homepage = $request->show_homepage;
        $product->save();
        if($request->gallery){
          foreach ($request->gallery as $key => $gallery) {
            $extention = $gallery->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::make($gallery)
                ->save(public_path().'/'.$image_name);
            $product_gallery = new ProductGallery();
            $product_gallery->image=$image_name;
            $product_gallery->product_id=$product->id;
            $product_gallery->save();
          }
        }
        if($request->is_specification){
            $exist_specifications=[];
            if($request->keys){
                foreach($request->keys as $index => $key){
                    if($key){
                        if($request->specifications[$index]){
                            if(!in_array($key, $exist_specifications)){
                                $productSpecification= new ProductSpecification();
                                $productSpecification->product_id = $product->id;
                                $productSpecification->product_specification_key_id = $key;
                                $productSpecification->specification = $request->specifications[$index];
                                $productSpecification->save();
                            }
                            $exist_specifications[] = $key;
                        }
                    }
                }
            }
            if($request->new_keys){
                foreach($request->new_keys as $index => $key){
                    if($key){
                      $spec = ProductSpecificationKey::where('key',$key)->first();
                      if(!empty($spec)){
                        $productSpecification= new ProductSpecification();
                        $productSpecification->product_id = $product->id;
                        $productSpecification->product_specification_key_id = $spec->id;
                        $productSpecification->specification = $request->new_specifications[$index];
                        $productSpecification->save();
                      }else {
                        $SpecificationKey = new ProductSpecificationKey();
                        $SpecificationKey->key = $key;
                        $SpecificationKey->status = 1;
                        $SpecificationKey->save();
                        $productSpecification= new ProductSpecification();
                        $productSpecification->product_id = $product->id;
                        $productSpecification->product_specification_key_id = $SpecificationKey->id;
                        $productSpecification->specification = $request->new_specifications[$index];
                        $productSpecification->save();
                      }
                    }
                }
            }
        }


        $variants = $request->input('variant');
        $variantItems = $request->input('variant_item');
        $variantTexts = $request->input('text');

        foreach ($variants as $index => $variantId) {
            $variantItem = $variantItems[$index];
            $variantText = $variantTexts[$index];
            if (!empty($variantId)) {

            // Save the updated product attribute in the product_attributes table
              $product->attributes()->create([
                  'variant_id' => $variantId,
                  'variant_item_id' => $variantItem,
                  'text' => $variantText
              ]);
            }
        }
        $productSubcategory = new ProductSubcategory();
        $productSubcategory->product_id = $product->id;
        $productSubcategory->categories_ids = implode(',', $request->input('subcategories'));
        $productSubcategory->save();
        $adminsWithType1 = Admin::where('admin_type', 1)->get();

       foreach ($adminsWithType1 as $admin) {
           $notification = new Notification([
               'admin_id' => $admin->id,
               'message' => 'Created new product'.' '.$product->name,
               'link' => route('admin.product.show', $product->id),
           ]);
           $notification->save();
       }
        event(new NewProductCreated($product));

        $notification = trans('admin_validation.Created Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product.index')->with($notification);
    }

    public function show($id)
    {
        $product = Product::with('brand','gallery','specifications','attributes')->find($id);


        return view('admin.view_product',compact('product'));
    }
    public function getVariantItems(Request $request)
    {
        $variantId = $request->input('product_variant_id');
        $variantItems = ProductVariantItem::where('product_variant_id', $variantId)->get();

        return response()->json($variantItems);
    }

    public function edit($id)
    {
        $product = Product::with('brand','gallery','attributes')->find($id);
        $selectedCategoryIds = ProductSubcategory::where('product_id',$id)->first();
        $selectedCategories = [];
        if (isset($selectedCategoryIds)) {
          $selectedCategoryIds = explode(",",$selectedCategoryIds->categories_ids);
          foreach ($selectedCategoryIds as $key => $value) {
            $data =  Category::find($value);
            array_push($selectedCategories,$data);
          }
        }

        $variants = ProductVariant::with('variantItems')->get();

        $rootCategories = Category::where('parent_id', 0)->get();

        $brands = Brand::all();
        $specificationKeys = ProductSpecificationKey::all();
        $productSpecifications = ProductSpecification::where('product_id',$product->id)->get();

        return view('admin.edit_product',compact('selectedCategoryIds','variants','selectedCategories','rootCategories','brands','specificationKeys','product','productSpecifications'));

    }


    public function update(Request $request, $id)
    {

        $product = Product::find($id);
        $rules = [
            'short_name' => 'required',
            'name' => 'required',
            'slug' => 'required|unique:products,slug,'.$product->id,
            'subcategories' => 'required',
            'short_description' => 'required',
            'long_description' => 'required',
            'price' => 'required|numeric',
        ];
        $customMessages = [
            'short_name.required' => trans('admin_validation.Short name is required'),
            'short_name.unique' => trans('admin_validation.Short name is required'),
            'name.required' => trans('admin_validation.Name is required'),
            'name.unique' => trans('admin_validation.Name is required'),
            'slug.required' => trans('admin_validation.Slug is required'),
            'slug.unique' => trans('admin_validation.Slug already exist'),
            'subcategories.required' => trans('admin_validation.Category is required'),
            'banner_image.required' => trans('admin_validation.Banner is required'),
            'short_description.required' => trans('admin_validation.Short description is required'),
            'long_description.required' => trans('admin_validation.Long description is required'),
            'brand.required' => trans('admin_validation.Brand is required'),
            'price.required' => trans('admin_validation.Price is required'),
        ];
        $this->validate($request, $rules,$customMessages);

        if($request->thumb_image){
            $old_thumbnail = $product->thumb_image;
            $extention = $request->thumb_image->getClientOriginalExtension();
            $image_name = Str::slug($request->name).date('-Y-m-d-h-i-s-').rand(999,9999).'.'.$extention;
            $image_name = 'uploads/custom-images/'.$image_name;
            Image::make($request->thumb_image)
                ->save(public_path().'/'.$image_name);
            $product->thumb_image=$image_name;
            $product->save();
            if($old_thumbnail){
                if(File::exists(public_path().'/'.$old_thumbnail))unlink(public_path().'/'.$old_thumbnail);
            }
        }


        $product->short_name = $request->short_name;
        $product->name = $request->name;
        $product->slug = $request->slug;
        $product->brand_id = $request->brand ? $request->brand : 0;
        $product->sku = $request->sku;
        $product->price = $request->price;
        $product->offer_price = $request->offer_price;
        $product->short_description = $request->short_description;
        $product->long_description = $request->long_description;
        $product->tags = $request->tags;
        $product->status = $request->status?$request->status:0;
        $product->is_specification = $request->is_specification ? 1 : 0;
        $product->seo_title = $request->seo_title ? $request->seo_title : $request->name;
        $product->seo_description = $request->seo_description ? $request->seo_description : $request->name;
        $product->is_top = $request->top_product ? 1 : 0;
        $product->new_product = $request->new_arrival ? 1 : 0;
        $product->is_best = $request->best_product ? 1 : 0;
        $product->is_featured = $request->is_featured ? 1 : 0;
        $product->show_homepage = $request->show_homepage;

        $product->approve_by_admin = $request->approve_by_admin;
        $product->save();
        $productSubcategory = ProductSubcategory::where('product_id',$product->id)->first();
        if (!empty($productSubcategory)) {
          $productSubcategory->categories_ids = $request->input('subcategories')?implode(',', $request->input('subcategories')):'';
          $productSubcategory->save();

        }

        $product->attributes()->delete();
        $variants = $request->input('variant');
        $variantItems = $request->input('variant_item');
        $variantTexts = $request->input('text');

        foreach ($variants as $index => $variantId) {
            $variantItem = $variantItems[$index];
            $variantText = $variantTexts[$index];
            if (!empty($variantId)) {

            // Save the updated product attribute in the product_attributes table
            $product->attributes()->create([
                'variant_id' => $variantId,
                'variant_item_id' => $variantItem,
                'text' => $variantText
            ]);
          }
        }
        $notification = trans('admin_validation.Update Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product.index')->with($notification);
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        $gallery = $product->gallery;
        $old_thumbnail = $product->thumb_image;
        $product->delete();
        if($old_thumbnail){
            if(File::exists(public_path().'/'.$old_thumbnail))unlink(public_path().'/'.$old_thumbnail);
        }
        foreach($gallery as $image){
            $old_image = $image->image;
            $image->delete();
            if($old_image){
                if(File::exists(public_path().'/'.$old_image))unlink(public_path().'/'.$old_image);
            }
        }
        ProductAttribute::where('product_id',$id)->delete();
        ProductSpecification::where('product_id',$id)->delete();
        Wishlist::where('product_id',$id)->delete();
        $cartProducts = ShoppingCart::where('product_id',$id)->get();
        foreach($cartProducts as $cartProduct){
            ShoppingCartVariant::where('shopping_cart_id', $cartProduct->id)->delete();
            $cartProduct->delete();
        }

        $notification = trans('admin_validation.Delete Successfully');
        $notification=array('messege'=>$notification,'alert-type'=>'success');
        return redirect()->route('admin.product.index')->with($notification);
    }

    public function changeStatus($id){
        $product = Product::find($id);
        if($product->status == 1){
            $product->status = 0;
            $product->save();
            $message = trans('admin_validation.InActive Successfully');
        }else{
            $product->status = 1;
            $product->save();
            $message = trans('admin_validation.Active Successfully');
        }
        return response()->json($message);
    }

    public function productApproved($id){
        $product = Product::find($id);
        if($product->approve_by_admin == 1){
            $product->approve_by_admin = 0;
            $product->save();
            $message = trans('admin_validation.Reject Successfully');
        }else{
            $product->approve_by_admin = 1;
            $product->save();
            $message = trans('admin_validation.Approved Successfully');
        }
        return response()->json($message);
    }

    public function removedProductExistSpecification($id){
        $productSpecification = ProductSpecification::find($id);
        $productSpecification->delete();
        $message = trans('admin_validation.Removed Successfully');
        return response()->json($message);
    }

    public function getSubcategories($id)
    {
        $subcategories = Category::where('parent_id', $id)->get();
        return response()->json($subcategories);
    }


}
