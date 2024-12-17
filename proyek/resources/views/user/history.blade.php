@extends('layout.general')
@section('title')
    TastyPastries - {{ Auth::user()->display_name }}
@endsection

@section('menu')
    <li>
        <a href="{{ url('TastyPastries/Category/Food') }}">Food</a>
    </li>
    <li>
        <a href="{{ url('TastyPastries/Category/Drink') }}">Drink</a>
    </li>
    <li class="has-dropdown">
        <a>More</a>
        <ul class="dropdown">
            @foreach ($listMerch as $c)
                <li>
                    <a href="{{ url('TastyPastries/Category/' . $c['name']) }}">{{ $c['name'] }}</a>
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
            <div class="" style="margin-top: 2%">
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
                    <div class="col-md-4">
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
                    <div class="col-md-2">
                        <h3 class="custom-card-title">Subtotal</h3>
                    </div>
                </div>
                @foreach ($listDtrans as $d)
                    <div class="row" style="padding: 2% 0; color: white; text-align: center; display: flex; align-items: center; background-color: #cda45e; border-bottom: 2px solid">
                        <div class="col-md-2">
                            <a href="{{ route('detail', ['id' => $d->Item->ID_items]) }}">
                                <img src="{{ $d->Item->img }}" alt="" class="img-thumbnail">
                            </a>
                        </div>
                        <div class="col-md-4">
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
                        <div class="col-md-2">
                            <p>Rp {{ number_format($d->subtotal, 0, ',', '.') }}</p>
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
        </div>
    </div>
@endsection
