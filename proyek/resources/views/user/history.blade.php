@extends('layout.general')
@section('title')
    ChicOut - {{ Auth::user()->display_name }}
@endsection

@section('menu')
    <li>
        <a href="{{ url('ChicOut/Category/Men') }}">Men</a>
    </li>
    <li>
        <a href="{{ url('ChicOut/Category/Women') }}">Women</a>
    </li>
    <li class="has-dropdown">
        <a>More</a>
        <ul class="dropdown">
            @foreach ($listMerch as $c)
                <li>
                    <a href="{{ url('ChicOut/Category/' . $c['name']) }}">{{ $c['name'] }}</a>
                </li>
            @endforeach
        </ul>
    </li>

    @auth
        @if (auth()->user()->role === 'master')
            <li><a href="{{ route('master-home') }}">Master</a></li>
        @else
            <li><a href="{{ route('cart') }}">Cart</a></li>
            <li class="active"><a href="{{ route('profile') }}">Profile</a></li>
        @endif
    @endauth
    @guest
        <li><a href="{{ route('login') }}">Login</a></li>
    @endguest
@endsection


@section('content')
    <div class="container" style="margin-top: 15%">
        <div class="page">
            <a href="{{ route('profile') }}" class="btn btn-primary"><i class="icon-back"></i></a>
            <div class="" style="margin-top: 2%; padding-bottom: 2%;">
                <h3 class="custom-card-title">Transaction Details</h3>
                <p class="custom-card-title">
                    Sent to : {{$htrans->address}}
                </p>
                <p class="custom-card-title">
                    Purchase date : {{ \Carbon\Carbon::parse($htrans->purchase_date)->format('d F Y - H:i') }}
                </p>
            </div>
            <div class="container">
                <div class="row" style="text-align: center;">
                    <div class="col-md-2">

                    </div>
                    <div class="col-md-2">
                        <h3 class="custom-card-title">Name</h3>
                    </div>
                    <div class="col-md-1">
                        <h3 class="custom-card-title">Qty</h3>
                    </div>
                    <div class="col-md-2">
                        <h3 class="custom-card-title">Price</h3>
                    </div>
                    <div class="col-md-1">
                        <h3 class="custom-card-title">Discount</h3>
                    </div>
                    <div class="col-md-3">
                        <h3 class="custom-card-title">Subtotal</h3>
                    </div>
                    <div class="col-md-1">
                        <h3 class="custom-card-title">Action</h3>
                    </div>
                </div>
                @foreach ($listDtrans as $d)
                    <div class="row" style="padding: 2% 0; color: white; text-align: center; display: flex; align-items: center; background-color: #cda45e; border-bottom: 2px solid">
                        <div class="col-md-2">
                            <a href="{{ route('detail', ['id' => $d->Item->ID_items]) }}">
                                <img src="{{ $d->Item->img }}" alt="" class="img-thumbnail">
                            </a>
                        </div>
                        <div class="col-md-2">
                            <a href="{{ route('detail', ['id' => $d->Item->ID_items]) }}">
                                <p style="color: white">{{ $d->Item->name }}</p>
                            </a>
                        </div>
                        <div class="col-md-1">
                            <p>Qty: {{ $d->qty }}</p>
                        </div>
                        <div class="col-md-2">
                            <p>Rp {{ number_format($d->price, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-1">
                            <p>
                                @if ($d->discount != 0)
                                    {{ $d->discount }}%
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="col-md-3">
                            <p>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</p>
                        </div>
                        <div class="col-md-2">
                            <p>
                                <a href="{{ route('return-items', ['id' => $d->Item->ID_items]) }}" style="color:red">Return</a>
                            </p>
                        </div>
                        
                    </div>
                @endforeach
                <div class="" id="" style="text-align: right; color: white">
                    <div class="row">
                        <div class="col-md-9">

                        </div>
                        <div class="col-md-3">
                            <p class="custom-card-title">
                                Total : Rp {{ number_format($htrans->total, 0, ',', '.') }} via {{$htrans->Payment->name}}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div id="return-form-container" style="display:none; padding: 2%; border:1px white; margin-top: 20px;">
                <form id="return-form" method="POST" action="{{ route('process-return') }}">
                    @csrf
                    <input type="hidden" name="id" id="return-item-id">
                    <div class="form-group">
                        <label for="return-item-name">Item Name</label>
                        <input type="text" id="return-item-name" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="return-item-qty">Quantity</label>
                        <input type="number" id="return-item-qty" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                        <label for="return-reason">Reason for Return</label>
                        <textarea name="reason" id="return-reason" class="form-control" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Return</button>
                </form>
            </div>
        </div>
    </div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
    const returnFormContainer = document.getElementById('return-form-container');
    const returnForm = document.getElementById('return-form');
    const returnItemId = document.getElementById('return-item-id');
    const returnItemName = document.getElementById('return-item-name');
    const returnItemQty = document.getElementById('return-item-qty');
    const returnReason = document.getElementById('return-reason');

    // Fungsi untuk fetch data
    function fetchReturnData(itemId) {
        fetch(`/return-items/${itemId}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                // Populate form
                returnItemId.value = data.id;
                returnItemName.value = data.name;
                returnItemQty.value = data.qty;
                returnReason.value = '';

                // Tampilkan form
                returnFormContainer.style.display = 'block';
            })
            .catch(err => {
                console.error('Error fetching return data:', err);
                alert('Something went wrong. Please try again later.');
            });
    }

    // Tambahkan event listener ke setiap link
    document.querySelectorAll('a[href*="return-items"]').forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const itemId = this.getAttribute('href').split('/').pop();
            fetchReturnData(itemId);
        });
    });
});

</script>

