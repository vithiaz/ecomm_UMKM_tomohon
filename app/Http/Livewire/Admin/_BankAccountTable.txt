<?php

namespace App\Http\Livewire\Admin;

use App\Models\BankAccount;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

class BankAccountTable extends PowerGridComponent
{
    use ActionButton;

    // Binding Variable
    public string $status;

    public function setUp()
    {
        $this->showPerPage()
            ->showSearchInput();
    }

    public function dataSource(): array
    {
        $model = BankAccount::query()->
                    with('user')->
                    where('status', '=', $this->status)->
                    get();

        return PowerGrid::eloquent($model)
            ->addColumn('id')
            ->addColumn('user_id', function(BankAccount $model) {
                return $model->user->id;
            })
            ->addColumn('bank_name')
            ->addColumn('account_number')
            ->addColumn('account_name')
            // ->addColumn('name')
            ->addColumn('updated_at')
            ->addColumn('updated_at_formatted', function(BankAccount $model) {
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
                ->hidden(),

            Column::add()
                ->title(__('User ID'))
                ->field('user_id')
                ->searchable(),

            Column::add()
                ->title(__('Bank'))
                ->field('bank_name')
                ->searchable(),

            Column::add()
                ->title(__('No. Rekening'))
                ->field('account_number')
                ->searchable(),

            Column::add()
                ->title(__('Atas Nama'))
                ->field('account_name')
                ->searchable(),

            Column::add()
                ->title(__('Tanggal'))
                ->field('updated_at')
                ->sortable()
                ->hidden(),

            Column::add()
                ->title(__('Tanggal'))
                ->field('updated_at_formatted')
                ->makeInputDatePicker('updated_at')
                ->sortable()
                ->searchable()
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
        if ($this->status == 'request') {
            $actions = [
                Button::add('Validasi')
                    ->caption(__('Validasi'))
                    ->class('btn btn-default-dark')
                    ->method('post')
                    ->route('admin.umkm-account.confirm', [
                        'account_number' => 'account_number',
                        'id' => 'id',
                ]),
    
                Button::add('Tolak')
                    ->caption(__('Tolak'))
                    ->class('btn btn-default-red')
                    ->method('post')
                    ->route('admin.umkm-account.reject', [
                        'account_number' => 'account_number',
                        'id' => 'id',
                    ]),
            ];
        }
        else if ($this->status == 'revoked') {
            $actions = [
                Button::add('Aktivasi')
                    ->caption(__('Aktivasi'))
                    ->class('btn btn-default-dark')
                    ->method('post')
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
                    ->route('admin.umkm-account.confirm', [
                        'account_number' => 'account_number',
                        'id' => 'id',
                ]),  
            ];
        }

        return $actions;
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
           $updated = BankAccount::query()->find($data['id'])->update([
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
