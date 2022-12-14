<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Http\Resources\VariantResource;
use App\Http\Validators\Product\ProductCreateValidator;
use App\Http\Validators\Product\ProductUpdateValidator;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Models\ProductVariantDetail;
use App\Models\ProductVariant;
use App\Models\ProductVariantDetailById;
use App\Models\ProductImportSlipDetail;
use App\Http\Resources\ProductsHaveComemntCollection;
class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       $input = $request->all();
       $dataProducts = Product::where('is_active',$input['is_active'] ?? 1)->where('deleted_at',null)->where(function ($query) use ($input) {
            if(!empty($input['name'])){
                $query->where('name', 'like', '%'.$input['name'].'%');
            }
            if(!empty($input['slug'])){
                $query->where('slug', 'like', '%'.$input['slug'].'%');
            }

       })->paginate($input['limit'] ?? 9);

       $dataReturn = [];
       foreach($dataProducts as $key => $value){

                $value ->create_by_name = product::getNameCreated($value->created_by)->created_by_name;
                $value ->update_by_name = product::getNameCreated($value->updated_by)->created_by_name;
                $value->collection_images = explode(',',$value->collection_images);
                $value-> cartegory_id = product::category($value->id)->cartegory_id;
                $value->variantsDetailsByProduct = Product::variantDetailsProductByProId($value->id);
                // $value->variantsByProduct = Product::variantDetailsProductByProId($value->id);
                $value->variants = Product::productVariants($value->id);
                array_push($dataReturn,[
                    "product" =>  $value,
                ]);
       }
    //    return response()->json([
    //     'data' => $dataReturn
    //    ]);
        return response()->json([
            "data" => $dataProducts
        ]);



        // $input['limit'] = !empty($request->limit) && $request->limit > 0 ? $request->limit : 10;

        // try {
        //     $data = Product::where('is_active', $input['is_active'] ?? 1)->where(function ($query) use ($input) {
        //         if(!empty($input['code'])){
        //             $query->where('code', $input['code']);
        //         }
        //         if(!empty($input['brand_id'])){
        //             $query->where('brand_id', $input['brand_id']);
        //         }
        //         if(!empty($input['subcategory_id'])){
        //             $query->where('subcategory_id', $input['subcategory_id']);
        //         }
        //         if(!empty($input['name'])){
        //             $query->where('name', 'like', '%'.$input['name'].'%');
        //         }
        //         if(!empty($input['slug'])){
        //             $query->where('slug', 'like', '%'.$input['slug'].'%');
        //         }
        //     })->orderBy('created_at', 'desc')->paginate($input['limit']);
        // } catch(HttpException $e) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => [
        //             'error' => $e->getMessage(),
        //             'file' => $e->getFile(),
        //             'line' => $e->getLine(),
        //         ],
        //     ], $e->getStatusCode());
        // }
        // return response()->json(new ProductCollection($data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ProductCreateValidator $validator)
    {

        $input= $request->all();
        $user = $request->user();
       $collection_images = isset($request->collection_images) ? implode(',',$request->collection_images) : null;
        $validator->validate($input);
        try {
            DB::beginTransaction();
            $create = Product::create([
                'code' => 'SP'.date('YmdHis', time()),
                'meta_title'=>$input['meta_title'],
                'meta_keywords'=>$input['meta_keywords'],
                'meta_keywords'=>$input['meta_keywords'],
                'meta_description'=>$input['meta_description'],
                'name' => $input['name'],
                'slug' => !empty($input['slug']) ? Str::slug($input['slug']) : Str::slug($input['name']),
                'description' => $input['description'] ?? null,
                'url_image' => $input['url_image'],
                'collection_images' => $collection_images,
                // 'price' => $input['price'],
                // 'discount' => $input['discount'],
                'specification_infomation' => $input['specification_infomation'] ?? null,

                'subcategory_id' => $input['subcategory_id'],
                'is_active' => $input['is_active'] ?? 1,
                'created_by' => $user->id,
                'updated_by' => $user->id,
            ]);
        //    $productInfo = Product::where('code',$create['code'])->first();
            if(isset($request->variant_ids)){
                foreach($request->variant_ids as $key => $variant_id){
                   $proVariant = ProductVariantDetail::create([
                        'variant_id' => $variant_id,
                        'product_id' => $create->id,
                    ]);
                    foreach($request->colors_by_variant_id[$key] as $keyColors => $valueColor){
                        ProductVariantDetailById::create([
                            "pro_variant_id" => $proVariant->id,
                            "color_id" => $valueColor,
                            "price" => $request->prices_by_variant_id[$key][$keyColors],
                            "discount" => $request->discount_by_variant_id[$key][$keyColors]
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (HttpException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }


        return response()->json([
            'status' => 'success',
            'message' => '???? t???o s???n ph???m ['.$create->name.'] !',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $dataByproduct = Product::find($id);
            // $dataVariants = Product::productVariants($id);
            $dataTest = Product::variantDetailsProductByProId($id);

            foreach($dataTest as $item){
                $item->discount_value = number_format( round($item->price - ($item->price * $item->discount )/100,0) ) . " VN??";
                $item->discount_value_ = round($item->price - ($item->price * $item->discount )/100,0);
                $item->price_ = round($item->price - ($item->price * $item->discount )/100,0);
            }

            // $dataByproduct->variantsssssss = Product::productVariants($id);
            $dataByproduct->variants = Product::productVariants($id);

            $dataByproduct->dataVariants = $dataTest;

            // array_push($dataVariants,$dataTest);
            if(empty($dataByproduct)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kh??ng t??m th???y s???n ph???m !',
                ], 404);
            }
        } catch (HttpException $e) {
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }

        return response()->json([
            'status' => 'success',
            'data' => new ProductResource($dataByproduct),

        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, ProductUpdateValidator $validator)
    {


        // dd($request->all());
        $input = $request->all();
        $user = $request->user();
        $validator->validate($input);
        try {
            DB::beginTransaction();
            $collection_images = isset($request->collection_images) ? implode(',',$request->collection_images) : null;
            $product = Product::find($id);
            if(empty($product)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kh??ng t??m th???y s???n ph???m !'
                ], 404);
            }
            $product->meta_title =  $request->meta_title ?? $product->meta_title;
            $product->meta_keywords =  $request->meta_keywords ?? $product->meta_keywords;
            $product->meta_description =  $request->meta_description ?? $product->meta_description;
            $product->name = $request->name ?? $product->name;
            // $product->slug = Str::slug($request->slug) ?? Str::slug($request->name);
            $product->slug = Str::slug($request->name);
            $product->description = $request->description ?? $product->description;
            $product->url_image = $request->url_image ?? $product->url_image;
            $product->collection_images = $collection_images ?? null;
            $product->specification_infomation = $request->specification_infomation ?? $product->specification_infomation;
            $product->subcategory_id = $request->subcategory_id ?? $product->subcategory_id;
            // $product->price = $request->price ?? $product->price;
            // $product->discount = $request->discount ?? $product->discount;
            $product->is_active = $request->is_active ?? $product->is_active;
            $product->updated_by = $user->id;
            $product->save();


            if(isset($request->variant_ids)){
                $dataVariants = ProductVariantDetail::where('product_id', $product->id)->get();
                // dd($dataVariants);
                // array_diff
                $arrOld = [];
                foreach($dataVariants as $value){
                    // dd($value);
                        array_push($arrOld,$value->variant_id);
                }
                $compare = '';


                if(count($arrOld) > count($request->variant_ids)){
                    // x??a bi???n th???
                    // dd('x??a');
                    $compare =  array_diff($arrOld,$request->variant_ids);

                    try {
                        foreach($compare as $keyDelete => $valueDelete){
                            $dataVariantsDelete = ProductVariantDetail::where('product_id', $product->id)
                            ->where('variant_id',$valueDelete)
                            ->get();
                            foreach($dataVariantsDelete as $keyDetails => $valueDetails){

                                $dataVarianDetailsDelete = ProductVariantDetailById::where('pro_variant_id',$valueDetails->id)->delete();
                                // dd($dataVarianDetailsDelete);
                                $valueDetails->delete();
                            }
                        }
                    } catch(HttpException $e){
                        return response()->json([
                            'status' => 'error',
                            'message' => [
                                'error' => $e->getMessage(),
                                'file' => $e->getFile(),
                                'line' => $e->getLine(),
                            ],
                        ], $e->getStatusCode());
                    }

                } else if(count($arrOld) < count($request->variant_ids)){

                    $compare =  array_diff($request->variant_ids,$arrOld);
                    // th??m bi???n th???
                    foreach($compare as $key => $value){

                        $proVariant = ProductVariantDetail::create([
                            'variant_id' => $value,
                            'product_id' => $product->id,
                        ]);

                        foreach($request->colors_by_variant_id[$key] as $keyColors => $valueColor){
                                     $create = ProductVariantDetailById::create([
                                             "pro_variant_id" => $proVariant->id,
                                             "color_id" => $valueColor,
                                             "price" => $request->prices_by_variant_id[$key][$keyColors],
                                             "discount" => $request->discount_by_variant_id[$key][$keyColors],
                                         ]);

                                     }

                     }

                } else{
                    // b???ng nhau, variant kh??ng thay ?????i, ch??? c???p nh???t gi?? tr??? nh?? gi??, gi???m gi??,.... kh??ng thay ?????i m??u s???c
                    $compare =  array_diff($request->variant_ids,$arrOld);

                    foreach($request->variant_ids as $key => $valueVariant) {

                        $dataWaitUpdate = ProductVariantDetail::where('product_id',$product->id)
                                                              ->where('variant_id',$valueVariant)->first();

                        if($dataWaitUpdate){
                            $dataWaitUpdate->update([
                                "variant_id" => $valueVariant
                            ]);

                            foreach($request->colors_by_variant_id[$key] as $keyColors => $valueColor){
                                $dataVarianDetail = ProductVariantDetailById::where('pro_variant_id',$dataWaitUpdate->id)
                                ->where('color_id',$valueColor)->first();
                               if($dataVarianDetail){

                                    $upDetails =  $dataVarianDetail->update([
                                        "pro_variant_id" => $dataWaitUpdate->id,
                                        "color_id" => $valueColor,
                                        "price" => $request->prices_by_variant_id[$key][$keyColors],
                                        "discount" => $request->discount_by_variant_id[$key][$keyColors],
                                    ]);

                               } else {
                                        $create = ProductVariantDetailById::create([
                                                        "pro_variant_id" => $dataWaitUpdate->id,
                                                        "color_id" => $valueColor,
                                                        "price" => $request->prices_by_variant_id[$key][$keyColors],
                                                        "discount" => $request->discount_by_variant_id[$key][$keyColors],
                                                    ]);
                                    }

                                // dd($dataVarianDetails[2]);
                                // dd($request->colors_by_variant_id[1]);
                                // $test = $dataVarianDetail = ProductVariantDetailById::where('pro_variant_id',$valueVariant)
                                // ->where('color_id',$request->colors_by_variant_id[$keyColors][$valueColor])
                                // ->where('is_active',1)
                                // ->where('deleted_at',null)
                                // ->get();

                                // $arr = [];
                                // foreach($test as $keyDelete => $valueDelete){
                                //     array_push($arr,$valueDelete);
                                // }

                                // $compare =  array_diff($request->colors_by_variant_id[$key],$arr);

                            }
                            //  x??? l?? x??a chi ti???t c???a bi???n th???



                              // x??? l?? m???ng b???ng nh??ng c?? ph??t sinh c?? gi?? tr??? trong m???ng kh??c nhau
                        }else{
                            // $proVariant = ProductVariantDetail::create([
                            //     'variant_id' => $valueVariant,
                            //     'product_id' => $product->id,
                            // ]);
                            // if(isset($request->colors_by_variant_id) && isset($request->discount_by_variant_id) ) {
                            //     foreach($request->colors_by_variant_id[$key] as $keyColors => $valueColor){
                            //     $create = ProductVariantDetailById::create([
                            //             "pro_variant_id" => $proVariant->id,
                            //             "color_id" => $valueColor,
                            //             "price" => $request->prices_by_variant_id[$key][$keyColors],
                            //             "discount" => $request->discount_by_variant_id[$key][$keyColors],
                            //         ]);

                            //     }
                            // }
                        }
                    }
                }



            }
            //  x??a bi???n th??? chi ti???t
            if(isset($request->delete_variant_details)){
                if(!empty($request->delete_variant_details)){
                    $arrDeleteVariantDetails = $request->delete_variant_details;
                    foreach($arrDeleteVariantDetails as $key => $value){
                       $dataDelete = ProductVariantDetailById::find($value);
                       $dataDelete->delete();
                    }
                }
            }

            DB::commit();

        } catch (HttpException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }

        return response()->json([
            'status' => 'success',
            'message' => '['.$product->name . '] ???? ???????c c???p nh???t'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
       $user = $request->user();
        try {
            DB::beginTransaction();
            $dataPro = Product::find($id);
            if($dataPro){
                $dataCheck = ProductImportSlipDetail::where('product_id',$dataPro->id)->count();
                if($dataCheck > 0){
                    return response()->json([
                        'status' => 'error',
                        'message' => 'S???n ph???m n??y ???? t???n t???i trong kho, kh??ng th??? x??a !'
                    ], 401);
                }
            }

            if(empty($dataPro)){
                return response()->json([
                    'status' => 'error',
                    'message' => 'Kh??ng t??m th???y s???n ph???m !'
                ], 404);
            }
            $datavariant = ProductVariantDetail::where('product_id',$dataPro->id)->get();
            foreach($datavariant as $key => $value){
                              ProductVariantDetail::where('product_id',$dataPro->id)->where('variant_id',$value->variant_id)->update(['deleted_by' => auth('sanctum')->user()->id]);
                              ProductVariantDetail::where('product_id',$dataPro->id)->where('variant_id',$value->variant_id)->delete();
                              ProductVariantDetailById::where('pro_variant_id',$value->id)->update(['deleted_by' => auth('sanctum')->user()->id]);
                              ProductVariantDetailById::where('pro_variant_id',$value->id)->delete();
            }

            $dataPro->deleted_by = $user->id;
            $dataPro->save();
            $dataPro->delete();

            DB::commit();
        } catch(HttpException $e){
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }
        return response()->json([
            'status' => 'success',
            'message' => '???? x??a ['.$dataPro->name.'] !',
        ]);
    }

// ti??m sa??n ph????m co??n ha??ng
//  n????i nhi????u ba??ng du??ng Query Builder cho ?????? r????i ^^
    public function productByStore(Request $request){

        //  sa??n ph????m theo ??i??a chi?? ti??nh tha??nh va?? qu????n huy????n cu??a c????a ha??ng
        if(!empty($request->province_id) && !empty($request->district_id)){
            $data = DB::table('productAmountByWarehouse')
            ->select('products.name','productAmountByWarehouse.product_amount','stores.name as Showroom')
            ->leftJoin('warehouses','productAmountByWarehouse.warehouse_id','warehouses.id')
            ->leftJoin('stores','warehouses.id','stores.warehouse_id')
            ->leftJoin('products','productAmountByWarehouse.product_id','products.id')
            ->where('products.id',$request->product_id)
            ->where('stores.province_id',$request->province_id)
            ->where('stores.district_id',$request->district_id)
            ->where('products.is_active',1)
            ->get();
            return response()->json([
                'data' => $data
            ],200);

        }
        //  sa??m ph????m  theo ??i??a chi?? ti??nh tha??nh cu????a ha??ng
        if(!empty($request->province_id)) {
            $data = DB::table('productAmountByWarehouse')
            ->select('products.name','productAmountByWarehouse.product_amount','stores.name as Showroom')
            ->leftJoin('warehouses','productAmountByWarehouse.warehouse_id','warehouses.id')
            ->leftJoin('stores','warehouses.id','stores.warehouse_id')
            ->leftJoin('products','productAmountByWarehouse.product_id','products.id')
            ->where('products.id',$request->product_id)
            ->where('stores.province_id',$request->province_id)
            ->where('products.is_active',1)
            ->get();
            return response()->json([
                'data' => $data
            ],200);
        }


        //  n????i nhi????u ba??ng du??ng Query Builder cho ?????? r????i ^^
        // ti??m 1 sa??n ph????m (id sa??n ph????m) co??n ha??ng n????u kh??ng truy????n ti??nh tha??nh ph???? va?? qu????n huy????n
        $data = DB::table('productAmountByWarehouse')
        ->select('products.name','productAmountByWarehouse.product_amount','stores.name as Showroom')
        ->leftJoin('warehouses','productAmountByWarehouse.warehouse_id','warehouses.id')
        ->leftJoin('stores','warehouses.id','stores.warehouse_id')
        ->leftJoin('products','productAmountByWarehouse.product_id','products.id')
        ->where('products.id',$request->product_id)
        ->where('products.is_active',1)
        ->get();
        return response()->json([
            'data' => $data
        ],200);
    }

    // get province store (all)
        public function getProvincesByWarehouse(Request $request){

            if(isset($request->province_id) && isset($request->district_id) && isset($request->ward_id)){
                $data = DB::table('stores')
                ->select('stores.name as store_name','provinces.id as province_id','provinces.name as province_name','districts.id as district_id','districts.name as district_name','wards.id as ward_id','wards.name as ward_name')
                ->join('warehouses','stores.warehouse_id','warehouses.id')
                ->join('productAmountByWarehouse','warehouses.id','productAmountByWarehouse.warehouse_id')
                ->join('products','productAmountByWarehouse.product_id','products.id')
                ->join('provinces','stores.province_id','provinces.id')
                ->join('districts','stores.district_id','districts.id')
                ->join('wards','stores.ward_id','wards.id')
                ->where('products.is_active',1)
                ->where('products.id',$request->product_id)
                ->where('productAmountByWarehouse.pro_variant_id',$request->variant_id)
                ->where('productAmountByWarehouse.product_amount','>',0)
                ->where('stores.province_id',$request->province_id)
                ->where('stores.district_id',$request->district_id)
                ->where('stores.ward_id',$request->ward_id)
                ->get();
                return response()->json([
                    'status' => 'success',
                    'data' => $data
                ]);
            }

                if(isset($request->province_id ) && isset($request->district_id)){
                    $data = DB::table('stores')
                    ->select('stores.name as store_name','provinces.id as province_id','provinces.name as province_name','districts.id as district_id','districts.name as district_name','wards.id as ward_id','wards.name as ward_name')
                    ->join('warehouses','stores.warehouse_id','warehouses.id')
                    ->join('productAmountByWarehouse','warehouses.id','productAmountByWarehouse.warehouse_id')
                    ->join('products','productAmountByWarehouse.product_id','products.id')
                    ->join('provinces','stores.province_id','provinces.id')
                    ->join('districts','stores.district_id','districts.id')
                    ->join('wards','stores.ward_id','wards.id')
                    ->where('products.is_active',1)
                    ->where('products.id',$request->product_id)
                    ->where('productAmountByWarehouse.pro_variant_id',$request->variant_id)
                    ->where('productAmountByWarehouse.product_amount','>',0)
                    ->where('stores.province_id',$request->province_id)
                    ->where('stores.district_id',$request->district_id)
                    ->get();
                    return response()->json([
                        'status' => 'success',
                        'data' => $data
                    ]);
                }

                if(isset($request->province_id)){
                    $data = DB::table('stores')
                    ->select('stores.name as store_name','provinces.id as province_id','provinces.name as province_name','districts.id as district_id','districts.name as district_name','wards.id as ward_id','wards.name as ward_name')
                    ->join('warehouses','stores.warehouse_id','warehouses.id')
                    ->join('productAmountByWarehouse','warehouses.id','productAmountByWarehouse.warehouse_id')
                    ->join('products','productAmountByWarehouse.product_id','products.id')
                    ->join('provinces','stores.province_id','provinces.id')
                    ->join('districts','stores.district_id','districts.id')
                    ->join('wards','stores.ward_id','wards.id')
                    ->where('products.is_active',1)
                    ->where('products.id',$request->product_id)
                    ->where('productAmountByWarehouse.pro_variant_id',$request->variant_id)
                    ->where('productAmountByWarehouse.product_amount','>',0)
                    ->where('stores.province_id',$request->province_id)
                    ->get();
                    return response()->json([
                        'status' => 'success',
                        'data' => $data
                    ]);
                }

                $data = DB::table('stores')
                ->select('stores.name as store_name','provinces.id as province_id','provinces.name as province_name','districts.id as district_id','districts.name as district_name','wards.id as ward_id','wards.name as ward_name')
                ->join('warehouses','stores.warehouse_id','warehouses.id')
                ->join('productAmountByWarehouse','warehouses.id','productAmountByWarehouse.warehouse_id')
                ->join('products','productAmountByWarehouse.product_id','products.id')
                ->join('provinces','stores.province_id','provinces.id')
                ->join('districts','stores.district_id','districts.id')
                ->join('wards','stores.ward_id','wards.id')
                ->where('products.is_active',1)
                ->where('products.id',$request->product_id)
                ->where('productAmountByWarehouse.pro_variant_id',$request->variant_id)
                ->where('productAmountByWarehouse.product_amount','>',0)
                ->get();
                return response()->json([
                    'status' => 'success',
                    'data' => $data
                ]);
        }

    //  product by subcategory id

    public function producstBySubcategoryId($SubId) {
        //  $product = new Product();

        $dataProducts = Product::productsBySubCate($SubId);

        $dataReturn = [];
        foreach($dataProducts as $key => $value){

                 $value->variantsDetailsByProduct = Product::variantDetailsProductByProId($value->product_id);

                 // $value->variantsByProduct = Product::variantDetailsProductByProId($value->id);

                 $value->variants = Product::productVariants($value->product_id);
                 array_push($dataReturn,[
                     "product" =>  $value,
                 ]);
        }
        return response()->json([
            "data" => $dataProducts
        ]);
    }

    public function productsByCategoryId($category_id){
        $product = new Product();

        $dataProducts = $product->productByCategory($category_id);
        $dataReturn = [];
        foreach($dataProducts as $key => $value){

                 $value->variantsDetailsByProduct = Product::variantDetailsProductByProId($value->id);

                 // $value->variantsByProduct = Product::variantDetailsProductByProId($value->id);

                 $value->variants = Product::productVariants($value->id);
                 array_push($dataReturn,[
                     "product" =>  $value,
                 ]);
        }
        return response()->json([
            'status' => 'success',
            'data' => $dataProducts
        ],200);
    }

    // delete variants of a product

    public function deleteVariantOfproduct($variant_id, Request $request){
        try {
            DB::beginTransaction();
            ProductVariantDetail::where('variant_id',$variant_id)->where('product_id',$request->product_id)->update(["deleted_by" => auth('sanctum')->user()->id]);
            ProductVariantDetail::where('variant_id',$variant_id)->where('product_id',$request->product_id)->delete();
            DB::commit();
        } catch(HttpException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }
        return response()->json([
            'status' => 'success',
            'message' => '???? x??a bi???n th??? '. $variant_id . ' !'
        ]);
    }

    public function getVariantById($variant_id, Request $request){

        try {
            DB::beginTransaction();

            DB::commit();
        } catch(HttpException $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
            ], $e->getStatusCode());
        }

    }

    // t??m s???n ph???m

    public function search(Request $req){

        $product = new Product();
        $data = $product-> productByKeywords($req->keywords);

        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function getAllSubcate(){
        $data = Product::AllCategory();

            foreach($data as $key => $value){

                $value->products = Product::AllSubCategoryByCategoryId($value->category_id);
                foreach($value->products as $key2 => $value2){

                    $value2->variantsDetailsByProduct = Product::variantDetailsProductByProId($value2->id);
                    $value2->variants = Product::productVariants($value2->id);

                }
            }

            foreach( $data as $key => $value){
                if(count($value->products) > 0){
                    $dataReturn['data'][] = $data[$key];
                }
            }

        return response()->json([
            'status' => 'success',
            'data' => $dataReturn['data'],
        ],200);
    }



    public static function getproductsImportSlip(){
        $data = Product::all();
        foreach($data as $key => $value){

            $value->proVariant = Product::productVariants($value->id);
            // dd( $value->proVariant);
            foreach($value->proVariant as $key2 => $value2){
                $value2->productVariantDetails = Product::variantDetailsByProvariant($value2->productVariantID);
                foreach($value2->productVariantDetails as $key => $value){
                    $value->color = Product::getColorById($value->color_id);
                }
            }
        }
        return response()->json([
            'data' => $data
        ],200);
    }

    public function productsHaveCommentAll(Request $request){
        $input = $request->all();
        $data = Product::where(function ($query) use ($input) {
            if(!empty($input['name'])){
                $query->where('name', 'like', '%'.$input['name'].'%');
            }
            if(!empty($input['slug'])){
                $query->where('slug', 'like', '%'.$input['slug'].'%');
            }

       })->paginate(9);
        $data = new ProductsHaveComemntCollection($data);
        return response()->json(
             $data,200);

    }


    public function productsAllForClient(Request $request){
        // $input = $request->all();
        $dataProducts = Product::where('is_active',$input['is_active'] ?? 1)->where('deleted_at',null)->get();

        $dataReturn = [];
        foreach($dataProducts as $key => $value){

                 $value ->create_by_name = product::getNameCreated($value->created_by)->created_by_name;
                 $value->collection_images = explode(',',$value->collection_images);
                 $value-> cartegory_id = product::category($value->id)->cartegory_id;
                 $value->variantsDetailsByProduct = Product::variantDetailsProductByProId($value->id);
                foreach($value->variantsDetailsByProduct as $item) {
                    $item->price_ = number_format(round( $item->price,0));
                    $item->discount_value =  number_format( round($item->price - ($item->price * $item->discount )/100,0) ) . " VN??";
                    $item->discount_value_ = round($item->price - ($item->price * $item->discount )/100,0);
                }
                 // $value->variantsByProduct = Product::variantDetailsProductByProId($value->id);
                 $value->variants = Product::productVariants($value->id);
                 array_push($dataReturn,[
                     "product" =>  $value,
                 ]);
        }
     //    return response()->json([
     //     'data' => $dataReturn
     //    ]);
         return response()->json([
             "data" => $dataProducts
         ]);


    }

    public function getVariantByVarriantID($id){
        $data = DB::table('variants')
        ->where('is_active',1)
        ->where('deleted_at',null)
        ->where('variants.id',$id)->first();
        return  $data;
    }


    public function checkProductsAmount(Request $request){
        $input = $request->all();
        $data = DB::table('productAmountByWarehouse')
        ->select('products.name','productAmountByWarehouse.product_amount','stores.name as Showroom','productAmountByWarehouse.pro_variant_id as variant_id')
        ->leftJoin('warehouses','productAmountByWarehouse.warehouse_id','warehouses.id')
        ->leftJoin('stores','warehouses.id','stores.warehouse_id')
        ->leftJoin('products','productAmountByWarehouse.product_id','products.id')
        // ->where('products.id',$request->product_id)
        ->where('productAmountByWarehouse.warehouse_id',$request->warehouse_id)
        ->where(function ($query) use ($input) {
            if(!empty($input['name'])){
                $query->where('products.name', 'like', '%'.$input['name'].'%');
            }
            if(!empty($input['slug'])){
                $query->where('products.slug', 'like', '%'.$input['slug'].'%');
            }
         })
        ->where('products.is_active',1)
        ->paginate($input['paginate'] ?? 9);

        foreach($data as $value){
            $value->variant_id = $this->getVariantByVarriantID($value->variant_id)->id;
            $value->variant_name = $this->getVariantByVarriantID($value->variant_id)->variant_name;
        }

        return response()->json([
            'data' => $data
        ],200);
    }



}
