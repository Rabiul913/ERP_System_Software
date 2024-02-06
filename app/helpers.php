<?php

use Illuminate\Support\Facades\File;
use Modules\SupplyChain\Entities\Stock;
use Modules\SupplyChain\Entities\Product;

if (!function_exists('uploadImage')) {

    function uploadImage($imageName, $storeLocation, $oldImageName = null)
    {
        $path = public_path("/$storeLocation/");
        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0777, true, true);
        }

        if ($oldImageName) {
            File::delete($path . $oldImageName);
        }

        $image_name = md5(time() . '_' . $imageName) . '.' . $imageName->getClientOriginalExtension();
        $imageName->move($path, $image_name);
        return $image_name;
    }
}

function deleteImageFile($image_name, $folderName)
{
    $path = public_path("$folderName/" . $image_name);
    if ($image_name) {
        $fileChk = File::exists($path);
        if ($fileChk) {
            File::delete($path);
        }
    }
}

function updateStock($product_id, $type = null, $type_id = 0, $qty = 0, $unit_price = 0, $batch_id = 0, $warehouse_id = 0)
{
    $stock = new Stock();
    $stock->product_id = $product_id;
    $stock->type = $type;
    $stock->type_id = $type_id;
    $stock->qty = $qty;
    $stock->unit_price = $unit_price;
    $stock->batch_id = $batch_id;
    $stock->warehouse_id = $warehouse_id;
    $stock->save();

    return $stock;
}
