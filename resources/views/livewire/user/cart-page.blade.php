@push('stylesheet')
    <link rel="stylesheet" href="{{ asset('css/cart-page.css') }}">
@endpush

<div class="cart-page">
    <div class="container">
        <div class="page-title">
            <h1>Keranjang</h1>
        </div>
        {{-- <div class="page-nav-menu-wrapper">
            <ul>
                <li
                    @if (\Request::route()->getName() == 'cart-page')
                        class='active'
                    @endif
                ><a 
                    href="{{ route('cart-page') }}">Keranjang</a></li>
                <li><a href="#">Dalam Proses</a></li>
                <li><a href="#">Selesai</a></li>
                <li><a href="#">Dibatalkan</a></li>
            </ul>
        </div> --}}

        @foreach (range(0,3) as $item)
            <div class="page-content-card">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <td>02-02-2023</td>
                                <td colspan="3">Mitra UMKM</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (range(0,3) as $item)
                                <tr>
                                    <td>
                                        <div class="image-container">
                                            <img src="{{ 'img\aziz-acharki-boIJluEJEPM-unsplash.jpg' }}" alt="FILL THIS">
                                        </div>
                                    </td>
                                    <td>
                                        <a href="#">Lorem ipsum dolor sit amet consectetur</a>
                                    </td>
                                    <td>
                                        <div class="prices">
                                            <span class="base-price">Rp. 120.000.000,00</span>
                                            <span class="final-price">Rp. 60.000.000,00</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="qty-wrapper">
                                            <span>x</span>
                                            <input class="form-control" type="number" placeholder="qty">
                                            <i class="fa-solid fa-trash delete-ic"></i>
                                        </div>
                                    </td>
                                    <td class="amount">
                                        Rp. 180.000.000,00
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td colspan="5">
                                    <div class="sub-total-wrapper">
                                        <span class="sub-total-label">Sub Total</span> 
                                        <div class="sub-total">Rp. 888.000,00</div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="button-wrapper">
                    <button class="btn checkout-btn">Checkout</button>
                </div>
            </div>
        @endforeach

    </div>
</div>