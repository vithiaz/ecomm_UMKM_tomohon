<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\ProductCategory as ProductCategoryModel;
use App\Http\Controllers\Controller;

class ProductCategory extends Controller
{
    public function delete($id) {
        $category = ProductCategoryModel::find($id);
        if ($category) {
            if($category->delete()) {
                return redirect()->back()->with('message', 'Kategori dihapus');
            }
        }
        return redirect()->back()->with('message', 'Terjadi kesalahan');
    }
}
