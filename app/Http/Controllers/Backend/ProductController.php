<?php

namespace App\Http\Controllers\Backend;

use Exception;
use League\Csv\Reader;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use App\Services\ProductStockListService;
use Illuminate\Support\Facades\Validator;


class ProductController extends Controller
{

    protected $productStockList;
    public function __construct(ProductStockListService $productStockListService)
    {
        $this->productStockList = $productStockListService;
    }

    //all products
    public function productList()
    {
        $products =Cache::remember('productsList', 3600, function () {
           return  Product::with('category')
        ->select('image', 'id', 'category_id', 'name', 'parts_no', 'rack_no', 'column_no', 'row_no', 'unit', 'unit_type', 'brand_name', 'description')
            ->orderBy('id', 'desc')->get();

        });

        return response()->json([
            'products' => $products,
            'message' => 'success'
        ], 200);
    }

    // low stock
    public function lowStock(Request $request)
    {
        $minimumSotck =Cache::remember('lowStock', 3600, function () {
           return Product::whereColumn('unit', '<=', 'minimum_stock')->with('category')
        ->select('id', 'category_id', 'name', 'parts_no', 'rack_no', 'column_no', 'row_no', 'unit', 'unit_type', 'brand_name')
            ->orderBy('id', 'desc')->get();
        });

        return response()->json([
            'minimumSotck' => $minimumSotck,
            'message' => 'success'
        ], 200);
    }


    //product stock list
    public function productStockList(Request $request)
    {


        $allProducts = $this->productStockList->productStockList($request);

        return response()->json([
            'allProducts' => $allProducts,
            'message' => 'success',
        ], 200);
    }


    //create product
    public function createProduct(Request $request)
    {

        // $csv = Reader::createFromPath(public_path('products/electrical.csv'));
        // $reader = $csv->getRecords();
        // foreach ($reader as $row) {

        //     Product::create([
        //         'name' => $row[1],
        //         'unit' => is_numeric($row[3]) ? $row[3] : 0, // যদি সংখ্যা না হয়, তাহলে 0 দাও
        //         'unit_type' => $row[2],
        //         'category_id' => 9,
        //     ]);

        // }

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'unit' => 'required|numeric|min:0',
            'unit_type' => 'required',
            'category_id' => 'required',
            'minimum_stock' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['errors' => $validator->errors()]);
        }

        try {
            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'unit' => $request->unit,
                'unit_type' => $request->unit_type,
                'minimum_stock' => $request->minimum_stock,
                'parts_no' => $request->parts_no,
                'rack_no' => $request->rack_no,
                'column_no' => $request->column_no,
                'row_no' => $request->row_no,
                'brand_name' => $request->brand_name
            ];

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $fileName);
                $data['image'] = $fileName;
            }

            Product::create($data);
            Artisan::call('cache:clear');
            return redirect()->back()->with(['status' => true, 'message' => 'Product created successfully']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong']);
        }
    }


    //update product
    public function updateProduct(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'unit' => 'required|numeric|min:0',
            'unit_type' => 'required',
            'category_id' => 'required',
            'minimum_stock' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with(['errors' => $validator->errors()]);
        }

        try {
            $product = Product::findOrFail($request->product_id);

            $data = [
                'name' => $request->name,
                'description' => $request->description,
                'category_id' => $request->category_id,
                'unit' => $request->unit,
                'unit_type' => $request->unit_type,
                'minimum_stock' => $request->minimum_stock,
                'parts_no' => $request->parts_no,
                'rack_no' => $request->rack_no,
                'column_no' => $request->column_no,
                'row_no' => $request->row_no,
                'brand_name' => $request->brand_name
            ];

            if ($request->hasFile('image')) {

                $validator = Validator::make($request->all(), [

                    'image' => 'image|mimes:jpeg,png,jpg,gif,svg,webp|max:100',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->with(['errors' => $validator->errors()]);
                }

                $image = $request->file('image');
                $fileName = time() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads'), $fileName);
                $data['image'] = $fileName;


                $oldImage = $product->image;
                File::delete(public_path('uploads/' . $oldImage));

                $product->update($data);
                return redirect()->back()->with(['status' => true, 'message' => 'Product updated successfully']);
            } else {

                $oldImage = $product->image;
                if ($oldImage != null && $request->image == null) {

                    File::delete(public_path('uploads/' . $oldImage));
                    $data['image'] = null;
                    $product->update($data);
                    return redirect()->back()->with(['status' => true, 'message' => 'Product updated successfully']);
                } else {

                    $data['image'] = $request->image;
                    $product->update($data);
                    return redirect()->back()->with(['status' => true, 'message' => 'Product updated successfully']);
                }
            }
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => 'Something went wrong']);
        }
    }

    //delete product
    public function deleteProduct(Request $request)
    {

        try {
            $product = Product::findOrFail($request->product_id);
            $oldImage = $product->image;

            $product->delete();

            if ($oldImage != null) {
                File::delete(public_path('uploads/' . $oldImage));
            }


            return redirect()->back()->with(['status' => true, 'message' => 'Product deleted successfully']);
        } catch (Exception $e) {
            return redirect()->back()->with(['status' => false, 'message' => 'somethintg went wrong']);
        }
    }
}
