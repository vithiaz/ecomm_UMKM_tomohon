<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Traits\{ActionButton, WithExport};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class ProductTable extends PowerGridComponent
{
    use ActionButton;
    use WithExport;

    // Binding Variable
    public string $status;

    public function setUp(): array
    {
        // $this->showCheckBox();

        return [
            Exportable::make('export')
                ->striped()
                ->type(Exportable::TYPE_XLS, Exportable::TYPE_CSV),
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    public function datasource(): Builder
    {
        return Product::query()->with([
            'umkm',
            'user',
            'categories',
        ])->where('status', '=', $this->status);
    }


    public function relationSearch(): array
    {
        return [];
    }


    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
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
        });

    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->searchable(),

            Column::make('Name', 'name')
                    ->sortable()
                    ->searchable(),

            Column::make('Harga', 'price_formatted', 'price')
                    ->sortable(),

            Column::make('Diskon', 'discount_formatted', 'discount')
                ->sortable(),

            Column::make('Umkm', 'umkm_name')
                ->searchable(),

            Column::make('Kategori', 'categories')
                ->searchable(),
                
            Column::make('Diubah', 'updated_at_formatted', 'updated_at')
                ->sortable()
                ->searchable(),

        ];
    }

    public function filters(): array
    {
        return [
            // Filter::inputText('name')->operators(['contains']),
            // Filter::inputText('name_slug')->operators(['contains']),
            // Filter::inputText('status')->operators(['contains']),
            // Filter::datetimepicker('updated_at', 'updated_at_formatted'),
        ];
    }
    
    public function actions(): array
    {
       return [
            Button::make('tinjau', 'tinjau')
                ->class('btn btn-default-orange')
                ->route('product.edit', ['product' => 'id'])
                ->method('get')
                ->target('_self')
                ->route('admin.product-review', [
                    'product_id' => 'id',
                    'name_slug' => 'name_slug',
                ]),
        ];
    }
    

    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid Product Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($product) => $product->id === 1)
                ->hide(),
        ];
    }
    */
}
