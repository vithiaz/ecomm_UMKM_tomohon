<?php

namespace App\Http\Livewire\Admin;

use App\Models\UmkmRegistration;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use PowerComponents\LivewirePowerGrid\Rules\{Rule, RuleActions};
use PowerComponents\LivewirePowerGrid\Filters\Filter;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;
use PowerComponents\LivewirePowerGrid\{Button, Column, Exportable, Footer, Header, PowerGrid, PowerGridComponent, PowerGridEloquent};

final class UserUmkmRegistrationTable extends PowerGridComponent
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
        return UmkmRegistration::query()
                ->with(
                    'user',
                )->where('status' , '=', $this->status);
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function addColumns(): PowerGridEloquent
    {
        return PowerGrid::eloquent()
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
            ->addColumn('updated_at')
            ->addColumn('updated_at_formatted', fn (UmkmRegistration $model) => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'));
    }

    public function columns(): array
    {
        if ($this->status == 'acc') {
            return [
                Column::make('User ID', 'user_id')
                    ->searchable()
                    ->sortable(),
    
                Column::make('Username', 'username')
                    ->searchable(),
    
                Column::make('Nama Lengkap', 'full_name')
                    ->searchable(),
    
                Column::make('Alamat', 'address')
                    ->searchable(),
    
                Column::make('Status', 'status')
                    ->hidden(),
    
                Column::make('Pesan', 'message')
                    ->hidden(),
    
                Column::make('Tanggal', 'updated_at_formatted', 'updated_at')
                    ->sortable()
                    ->searchable(),
            ];
        } else {
            return [
                Column::make('User ID', 'user_id')
                    ->searchable()
                    ->sortable(),
    
                Column::make('Username', 'username')
                    ->searchable(),
    
                Column::make('Nama Lengkap', 'full_name')
                    ->searchable(),
    
                Column::make('Alamat', 'address')
                    ->searchable(),

                Column::make('Status', 'status'),
    
                Column::make('Pesan', 'message'),
    
                Column::make('Tanggal', 'updated_at_formatted', 'updated_at')
                    ->sortable()
                    ->searchable(),
            ];
        }

    }

    public function filters(): array
    {
        return [
            // Filter::inputText('name'),
            // Filter::datepicker('created_at_formatted', 'created_at'),
        ];
    }

    
    public function actions(): array
    {
       return [
           Button::make('Verifikasi', 'tinjau')
               ->class('btn btn-default-orange')
               ->target('_self')
               ->route('admin.umkm-registration-review', [
                    'user_id' => 'user_id',
                    'reg_id' => 'id',
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
     * PowerGrid UmkmRegistration Action Rules.
     *
     * @return array<int, RuleActions>
     */

    /*
    public function actionRules(): array
    {
       return [

           //Hide button edit for ID 1
            Rule::button('edit')
                ->when(fn($umkm-registration) => $umkm-registration->id === 1)
                ->hide(),
        ];
    }
    */
}
