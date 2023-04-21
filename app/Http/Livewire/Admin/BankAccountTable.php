<?php

namespace App\Http\Livewire\Admin;

use App\Models\BankAccount;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class BankAccountTable extends PowerGridComponent
{
    use ActionButton;

    // Binding Variable
    public string $status;

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
        return BankAccount::query()
                ->with('user')
                ->where('status', '=', $this->status);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('user_id', function(BankAccount $model) {
                return $model->user->id;
            })
            ->addColumn('bank_name')
            ->addColumn('account_number')
            ->addColumn('account_name')
            ->addColumn('updated_at')
            ->addColumn('updated_at_formatted', fn (BankAccount $model) => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'));
    }


    public function columns(): array
    {
        return [
            Column::make('ID', 'id')
                ->hidden(),

            Column::make('User ID', 'user_id')
                ->searchable(),

            Column::make('Bank', 'bank_name')
                ->searchable(),

            Column::make('No. Rekening', 'account_number')
                ->searchable(),

            Column::make('Atas Nama', 'account_name')
                ->searchable(),

            Column::make('Tanggal', 'updated_at')
                ->hidden(),

            Column::make('Tanggal', 'updated_at_formatted', 'updated_at')
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
            // Filter::inputText('name'),
            // Filter::datepicker('created_at_formatted', 'created_at'),
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
     * PowerGrid BankAccount Action Buttons.
     *
     * @return array<int, Button>
     */

    
    public function actions(): array
    {
        if ($this->status == 'request') {
            $actions = [
                Button::add('Validasi')
                    ->caption(__('Validasi'))
                    ->class('btn btn-default-dark')
                    ->method('post')
                    ->target('_self')
                    ->route('admin.umkm-account.confirm', [
                        'account_number' => 'account_number',
                        'id' => 'id',
                ]),
    
                Button::add('Tolak')
                    ->caption(__('Tolak'))
                    ->class('btn btn-default-red')
                    ->method('post')
                    ->target('_self')
                    ->route('admin.umkm-account.reject', [
                        'account_number' => 'account_number',
                        'id' => 'id',
                    ]),
            ];
        }else if ($this->status == 'revoked') {
            $actions = [
                Button::add('Aktivasi')
                    ->caption(__('Aktivasi'))
                    ->class('btn btn-default-dark')
                    ->method('post')
                    ->target('_self')
                    ->route('admin.umkm-account.confirm', [
                        'account_number' => 'account_number',
                        'id' => 'id',
                ]),  
            ];
        }
        else if ($this->status == 'acc') {
            $actions = [
                Button::add('Nonaktifkan')
                ->caption(__('Nonaktifkan'))
                ->class('btn btn-default-red')
                ->method('post')
                ->target('_self')
                ->route('admin.umkm-account.revoke', [
                    'account_number' => 'account_number',
                    'id' => 'id',
                ]),    
            ];
        }
        else {
            $actions = [
                Button::add('Aktivasi')
                    ->caption(__('Aktivasi'))
                    ->class('btn btn-default-dark')
                    ->method('post')
                    ->target('_self')
                    ->route('admin.umkm-account.confirm', [
                        'account_number' => 'account_number',
                        'id' => 'id',
                ]),  
            ];
        }
    //    return [
    //        Button::make('edit', 'Edit')
    //            ->class('bg-indigo-500 cursor-pointer text-white px-3 py-2.5 m-1 rounded text-sm')
    //            ->route('bank-account.edit', ['bank-account' => 'id']),

    //        Button::make('destroy', 'Delete')
    //            ->class('bg-red-500 cursor-pointer text-white px-3 py-2 m-1 rounded text-sm')
    //            ->route('bank-account.destroy', ['bank-account' => 'id'])
    //            ->method('delete')
    //     ];

        return $actions;
    }
    

    /*
    |--------------------------------------------------------------------------
    | Actions Rules
    |--------------------------------------------------------------------------
    | Enable the method below to configure Rules for your Table and Action Buttons.
    |
    */

    /**
     * PowerGrid BankAccount Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($bank-account) => $bank-account->id === 1)
                ->hide(),
        ];
    }
    */
}
