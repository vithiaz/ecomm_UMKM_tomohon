<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

class ProductTable extends PowerGridComponent
{
    use ActionButton;

    // Binding Variable
    public string $status;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public function setUp()
    {
        $this->showPerPage()
            ->showSearchInput();
    }

    public function dataSource(): array
    {
        $model = Product::query()->with([
            'umkm',
            'user',
            'categories',
        ])->where('status', '=', $this->status)->get();
        
        return PowerGrid::eloquent($model)
            ->addColumn('id')
            ->addColumn('name')
            ->addColumn('name_slug')
            ->addColumn('price')
            ->addColumn('price_formatted', function(Product $model) {
                return format_rupiah($model->price);
            })
            ->addColumn('discount')
            ->addColumn('discount_formatted', function(Product $model) {
                return $model->discount . ' %';
            })
            ->addColumn('umkm_name', function(Product $model) {
                return $model->umkm->name;
            })
            ->addColumn('categories', function(Product $model) {
                $categories_list = '';
                foreach ($model->categories as $category) {
                    $categories_list = $category->name . ', ' . $categories_list;
                }
                return $categories_list;
                // return $model->product_categories->first_name . ($model->user->last_name ? ' '.$model->user->last_name : '');
            })
            ->addColumn('updated_at')
            ->addColumn('updated_at_formatted', function(Product $model) {
                return Carbon::parse($model->updated_at)->format('d/m/Y H:i:s');
            })
            ->make();
    }

    public function columns(): array
    {
        return [
            Column::add()
                ->title(__('ID'))
                ->field('id')
                ->searchable(),

            Column::add()
                ->title(__('Nama'))
                ->field('name')
                ->searchable()
                ->sortable(),

            Column::add()
                ->title(__('Harga'))
                ->field('price')
                ->sortable()
                ->hidden(),
            
            Column::add()
                ->title(__('Harga'))
                ->field('price_formatted')
                ->sortable(),
            
            Column::add()
                ->title(__('Diskon'))
                ->field('discount')
                ->sortable()
                ->hidden(),

            Column::add()
                ->title(__('Diskon'))
                ->field('discount_formatted')
                ->sortable(),

            Column::add()
                ->title(__('Umkm'))
                ->field('umkm_name')
                ->searchable(),
            
            Column::add()
                ->title(__('Kategori'))
                ->field('categories')
                // ->makeInputText('categories')
                ->searchable(),

            // Column::add()
            //     ->title(__('Penjual'))
            //     ->field('user_name')
            //     ->searchable(),

            Column::add()
                ->title(__('Diubah'))
                ->field('updated_at')
                ->sortable()
                ->hidden(),

            Column::add()
                ->title(__('Diubah'))
                ->field('updated_at_formatted')
                ->makeInputDatePicker('updated_at')
                ->sortable()
                ->searchable(),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable this section only when you have defined routes for these actions.
    |
    */

    public function actions(): array
    {
       return [
           Button::add('tinjau')
               ->caption(__('tinjau'))
               ->class('btn btn-default-orange')
               ->method('get')
               ->route('admin.product-review', [
                'product_id' => 'id',
                'name_slug' => 'name_slug',
               ]),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Edit Method
    |--------------------------------------------------------------------------
    | Enable this section to use editOnClick() or toggleable() methods
    |
    */

    /*
    public function update(array $data ): bool
    {
       try {
           $updated = Product::query()->find($data['id'])->update([
                $data['field'] => $data['value']
           ]);
       } catch (QueryException $exception) {
           $updated = false;
       }
       return $updated;
    }

    public function updateMessages(string $status, string $field = '_default_message'): string
    {
        $updateMessages = [
            'success'   => [
                '_default_message' => __('Data has been updated successfully!'),
                //'custom_field' => __('Custom Field updated successfully!'),
            ],
            "error" => [
                '_default_message' => __('Error updating the data.'),
                //'custom_field' => __('Error updating custom field.'),
            ]
        ];

        return ($updateMessages[$status][$field] ?? $updateMessages[$status]['_default_message']);
    }
    */


}
