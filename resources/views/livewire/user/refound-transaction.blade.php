@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/cart-page.css') }}">
@endpush

<div class="cart-page">
    <div class="container">
        <div class="page-title">
            <h1>Refound Transaksi</h1>
        </div>
        
        <div class="page-content-card lighten">
            <div class="card-id-wrapper">
                <span class="id">{{ $order_detail->umkm->name }}</span>
                <div class="status">{{ $order_detail->payment_status }}</div>
            </div>
            <div class="address-wrapper">
                <div class="label">Alamat pengiriman</div>
                <p>{{ $order_detail->order_address }}</p>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <td>{{ $this->get_date($order_detail->created_at) }}</td>
                            <td colspan="4">Daftar Produk</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order_detail->order_item as $item)
                            <tr>
                                <td>
                                    {{-- <div class="image-container">
                                        <img src="#" alt="FILL THIS">
                                    </div> --}}
                                    {{ $item->delivery_status }}
                                </td>
                                <td>
                                    <a href="{{ route('product-details', ['product_id' => $item->product->id, 'name_slug' => $item->product->name_slug]) }}">{{ $item->product->name }}</a>
                                </td>
                                <td>
                                    <div class="prices">
                                        <span class="final-price">{{ format_rupiah($item->amount) }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="qty-wrapper">
                                        <span>x{{ $item->qty }}</span>
                                    </div>
                                </td>
                                <td class="amount">
                                    {{ format_rupiah( $this->calculate_amount($item->amount, $item->qty) ) }}
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5">
                                <div class="sub-total-wrapper">
                                    <span class="sub-total-label">Sub Total</span> 
                                    <div class="sub-total">{{ format_rupiah($order_detail->payment_amount) }}</div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            {{-- <div class="button-wrapper">
                <button style="display: none" id="refoundModalToggleBtn" type="button" data-bs-toggle="modal" data-bs-target="#refoundModal"></button>
                <button type="button" class="btn btn-default-red">Batalkan Pesanan</button>
            </div> --}}
        </div>

        <div class="page-content-card lighten">
            <div class="content-card-title">
                <span>Form Refound</span>
            </div>

            <div class="info-wrapper">
                <p>Informasi nomor rekening dibutuhkan untuk melakukan refound. Admin akan melakukan refound pada nomor rekening yang valid dalam hari kerja.</p>
                <p>Nominal refound adalah sebesar Sub Total dari transaksi.</p>
            </div>
                
            <form wire:submit.prevent='request_refound' class="account-input-form">
                <select wire:model='bank_name' class="form-select @error('bank_name') is-invalid @enderror" aria-label="Pilih Bank">
                    <option selected>Pilih Bank</option>
                    <option value="BRI">BRI</option>
                    <option value="BNI">BNI</option>
                    <option value="MANDIRI">Mandiri</option>
                </select>
                @error('bank_name')
                    <small class="error">{{ $message }}</small>
                @enderror
                <div class="form-floating">
                    <input wire:model='account_number' type="text" class="form-control @error('account_number') is-invalid @enderror" id="account-number-input" placeholder="Nomor Rekening">
                    <label for="account-number-input">Nomor Rekening</label>
                    @error('account_number')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-floating">
                    <input wire:model='account_name' type="text" class="form-control @error('account_name') is-invalid @enderror" id="full-name-input" placeholder="Nama Lengkap Pemilik Rekening">
                    <label for="full-name-input">Nama Lengkap Pemilik Rekening</label>
                    @error('account_name')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-floating">
                    <textarea wire:model='refound_description' type="text" class="form-control @error('refound_description') is-invalid @enderror" style="min-height: 130px;" id="refound-description-input" placeholder="Keterangan / Alasan melakukan refound ..."></textarea>
                    <label for="refound-description-input">Keterangan / Alasan melakukan refound ...</label>
                    @error('refound_description')
                        <small class="error">{{ $message }}</small>
                    @enderror
                </div>
                <div class="button-wrapper">
                    <button type="submit" class="btn btn-refound">Refound Transaction</button>
                </div>
            </form>

        </div>

    </div>


    {{-- Confirmation Modal --}}
    {{-- <div wire:ignore.self class="modal fade" id="refoundModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <button class="close-modal-button" onclick="hide_refound_modal()" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>

                <div class="title">Informasi</div>

                <div class="card-content-wrapper">
                    <p class="indent">Pesanan dapat dibatalkan apabila penjual belum melakukan pengiriman produk ke alamat tujuan.</p>
                    <p class="indent">Anda akan diminta untuk mengisi form pembatalan transaksi serta mencantumkan informasi rekening Bank yang valid untuk dilakukan refound. Admin akan melakukan refound pada hari kerja.</p>
                </div>

                <div class="card-content-wrapper">
                    <span class="card-content-wrapper-title">Konfirmasi Pembatalan Pesanan</span>
                    <div class="buttons-container">
                        <button onclick="redirect_to_refound_page()" class="btn btn-danger">Batalkan Pesanan</button>
                        <button onclick="hide_refound_modal()" data-bs-dismiss="modal" class="btn btn-secondary">Kembali</button>
                    </div>
                </div>

            </div>
        </div>
    </div> --}}


</div>

@push('script')
<script>

    $(document).ready(function () {
        $('.app-content').addClass('lighten');
    })
    
    // Modal
    function hide_refound_modal() {
        $('#refoundModal').modal('hide');
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
        // $('.modal-backdrop').remove();
    }
    $(window).on('toggleRefoundModal', function () {
        $('#refoundModalToggleBtn').click()
    })




</script>
@endpush