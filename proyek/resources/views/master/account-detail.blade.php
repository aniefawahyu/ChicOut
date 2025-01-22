@extends('layout.crud')
@section('title')
    Chicout - Accounts
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
    <li class="has-dropdown active" style="color:rgba(255, 255, 255, 0.7)">
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
    @if (Session::has('sukses'))
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Yay...',
                text: '{{ Session::get('sukses') }}',
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
    @endif
    <div class="box" style="margin-top: 15%; margin-bottom: 2%;">
        <div class="row">
            <div class="col-md-10">
                <h3 class="custom-card-title">Account Information</h3>
                <p class="custom-card-title">
                    Username : {{ $acc->username }}
                </p>
                <p class="custom-card-title">
                    Display Name : {{ $acc->display_name }}
                </p>
                <p class="custom-card-title">
                    Email : {{ $acc->email }}
                </p>
                <p class="custom-card-title">
                    Tel : {{ $acc->tel }}
                </p>
                <p class="custom-card-title">
                    Address : {{ $acc->address }}
                </p>
            </div>
            <div class="col-md-2" style="text-align: end">
                <a href="{{ route('master-account', ['search' => 'All'])}}" class="btn btn-primary"><i class="icon-back"></i></a>
            </div>
        </div>
    </div>
    <h1 style="color: white">Transaction History</h1>
    <table class="table" style="background-color: #1a1814">
        <tr style="font-weight: bold; color: white;">
            <th style="border-right: 2px solid; border-left: 2px solid; text-align: center">Purchase Date</th>
            <th style="border-right: 2px solid; text-align: center">Total</th>
            <th style="border-right: 2px solid; text-align: center">Payment</th>
            <th style="border-right: 2px solid; text-align: center">Sent To</th>
        </tr>
        @foreach ($htrans as $h)
        <tr class="text-center" style="color: white; border-bottom: 2px solid">
            <td style="border-right: 2px solid; border-left: 2px solid; vertical-align: middle">
                <a href="{{ route('master-dtrans', ['id'=>$h->ID_htrans]) }}" style="color: white">
                    {{ \Carbon\Carbon::parse($h->purchase_date)->format('d F Y - H:i') }}
                </a>
            </td>
            <td style="border-right: 2px solid; vertical-align: middle">
                <a href="{{ route('master-dtrans', ['id'=>$h->ID_htrans]) }}" style="color: white">
                    Rp {{ number_format($h->total, 0, ',', '.') }}
                </a>
            </td>
            <td style="border-right: 2px solid; vertical-align: middle">
                <a href="{{ route('master-dtrans', ['id'=>$h->ID_htrans]) }}" style="color: white">
                    {{$h->Payment->name}}
                </a>
            </td>
            <td style="border-right: 2px solid; vertical-align: middle">
                <a href="{{ route('master-dtrans', ['id'=>$h->ID_htrans]) }}" style="color: white">
                    {{ implode(' ', array_slice(explode(' ', $h->address), 0, 5)) }}...
                </a>
            </td>
        </tr>
        @endforeach
    </table>
@endsection

