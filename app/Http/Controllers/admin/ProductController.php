<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductRating;
use App\Models\SubCategory;
use App\Models\TempImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    private $categoryRepository;
    private $productRepository;
    private $subCategoryRepository;
    private $brandRepository;
    private $productImageRepository;

    public function __construct(Category $category, Product $product, SubCategory $subCategory, Brand $brand, ProductImage $productImage)
    {
        $this->categoryRepository = $category;
        $this->productRepository = $product;
        $this->subCategoryRepository = $subCategory;
        $this->brandRepository = $brand;
        $this->productImageRepository = $productImage;
    }

    public function index(Request $request)
    {
        $products = $this->productRepository->latest('id')->with('product_images');
        if ($request->get('searchText')) {
            $products = $products->where('title', 'like', '%' . $request->get('searchText') . '%');
        }
        $products = $products->paginate(10);
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = $this->categoryRepository->all();
        $subcategories = $this->subCategoryRepository->all();
        $brands = $this->brandRepository->orderBy("name", "ASC")->get();
        return view('admin.product.create', compact('categories', 'subcategories', 'brands'));
    }

    public function store(Request $request)
    {
        $rule = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
            'barcode' => 'required|unique:products,barcode',
            'compare_price' => 'required|numeric',
        ];

        if (!empty($request->track_qty) && $request->track_qty == 'Yes') {
            $rule['qty'] = 'required|numeric';
        }

        $validator = Validator::make($request->all(), $rule,
        [
            'title.required' => 'Tên sản phẩm không được để trống.',
            'slug.required' => 'Slug sản phẩm không được để trống.',
            'slug.unique' => 'Slug này đã tồn tại, vui lòng chọn một slug khác.',
            'price.required' => 'Giá sản phẩm không được để trống.',
            'price.numeric' => 'Giá sản phẩm phải là số.',
            'sku.required' => 'SKU không được để trống.',
            'sku.unique' => 'SKU này đã tồn tại, vui lòng chọn một SKU khác.',
            'track_qty.required' => 'Trạng thái theo dõi số lượng không được để trống.',
            'track_qty.in' => 'Trạng thái theo dõi số lượng phải là "Yes" hoặc "No".',
            'category.required' => 'Danh mục sản phẩm không được để trống.',
            'category.numeric' => 'Danh mục sản phẩm phải là một số.',
            'is_featured.required' => 'Trạng thái nổi bật không được để trống.',
            'is_featured.in' => 'Trạng thái nổi bật phải là "Yes" hoặc "No".',
            'barcode.required' => 'Mã vạch sản phẩm không được để trống.',
            'barcode.unique' => 'Mã vạch này đã tồn tại, vui lòng chọn một mã vạch khác.',
            'compare_price.required' => 'Giá so sánh không được để trống.',
            'compare_price.numeric' => 'Giá so sánh phải là số.',
            'qty.required' => 'Số lượng sản phẩm không được để trống.',
            'qty.numeric' => 'Số lượng sản phẩm phải là một số.',
        ]);

        if ($validator->passes()) {
            $product = $this->productRepository->create([
                'title' => $request->title,
                'slug' => $request->slug,
                'description' => $request->description,
                'price' => $request->price,
                'compare_price' => $request->compare_price,
                'category_id' => $request->category,
                'brand_id' => $request->brand,
                'sub_category_id' => $request->sub_category,
                'is_featured' => $request->is_featured,
                'sku' => $request->sku,
                'barcode' => $request->barcode,
                'track_qty' => $request->track_qty,
                'qty' => 0,
                'status' => $request->status,
            ]);

            //save Gallery Pics
            if (!empty($request->image_array)) {
                foreach ($request->image_array as $temp_image_id) {
                    $tempImageInfo = TempImage::find($temp_image_id);
                    $extArray = explode('.', $tempImageInfo->name);
                    $ext = last($extArray); //jpg,gif,png,jpeg

                    $productImage = new ProductImage();
                    $productImage->product_id = $product->id;
                    $productImage->image = 'NULL';
                    $productImage->save();

                    $imageName = $product->id . '-' . $productImage->id . '-' . time() . '.' . $ext;
                    $productImage->image = $imageName;
                    $productImage->save();

                    // generate product thumbnail
                    // Large Image
                    $oldPath = public_path() . '/temp/' . $tempImageInfo->name;
                    $newPath = public_path() . '/uploads/products/large/' . $imageName;
                    File::copy($oldPath, $newPath);
                }
            }

            $request->session()->flash('success', 'Product added successfully!');
            return response()->json([
                'status' => true,
                'message' => 'Product added successfully!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function show($id)
    {

    }

    public function edit($id)
    {
        $brands = $this->brandRepository->all();
        $categories = $this->categoryRepository->all();
        $product = $this->productRepository->find($id);

        if (empty($product)) {
            return redirect()->route('admin.products.index')->with('error', 'Product not found');
        }

        //fetch product images
        $productImages = $this->productImageRepository->where('product_id', $id)->get();

        $subcategories = $this->subCategoryRepository->where('category_id', $product->category_id)->get();
        return view('admin.product.edit', compact('product', 'categories', 'brands', 'subcategories', 'productImages'));
    }

    public function update(Request $request, $id)
    {
        $product = $this->productRepository->find($id);

        $rule = [
            'title' => 'required',
            'slug' => 'required|unique:products,slug,' . $product->id . 'id',
            'price' => 'required|numeric',
            'sku' => 'required|unique:products,sku,' . $product->id . 'id',
            'track_qty' => 'required|in:Yes,No',
            'category' => 'required|numeric',
            'is_featured' => 'required|in:Yes,No',
            'barcode' => 'required|unique:products,barcode,' . $product->id . 'id',
            'compare_price' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rule);
        if ($validator->passes()) {
            $product->update([
                'title' => $request->title,
                'slug' => $request->slug,
                'description' => $request->description,
                'price' => $request->price,
                'compare_price' => $request->compare_price,
                'category_id' => $request->category,
                'brand_id' => $request->brand,
                'sub_category_id' => $request->sub_category,
                'is_featured' => $request->is_featured,
                'sku' => $request->sku,
                'barcode' => $request->barcode,
                'track_qty' => $request->track_qty,
                'status' => $request->status,
            ]);

            //update product gallery

            $request->session()->flash('success', 'Product updated successfully!');

            return response()->json([
                'status' => true,
                'message' => 'Product was updated successfully!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }

    }

    public function destroy($id, Request $request)
    {
        $product = $this->productRepository->find($id);
        if (empty($product)) {
            $request->session()->flash('error', 'Product not found.');

            return response()->json([
                'status' => false,
                'notFound' => true,
                'message' => 'Product not found.'
            ]);
        }

        //delete product gallery

        //delete product -> soft delete
        $product->delete();

        $request->session()->flash('success', 'Product deleted successfully.');
        return response()->json([
            'status' => true,
            'message' => 'Product deleted successfully.'
        ]);
    }



    public function rating() {
        $ratings = ProductRating::orderBy('product_id', 'desc')->paginate(10);
        return view('admin.product.rating', compact('ratings'));
    }
    public function approveRating($id, Request $request)
    {
        $rating = ProductRating::find($id);
        ProductRating::find($id)->update([
            'status' => $rating->status == 1 ? 0 : 1
        ]);
        $request->session()->flash('success', 'Đã đánh giá sản phẩm.');
        return response()->json([
            'status' => true,
            'message' => 'Product rating approved successfully.'
        ]);
    }
}
