@extends('layout.cart')

@section('title')
ChicOut - Cart
@endsection

@section('menu')
<li class="{{ request()->is('ChicOut/Category/Men') ? 'active' : '' }}">
    <a href="{{ url('ChicOut/Category/Men') }}"
        style="background-image: url('{{ asset('image/Men.jpeg') }}')">
        Men
    </a>
</li>
<li class="{{ request()->is('ChicOut/Category/Women') ? 'active' : '' }}">
    <a href="{{ url('ChicOut/Category/Women') }}"
        style="background-image: url('{{ asset('image/Women.jpeg') }}')">
        Women
    </a>
</li>
<li class="{{ request()->is('ChicOut/Category/Kids') ? 'active' : '' }}">
    <a href="{{ url('ChicOut/Category/Kids') }}"
        style="background-image: url('{{ asset('image/Kids.jpeg') }}')">
        Kids
    </a>
</li>

@auth
@if (auth()->user()->role === 'master')
<li><a href="{{ route('master-home') }}">Master</a></li>
@else
<li class="active"><a href="{{ route('cart') }}">Cart</a></li>
<li><a href="{{ route('profile') }}">Profile</a></li>
@endif
@endauth
@guest
<li><a href="{{ route('login') }}">Login</a></li>
@endguest
@endsection


@section('content')

<br><br><br><br><br>
<div class="container">
    @if (count($listCart) > 0)
    <form method="POST">
        @csrf
        <button title="Clear Cart" class="btn btn-danger" name="clear">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10" />
                <line x1="15" y1="9" x2="9" y2="15" />
                <line x1="9" y1="9" x2="15" y2="15" />
            </svg>
        </button>
    </form>
    <br>
    <form action={{ route('checkout') }} method="post">
        @csrf
        <div class="container" id="cartListAjax">
            @php
            $total = 0; // Initialize the total variable
            @endphp
            @foreach ($listCart as $item)
            <div class="">
                <div class="row g-0 isi-cart p-5">
                    <div class="col-md-2" style="padding-left: 0px !important; margin: auto 0">
                        <img src="{{ $item->Item->img }}" class="img-thumbnail rounded-start" alt="...">
                    </div>
                    <div class="col-md-7">
                        <a href="{{ route('detail', ['id' => $item->ID_items]) }}" class="">
                            <h5 class="custom-card-title">{{ $item->Item->name }}</h5>
                            <p>{{ implode(' ', array_slice(explode(' ', $item->Item->description), 0, 20)) }}..
                            </p>
                        </a>
                        @if ($item->Item->discount != 0)
                        {{-- has discount --}}
                        <h3 class="custom-card-title">
                            <span>Rp
                                {{ number_format(floor($item->Item->price - ($item->Item->price * $item->Item->discount) / 100), 0, ',', '.') }}</span><br>
                            <small><span
                                    style="background-color: #d43f3a; color: white; border-radius: 10%; padding: 1% 1%">{{ $item->Item->discount }}%</span>
                                <del>Rp {{ number_format($item->Item->price, 0, ',', '.') }}</del></small>
                        </h3>
                        @else
                        {{-- no discount --}}
                        <h3 class="custom-card-title">Rp
                            {{ number_format($item->Item->price, 0, ',', '.') }}
                        </h3>
                        @endif
                    </div>
                    <div class="col-md-3" style="margin: auto 0">
                        <div class="" style="margin: auto 0; display: flex">
                            @if ($item->Item->discount != 0)
                            @php
                            $subtotal = floor($item->Item->price - (($item->Item->price * $item->Item->discount) / 100)) * $item->qty;
                            $total += $subtotal; // Add the subtotal to the total
                            @endphp
                            @else
                            {{-- no discount --}}
                            @php
                            $subtotal = $item->Item->price * $item->qty;
                            $total += $subtotal; // Add the subtotal to the total
                            @endphp
                            @endif
                            <div style="margin: 5px;">
                                <p>Qty: <input type="number" min="1" data-idcart="{{ $item->ID_cart }}"
                                        name="qty" class="qty" value="{{ $item->qty }}"
                                        style="color: black; padding: 4.5px; border-radius: 5px">
                                </p>
                            </div>
                            <div style="margin: auto 0">
                                <button class="btn btn-danger" value="{{ $item->ID_cart }}" name="delete"><i
                                        class="icon-trash2"></i></button>

                            </div>
                        </div>
                        <div style="margin-left: 5px;">
                            <p>
                                Rp {{ number_format($subtotal, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            <div class="row isi-cart" style="margin-bottom: 2%; padding-bottom: 2%;">
                <div class="col-md-9"></div>
                <div class="col-md-3">
                    <h3 id="total" class="custom-card-title">Total: Rp {{ number_format($total, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <button class="btn btn-primary" type="submit" name="buy">
            <svg xmlns="http://www.w3.org/2000/svg" height="16" width="18" viewBox="0 0 576 512" fill="white">
                <path d="M512 80c8.8 0 16 7.2 16 16v32H48V96c0-8.8 7.2-16 16-16H512zm16 144V416c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V224H528zM64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zm56 304c-13.3 0-24 10.7-24 24s10.7 24 24 24h48c13.3 0 24-10.7 24-24s-10.7-24-24-24H120zm128 0c-13.3 0-24 10.7-24 24s10.7 24 24 24H360c13.3 0 24-10.7 24-24s-10.7-24-24-24H248z" />
            </svg>
        </button>
    </form>
    @else
    <div class="container-fluid cart-empty">
        <svg fill="white" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
            xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 231.523 231.523"
            style="enable-background:new 0 0 231.523 231.523;" xml:space="preserve">
            <g>
                <path
                    d="M107.415,145.798c0.399,3.858,3.656,6.73,7.451,6.73c0.258,0,0.518-0.013,0.78-0.04c4.12-0.426,7.115-4.111,6.689-8.231
                        l-3.459-33.468c-0.426-4.12-4.113-7.111-8.231-6.689c-4.12,0.426-7.115,4.111-6.689,8.231L107.415,145.798z">
                </path>
                <path d="M154.351,152.488c0.262,0.027,0.522,0.04,0.78,0.04c3.796,0,7.052-2.872,7.451-6.73l3.458-33.468
                        c0.426-4.121-2.569-7.806-6.689-8.231c-4.123-0.421-7.806,2.57-8.232,6.689l-3.458,33.468
                        C147.235,148.377,150.23,152.062,154.351,152.488z"></path>
                <path d="M96.278,185.088c-12.801,0-23.215,10.414-23.215,23.215c0,12.804,10.414,23.221,23.215,23.221
                        c12.801,0,23.216-10.417,23.216-23.221C119.494,195.502,109.079,185.088,96.278,185.088z M96.278,216.523
                        c-4.53,0-8.215-3.688-8.215-8.221c0-4.53,3.685-8.215,8.215-8.215c4.53,0,8.216,3.685,8.216,8.215
                        C104.494,212.835,100.808,216.523,96.278,216.523z"></path>
                <path d="M173.719,185.088c-12.801,0-23.216,10.414-23.216,23.215c0,12.804,10.414,23.221,23.216,23.221
                        c12.802,0,23.218-10.417,23.218-23.221C196.937,195.502,186.521,185.088,173.719,185.088z M173.719,216.523
                        c-4.53,0-8.216-3.688-8.216-8.221c0-4.53,3.686-8.215,8.216-8.215c4.531,0,8.218,3.685,8.218,8.215
                        C181.937,212.835,178.251,216.523,173.719,216.523z"></path>
                <path
                    d="M218.58,79.08c-1.42-1.837-3.611-2.913-5.933-2.913H63.152l-6.278-24.141c-0.86-3.305-3.844-5.612-7.259-5.612H18.876
                        c-4.142,0-7.5,3.358-7.5,7.5s3.358,7.5,7.5,7.5h24.94l6.227,23.946c0.031,0.134,0.066,0.267,0.104,0.398l23.157,89.046
                        c0.86,3.305,3.844,5.612,7.259,5.612h108.874c3.415,0,6.399-2.307,7.259-5.612l23.21-89.25
                        C220.49,83.309,220,80.918,218.58,79.08z M183.638,165.418H86.362l-19.309-74.25h135.895L183.638,165.418z">
                </path>
                <path
                    d="M105.556,52.851c1.464,1.463,3.383,2.195,5.302,2.195c1.92,0,3.84-0.733,5.305-2.198c2.928-2.93,2.927-7.679-0.003-10.607
                        L92.573,18.665c-2.93-2.928-7.678-2.927-10.607,0.002c-2.928,2.93-2.927,7.679,0.002,10.607L105.556,52.851z">
                </path>
                <path d="M159.174,55.045c1.92,0,3.841-0.733,5.306-2.199l23.552-23.573c2.928-2.93,2.925-7.679-0.005-10.606
                        c-2.93-2.928-7.679-2.925-10.606,0.005l-23.552,23.573c-2.928,2.93-2.925,7.679,0.005,10.607
                        C155.338,54.314,157.256,55.045,159.174,55.045z"></path>
                <path
                    d="M135.006,48.311c0.001,0,0.001,0,0.002,0c4.141,0,7.499-3.357,7.5-7.498l0.008-33.311c0.001-4.142-3.356-7.501-7.498-7.502
                        c-0.001,0-0.001,0-0.001,0c-4.142,0-7.5,3.357-7.501,7.498l-0.008,33.311C127.507,44.951,130.864,48.31,135.006,48.311z">
                </path>
            </g>
        </svg>
        <div>
            <h4 class="custom-card-title">You have not added any items in your Shopping Cart</h4>
            <button class="btn"><a href="{{ route('home') }}">Click to Order</a></button>
        </div>
    </div>
    @endif

    {{-- popup --}}
    @if (Session::has('snap_token'))
    <script>
        (() => {
            window.snap.pay(`{{ Session::get('snap_token') }}`, {
                onSuccess: async function(result) {
                    /* You may add your own implementation here */
                    alert("payment success!");
                    console.log(result);
                    alert(result);

                    const formElement = document.createElement("form");
                    formElement.action = "{{ route('validate-payment') }}";
                    formElement.method = "POST";
                    formElement.style.display = "none";
                    formElement.innerHTML = `
                        // <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @csrf
                        <input type="hidden" name="snap_response" value='${JSON.stringify(result)}'>
                    `;
                    document.body.appendChild(formElement);
                    formElement.submit();
                },
                onPending: function(result) {
                    /* You may add your own implementation here */
                    alert("wating your payment!");
                    console.log(result);
                },
                onError: function(result) {
                    /* You may add your own implementation here */
                    alert("payment failed!");
                    console.log(result);
                },
                onClose: function() {
                    /* You may add your own implementation here */
                    alert('you closed the popup without finishing the payment');
                }
            });

        })()
    </script>
    @endif

    @if (Session::has('choose_payment'))
    <div id="paymentPopup" data-animate-effect="fadeIn"
        class="card">
        <form method="POST">
            @csrf
            <div class="card-body trans-payment-isi">
                <div class="payment-title">
                    <div>
                        <h3 class="custom-card-title" style="color: black">Choose Payment Method</h3>
                    </div>
                </div>
                <div class="row row-cols-12" style="display:flex;gap: 10px; margin: 20px;/* margin-left: 0px; */">
                    <!-- @foreach ($listPayment as $p)
                    <button type="submit" name="pay" value="{{ $p->ID_payments }}"
                        class="col btn btn-buy-cart"
                        style="box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); background: transparent; ">

                        <img src="{{ $p->img }}" alt="">

                        <p style="margin-top: 20px; margin-bottom: -5px">{{ $p->name }}</p>
                        @endforeach -->
                </div>
                <div>
                    <button onclick="close()" class="btn btn-danger">Cancel Payment</button>
                </div>
            </div>
        </form>
    </div>
    @endif

    @if (Session::has('sukses'))
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Yay...',
            text: '{{ Session::get(key: "sukses") }}',
            customClass: {
                confirmButton: 'btn btn-success',
                container: 'my-swal'
            }
        });
    </script>
    <style>
        .my-swal .swal2-confirm {
            margin: 1em;
        }
    </style>
    @elseif (Session::has('fail'))
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Nooo...',
            text: '{{ Session::get("fail") }}',
            customClass: {
                confirmButton: 'btn btn-danger',
                container: 'my-swal'
            }
        });
    </script>
    <style>
        .my-swal .swal2-confirm {
            margin: 1em;
        }
    </style>
    @endif
</div>
@endsection

@push('style')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
@endpush

@push('script')
<script>
    // Function to set up event handlers
    function setupEventHandlers() {
        $(".qty").on('change', function() {
            var ID_cart = $(this).data('idcart');
            var qty = $(this).val();
            $.post("{{ route('update-cart') }}", {
                    ID_cart: ID_cart,
                    qty: qty,
                    '_token': '{{ csrf_token() }}'
                },
                function(data) {
                    $('#cartListAjax').html(data.view);
                    setupEventHandlers();
                }
            );
        });
    }

    // Call the function to set up event handlers when the document is ready
    $(document).ready(function() {
        setupEventHandlers();
    });

    function close() {
        $('.trans-payment, .trans-payment-isi').fadeOut(2000); // 2000 milidetik atau 2 detik
    }
</script>
@endpush