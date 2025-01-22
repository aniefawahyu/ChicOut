@extends('layout.crud')
@section('title')
    TastyPastries - Transaction Details
@endsection

@section('menu')
    <li>
        <a href="{{ route('master-home') }}">Home</a>
    </li>
    <li class="has-dropdown" style="color:rgba(255, 255, 255, 0.7)">
        <a href="{{ route('master-item', ['name' => 'All']) }}">Items</a>
        <ul class="dropdown">
            @foreach ($listCategory as $c)
                <li>
                    <a href="{{ route('master-item', ['name' => $c->name]) }}">{{ $c->name }}</a>
                </li>
            @endforeach
        </ul>
    </li>
    <li>
        <a href="{{ route('master-category') }}">Categories</a>
    </li>
    <li class="">
        <a href="{{ route('master-brand') }}">Brands</a>
    </li>
    <li>
        <a href="{{ route('master-payment') }}">Payments</a>
    </li>
    <li class="has-dropdown" style="color:rgba(255, 255, 255, 0.7)">
        <a href="{{ route('master-account', ['search'=>'All']) }}">Accounts</a>
        <ul class="dropdown">
            <li>
                <a href="{{ route('master-account', ['search'=>'Master']) }}">Master</a>
            </li>
            <li>
                <a href="{{ route('master-account', ['search'=>'User']) }}">User</a>
            </li>
        </ul>
    </li>
    <li>
        <a href="{{ route('master-profile') }}">Profile</a>
    </li>
@endsection


@section('content')
    <div class="container" style="margin-top: 15%">
        <div class="page">
            <a href="{{ url()->previous() }}" class="btn btn-primary"><i class="icon-back"></i></a>
            <div class="" style="margin-top: 2%">
                <h3 class="custom-card-title">{{$htrans->Account->display_name}}'s Transaction Details</h3>
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
