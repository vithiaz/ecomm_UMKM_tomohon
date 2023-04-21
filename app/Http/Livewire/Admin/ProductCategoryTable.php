<?php

namespace App\Http\Livewire\Admin;

use \App\Models\ProductCategory;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class ProductCategoryTable extends PowerGridComponent
{
    use ActionButton;

    public $name;

    // public ?string $name = null;

    // protected array $rules = [
    //     'name' => ['required'],
    // ];

    // public string $name;

    public function setUp(): array
    {

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
        return ProductCategory::query()->with('product');
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
            ->addColumn('products_count', function(ProductCategory $model) {
                return $model->product->count();
            });



        //     // ->addColumn('id')
        //     // ->addColumn('name')
        //     // ->addColumn('name_lower', fn (ProductCategory $model) => strtolower(e($model->name)))
        //     // ->addColumn('created_at')
        //     // ->addColumn('created_at_formatted', fn (ProductCategory $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->searchable()
                ->sortable(),

                Column::make('Nama Kategori', 'name')
                ->field('name')
                ->editOnClick(true)
                ->searchable(),
                
            Column::make('Jumlah Produk', 'products_count')
                ->sortable(),
    
            // Column::add()
            //     ->title(__('Nama Kategori'))
            //     ->field('name')
            //     ->editOnClick(true)
            //     ->searchable(),

            // Column::make('Jumlah Produk', 'products_count')    
            //     ->sortable()
    
            // Column::make('Created at', 'created_at')
            //     ->hidden(),

            // Column::make('Created at', 'created_at_formatted', 'created_at')
            //     ->searchable()
        ];
    }

    public function filters(): array
    {
        return [
            // Filter::inputText('name'),
            // Filter::datepicker('created_at_formatted', 'created_at'),
        ];
    }

    public function onUpdatedEditable($id, $field, $value): void
    {
        try {
            $updated = ProductCategory::query()->find($id)->update([
                $field => $value
            ]);
        } catch (QueryException $exception) {
            $updated = false;
        }

        if ($updated) {
            $this->fillData();
        }
    }


    public function actions(): array
    {
        return [
            Button::add('deleteCat')
                ->caption(__('hapus'))
                ->class('btn btn-default-red')
                ->method('delete')
                ->target('_self')
                ->route('admin.product-categories.delete', ['id' => 'id']),
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
     * PowerGrid ProductCategory Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($product-category) => $product-category->id === 1)
                ->hide(),
        ];
    }
    */
}
