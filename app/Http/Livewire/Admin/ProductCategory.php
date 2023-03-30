<?php

namespace App\Http\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Models\ProductCategory as ProductCategoryModel;

class ProductCategory extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    // Binding Variable
    public $category_name;
    public $edit_state_id;

    protected $rules = [
        'category_name' => 'required|string',
    ];

    public function mount() {
        $this->category_name = '';
        $this->edit_state_id = null;
    }

    public function render()
    {
        // $categories = ProductCategoryModel::with([
        //     'product',
        // ])->paginate(12);

        return view('livewire.admin.product-category', [
            // 'categories' => $categories,
        ])->layout('layouts.admin_app');
    }

    public function store_category() {
        $this->validate();

        $category = new ProductCategoryModel;
        $category->name = ucwords($this->category_name);
        $category->name_slug = Str::slug($this->category_name);
        $category->save();

        return redirect(request()->header('Referer'))->with('message', 'Pendaftaran Berhasil');
    }

    public function set_edit_state($id) {
        $this->edit_state_id = $id;
    }

}
