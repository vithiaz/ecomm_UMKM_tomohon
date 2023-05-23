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

final class PaymentToUmkmTable extends PowerGridComponent
{
    use ActionButton;

    // Binding Variable
    public string $status;

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */

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
        $UserOrderModel = UserOrder::with('success_transaction')->find($userOrderId);
        if ($UserOrderModel->success_transaction) {
            $UserOrderModel->success_transaction->seller_payment_status = 'settlement';
            $UserOrderModel->success_transaction->save();

            $msg = ['success' => 'Data Diupdate'];
            $this->dispatchBrowserEvent('display-message', $msg);    
            $this->fillData();
        }
    }

    public function getBankAccount($umkm_id): void
    {
        $UmkmId = $umkm_id[0];
        $UmkmModel = Umkm::with(['user', 'bank_accounts'])->find($UmkmId);
        $this->dispatchBrowserEvent('toggleBankAccountModal', ['data' => $UmkmModel->toArray()]);
    }


    public function setUp(): array
    {
        // $this->showCheckBox();

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
            'umkm',
            'success_transaction',
        ])->whereHas('success_transaction', function ($q) {
            return $q->where('seller_payment_status', '=', $this->status);
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
        return [];
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
            ->addColumn('umkm_id')
            ->addColumn('umkm_name', function (UserOrder $model) {
                return $model->umkm->name;
            })
            // ->addColumn('name_lower', fn (UserOrder $model) => strtolower(e($model->name)))
            ->addColumn('payment_amount')
            ->addColumn('payment_amount_formatted', function (UserOrder $model) {
                return format_rupiah($model->payment_amount);
            })
            ->addColumn('updated_at')
            ->addColumn('updated_at_formatted', fn (UserOrder $model) => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'));
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
            Column::make('Tanggal', 'updated_at')
            ->hidden(),

            Column::make('Tanggal', 'updated_at_formatted', 'updated_at')
                ->searchable()
                ->sortable(),

            Column::make('ID', 'id')
                ->searchable()
                ->sortable(),

            Column::make('Umkm', 'umkm_id')
                ->searchable()
                ->hidden(),

            Column::make('Nama UMKM', 'umkm_name')
                ->searchable(),

            Column::make('Jumlah Pembayaran', 'payment_amount')
                ->hidden(),

            Column::make('Jumlah Pembayaran', 'payment_amount_formatted', 'payment_amount')
                ->sortable(),
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
                    ->emit('getBankAccount', ['umkm_id']),
                    // ->dispatch('toggleBankAccountModal', []),
                
                Button::add('payed', 'Dibayarkan')
                    ->caption('Dibayarkan')
                    ->class('btn btn-default-orange')
                    ->emit('paymentPayed', ['id'])
            ];
        }
        return [];
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
