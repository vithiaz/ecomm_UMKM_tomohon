<?php

namespace App\Http\Livewire\Components;

use Livewire\Component;
use App\Models\BankAccount;
use App\Models\UmkmRegistration;
use Illuminate\Support\Facades\Auth;

class AddBankAccountUmkmProfilePage extends Component
{
    // Model Variable
    public $umkm_registration;

    // Binding Variable
    public $umkm_status;
    public $umkm_message;
    public $bank_name;
    public $account_number;
    public $account_name;

    public $bank_account_delete_id;

    public function mount() {
        $this->umkm_registration = UmkmRegistration::where('user_id', '=', Auth::user()->id)->first();
        if ($this->umkm_registration != null) {
            $this->umkm_status = $this->umkm_registration->status;
            $this->umkm_message = $this->umkm_registration->message;
        } else {
            $this->umkm_status = null;
            $this->umkm_message = null;
        }
    }
   
    public function render()
    {
        $bank_accounts = Auth::user()->bank_accounts()->get()->all();

        return view('livewire.components.add-bank-account-umkm-profile-page', [
            'bank_accounts' => $bank_accounts,
        ]);
    }

    public function request_umkm_status() {
        $umkm_stat = UmkmRegistration::where('user_id', '=', Auth::user()->id)->first();
        
        if ($umkm_stat == null) {
            $new_umkm_stat = new UmkmRegistration;
            $new_umkm_stat->user_id = Auth::user()->id;
            $new_umkm_stat->status = 'request';
            $new_umkm_stat->message = 'Registrasi pertama';
            $new_umkm_stat->save();

            $msg = ['success' => 'Pengajuan dikirim'];
            $this->dispatchBrowserEvent('display-message', $msg);
            $this->dispatchBrowserEvent('forceReload');

            return redirect(request()->header('Referer'));
        } else {
            $umkm_stat->status = 'request';
            $umkm_stat->save();
            $msg = ['success' => 'Pengajuan dikirim'];
            $this->dispatchBrowserEvent('display-message', $msg);

            return redirect(request()->header('Referer'));
        }
    }

    public function register_bank_acc() {
        $this->validate([
            'bank_name' => 'required',
            'account_number' => 'required|string',
            'account_name' => 'required|string',
        ]);
        
        $bankAccount = new BankAccount;
        $bankAccount->bank_name = $this->bank_name;
        $bankAccount->account_number = $this->account_number;
        $bankAccount->account_name = $this->account_name;
        $bankAccount->user_id = Auth::user()->id;
        $bankAccount->status = 'request';
        $bankAccount->save();

        $this->bank_name = null;
        $this->account_number = null;
        $this->account_name = null;

        $msg = ['success' => 'Akun Bank ditambahkan'];
        $this->dispatchBrowserEvent('display-message', $msg);
    }

    public function bank_acc_state_delete($id) {
        $this->bank_account_delete_id = $id;
        $this->dispatchBrowserEvent('toggle-delete-acc-modal');
    }

    public function delete_bank_account() {
        $delete_bank_acc = BankAccount::where('id', '=', $this->bank_account_delete_id)->first();
        if ($delete_bank_acc != null) {
            $delete_bank_acc->delete();
            $msg = ['success' => 'Akun Bank dihapus'];
            $this->dispatchBrowserEvent('display-message', $msg);
        }

        $this->dispatchBrowserEvent('toggle-delete-acc-modal');
    }
    
}
