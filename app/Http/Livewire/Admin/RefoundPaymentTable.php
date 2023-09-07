<?php

namespace App\Http\Livewire\Admin;

use App\Models\Umkm;
use App\Models\UserOrder;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class RefoundPaymentTable extends PowerGridComponent
{
    use ActionButton;

    // Binding Variable
    public string $status;


    protected function getListeners()
    {
        return array_merge(
            parent::getListeners(), [
                'paymentPayed',
                'getBankAccount',
            ]);
    }

    public function paymentPayed($id): void
    {
        $userOrderId = $id[0];
        $UserOrderModel = UserOrder::with('refound_order')->find($userOrderId);
        if ($UserOrderModel->refound_order) {
            $UserOrderModel->refound_order->payment_status = 'settlement';
            $UserOrderModel->refound_order->save();

            $msg = ['success' => 'Data Diupdate'];
            $this->dispatchBrowserEvent('display-message', $msg);    
            $this->fillData();
        }
    }

    public function getBankAccount($id): void
    {
        $userOrderId = $id[0];
        
        $UserOrderModel = UserOrder::with(['refound_order', 'user'])->find($userOrderId);

        $userDetail = $UserOrderModel->user->toArray();
        $refoundDetail = $UserOrderModel->refound_order->toArray();
        $this->dispatchBrowserEvent('toggleRefoundAccountModal', [
            'userDetail' => $userDetail,
            'refoundDetail' => $refoundDetail,
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp(): array
    {

        return [
            Header::make()->showSearchInput(),
            Footer::make()
                ->showPerPage()
                ->showRecordCount(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */

    /**
     * PowerGrid datasource.
     *
     * @return Builder<\App\Models\UserOrder>
     */
    public function datasource(): Builder
    {
        return UserOrder::query()->with([
            'user',
            'refound_order',
        ])->whereHas('refound_order', function ($q) {
            return $q->where('payment_status', '=', $this->status);
        });
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */

    /**
     * Relationship search.
     *
     * @return array<string, array<int, string>>
     */
    public function relationSearch(): array
    {
        return [
            'user' => [
                'username',
                'first_name',
                'last_name',
                'email',
            ]
        ];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    | ❗ IMPORTANT: When using closures, you must escape any value coming from
    |    the database using the `e()` Laravel Helper function.
    |
    */
    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('buyer_username', function (UserOrder $model) {
                return $model->user->username;
            })
            ->addColumn('buyer_fullname', function (UserOrder $model) {
                return $model->user->first_name . ' ' . $model->user->last_name;
            })
            ->addColumn('buyer_email', function (UserOrder $model) {
                return $model->user->email;
            })
            ->addColumn('payment_amount')
            ->addColumn('payment_amount_formatted', function (UserOrder $model) {
                return format_rupiah($model->payment_amount);
            })
            ->addColumn('created_at')
            ->addColumn('created_at_formatted', fn (UserOrder $model) => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'));
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */

    /**
     * PowerGrid Columns.
     *
     * @return array<int, Column>
     */
    public function columns(): array
    {
        return [
            Column::make('Created at', 'created_at')
                ->hidden(),

            Column::make('Tanggal', 'created_at_formatted', 'created_at')
                ->sortable()
                ->searchable(),
            
            Column::make('Order ID', 'id')
                ->searchable(),

            Column::make('Jumlah Refound', 'payment_amount_formatted', 'payment_amount')
                ->searchable()
                ->sortable(),

            Column::make('pembeli', 'buyer_fullname')
                ->searchable(),

            Column::make('username', 'buyer_username')
                ->searchable(),

            Column::make('email', 'buyer_email')
                ->searchable()

        ];
    }

    /**
     * PowerGrid Filters.
     *
     * @return array<int, Filter>
     */
    public function filters(): array
    {
        return [
            Filter::inputText('name'),
            Filter::datepicker('created_at_formatted', 'created_at'),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable the method below only if the Routes below are defined in your app.
    |
    */

    /**
     * PowerGrid UserOrder Action Buttons.
     *
     * @return array<int, Button>
     */

    public function actions(): array
    {
        if ($this->status == 'pending') {
            return [
                Button::add('paying_acc', 'Akun Pembayaran')
                    ->caption('Lihat Akun')
                    ->class('btn btn-default-dark')
                    ->emit('getBankAccount', ['id']),
                
                Button::add('payed', 'Dibayarkan')
                    ->caption('Dibayarkan')
                    ->class('btn btn-default-orange')
                    ->emit('paymentPayed', ['id'])
            ];
        }
        return [
            Button::add('paying_acc', 'Akun Pembayaran')
                ->caption('Lihat Akun')
                ->class('btn btn-default-dark')
                ->emit('getBankAccount', ['id'])
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
     * PowerGrid UserOrder Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($user-order) => $user-order->id === 1)
                ->hide(),
        ];
    }
    */
}
