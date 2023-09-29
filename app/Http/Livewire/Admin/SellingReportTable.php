<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use App\Models\UserOrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class SellingReportTable extends PowerGridComponent
{
    use ActionButton;

    public function setUp(): array
    {
        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }


    public function datasource(): Builder
    {
        // dd(
        //     Product::join('user_order_items', 'user_order_items.product_id', '=', 'products.id')
        //     ->join('user_orders', 'user_orders.id', '=', 'user_order_items.order_id')
        //     ->join('success_transactions', 'success_transactions.order_id', '=', 'user_orders.id')
        //     ->get()
        // );


        dd(
            Product::with([
                'umkm',
                'order_item.order_success',
            ])

            // ->withCount('order_item as order_success_count')
            // ->join('user_order_items', 'user_order_items.product_id', '=', 'products.id')
            // ->join('user_orders', 'user_orders.id', '=', 'user_order_items.order_id')
            // ->join('success_transactions', 'success_transactions.order_id', '=', 'user_orders.id')
            // ->groupBy('products.id') // Group by product to count success_transactions records per product
            // ->orderByDesc(\DB::raw('COUNT(user_order_items.id)')) // Order by the count of success_transactions records
            // ->select('products.*') // Select the product columns
            ->get()
        );


        dd(
            Product::with([
                'umkm',
                'order_item.order_success',
            ])
            // ->whereHas('order_item.order_success')
            ->withCount('order_item.order_success as order_success_count')
            // ->orderByDesc('order_success_count')
            ->get()
        );


        return Product::with([
            'umkm',
            'order_item.order_success',
        ]);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('status')
            ->addColumn('name')
            ->addColumn('umkm_name', fn (Product $model) => $model->umkm->name)
            ->addColumn('order_success', fn (Product $model) => $this->count_order_success($model->order_item) )
            // ->addColumn('order_success', fn (Product $model) => $this->count_order_success($model->order_item) )
            ->addColumn('created_at')
            ->addColumn('created_at_formatted', fn (Product $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    private function count_order_success($ModelCollection) {
        $order_success = 0;
        
        foreach ($ModelCollection as $order_item) {
            if ($order_item->order_success) {
                $order_success += $order_item->order_success->count();
            }
        }

        // foreach ($ModelCollection as $order_item) {
        //     $order_item_id = UserOrderItem::withCount('order_success')->find($order_item->id);
        //     if ($order_item_id) {
        //         $order_success += $order_item_id->order_success_count;
        //     }
        // }

        return $order_success;
    }


    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->searchable(),
                // ->hidden(),

            Column::make('Name', 'name')
                ->searchable()
                ->sortable(),

            Column::make('Order', 'order_success')
                ->sortable(),

            Column::make('Created at', 'created_at')
                ->hidden(),

            Column::make('Created at', 'created_at_formatted', 'created_at')
                ->searchable()
        ];
    }


    public function filters(): array
    {
        return [
            // Filter::inputText('name'),
            // Filter::datepicker('created_at_formatted', 'created_at'),
        ];
    }


    /*
    public function actions(): array
    {
       return [
           Button::make('edit', 'Edit')
               ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2.5 m-1 rounded text-sm')
               ->route('user-order-item.edit', ['user-order-item' => 'id']),

           Button::make('destroy', 'Delete')
               ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
               ->route('user-order-item.destroy', ['user-order-item' => 'id'])
               ->method('delete')
        ];
    }
    */

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
                ->when(fn($user-order-item) => $user-order-item->id === 1)
                ->hide(),
        ];
    }
    */
}
