<?php

namespace App\Http\Livewire\Admin;

use \App\Models\ProductCategory;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Header;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;


class ProductCategoryTable extends PowerGridComponent
{
    use ActionButton;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function setUp()
    {
        $this->showPerPage()
            // ->showCheckBox()
            ->showSearchInput();
    }

    public function dataSource(): array
    {
        $model = ProductCategory::query()->with('product')->get();
        return PowerGrid::eloquent($model)
            ->addColumn('id')
            ->addColumn('name')
            ->addColumn('products_count', function(ProductCategory $model) {
                return $model->product->count();
            })
            // ->addColumn('created_at')
            // ->addColumn('created_at_formatted', function(ProductCategory $model) {
            //     return Carbon::parse($model->created_at)->format('d/m/Y H:i:s');
            // })
            ->make();
    }

    public function columns(): array
    {
        return [
            Column::add()
                ->title(__('ID'))
                ->field('id')
                ->searchable()
                ->sortable(),

            Column::add()
                ->title(__('Jumlah Produk'))
                ->field('products_count')
                ->sortable(),

            Column::add()
                ->title(__('Nama Kategori'))
                ->field('name')
                ->editOnClick(true)
                ->searchable()

            // Column::add()
            //     ->title(__('Created at'))
            //     ->field('created_at')
            //     ->hidden(),

            // Column::add()
            //     ->title(__('Created at'))
            //     ->field('created_at_formatted')
            //     ->makeInputDatePicker('created_at')
            //     ->searchable()
        ];
    }
    
    public function actions(): array
    {
       return [
           Button::add('deleteCat')
                ->caption(__('hapus'))
                ->class('btn btn-default-red')
                ->method('delete')
                ->route('admin.product-categories.delete', ['id' => 'id']),
        ];
    }
    

    public function update(array $data ): bool
    {
        try {
            $updated = ProductCategory::query()->find($data['id'])->update([
                $data['field'] => $data['value']
            ]);
        } catch (QueryException $exception) {
            $updated = false;
        }

        if ($updated) {
            $this->dispatchBrowserEvent('reload_page');
        }

        return $updated;
    }

    public function updateMessages(string $status, string $field = '_default_message'): string
    {
        $updateMessages = [
            'success'   => [
                '_default_message' => __('Data berhasil diupdate!'),
                //'custom_field' => __('Custom Field updated successfully!'),
            ],
            "error" => [
                '_default_message' => __('Gagal melakukan update data.'),
                //'custom_field' => __('Error updating custom field.'),
            ]
        ];

        return ($updateMessages[$status][$field] ?? $updateMessages[$status]['_default_message']);

    }
    


}
