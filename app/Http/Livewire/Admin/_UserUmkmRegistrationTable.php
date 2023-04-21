<?php

namespace App\Http\Livewire\Admin;

use App\Models\UmkmRegistration;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

class UserUmkmRegistrationTable extends PowerGridComponent
{
    use ActionButton;

    // Binding Variable
    public string $status;

    
    public function setUp()
    {
        $this->showPerPage()
            // ->showCheckBox()    
            ->showSearchInput();
    }

    public function dataSource(): array
    {
        $model = UmkmRegistration::query()
                    ->with(
                        'user',
                    )->where('status' , '=', $this->status)->get();
        
        return PowerGrid::eloquent($model)
            ->addColumn('user_id', function(UmkmRegistration $model) {
                return $model->user->id;
            })
            ->addColumn('id')
            ->addColumn('status')
            ->addColumn('message')
            ->addColumn('username', function(UmkmRegistration $model) {
                return $model->user->username;
            })
            ->addColumn('full_name', function(UmkmRegistration $model) {
                $fullname = $model->user->first_name;
                if ($model->user->last_name) {
                    $fullname = $fullname . ' ' . $model->user->last_name;
                }
                return $fullname;
            })
            ->addColumn('address', function(UmkmRegistration $model) {
                return $model->user->address;
            })
            // ->addColumn('name')
            ->addColumn('updated_at')
            ->addColumn('updated_at_formatted', function(UmkmRegistration $model) {
                return Carbon::parse($model->updated_at)->format('d/m/Y H:i:s');
            })
            ->make();
    }

    public function columns(): array
    {
        if ($this->status == 'acc') {
            $column_return = [
                Column::add()
                    ->title(__('User ID'))
                    ->field('user_id')
                    ->searchable()
                    ->sortable(),
    
                Column::add()
                    ->title(__('Username'))
                    ->field('username')
                    ->searchable(),
    
                Column::add()
                    ->title(__('Nama Lengkap'))
                    ->field('full_name')
                    ->searchable(),
    
                Column::add()
                    ->title(__('Alamat'))
                    ->field('address')
                    ->searchable(),
    
                Column::add()
                    ->title(__('Status'))
                    ->field('status')
                    ->hidden(),
    
                Column::add()
                    ->title(__('Pesan'))
                    ->field('message')
                    ->hidden(),
    
                Column::add()
                    ->title(__('Tanggal'))
                    ->field('updated_at')
                    ->sortable()
                    ->searchable()
                    ->hidden(),
    
                Column::add()
                    ->title(__('Tanggal'))
                    ->field('updated_at_formatted')
                    ->makeInputDatePicker('updated_at')
                    ->sortable()
                    ->searchable()
            ];
        } else {
            $column_return = [
                Column::add()
                    ->title(__('User ID'))
                    ->field('user_id')
                    ->searchable()
                    ->sortable(),
    
                Column::add()
                    ->title(__('Username'))
                    ->field('username')
                    ->searchable(),
    
                Column::add()
                    ->title(__('Nama Lengkap'))
                    ->field('full_name')
                    ->searchable(),
    
                Column::add()
                    ->title(__('Alamat'))
                    ->field('address')
                    ->searchable(),
    
                Column::add()
                    ->title(__('Status'))
                    ->field('status'),
    
                Column::add()
                    ->title(__('Pesan'))
                    ->field('message'),
    
                Column::add()
                    ->title(__('Tanggal'))
                    ->field('updated_at')
                    ->sortable()
                    ->searchable()
                    ->hidden(),
    
                Column::add()
                    ->title(__('Tanggal'))
                    ->field('updated_at_formatted')
                    ->makeInputDatePicker('updated_at')
                    ->sortable()
                    ->searchable()
            ];

        }

        return $column_return;
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
        return  [
            Button::add('tinjau')
            ->caption(__('Verifikasi'))
            ->class('btn btn-default-orange')
            ->route('admin.umkm-registration-review', [
                'user_id' => 'user_id',
                'reg_id' => 'id',
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
           $updated = UmkmRegistration::query()->find($data['id'])->update([
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
