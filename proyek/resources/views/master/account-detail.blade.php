@extends('layout.crud')
@section('title')
    TastyPastries - Accounts
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
    <div class="" id="itemList" style="margin-top: 15%">
        <table class="table" style="background-color: #1a1814">
            <tr style="font-weight: bold; color: white; border-bottom: 2px solid">
                <th style="border-right: 2px solid; border-left: 2px solid; text-align: center">Username</th>
                <th style="border-right: 2px solid; text-align: center">Display Name</th>
                <th style="border-right: 2px solid; text-align: center">Email</th>
                <th style="border-right: 2px solid; text-align: center">Tel.</th>
                <th style="border-right: 2px solid; text-align: center">Action</th>
            </tr>
            @foreach ($listAccount as $i)
                <tr class="text-center" style="color: white; border-bottom: 2px solid">
                    <td style="border-right: 2px solid; border-left: 2px solid; vertical-align: middle">
                        {{ $i->username }}
                    </td>
                    <td style="border-right: 2px solid; border-left: 2px solid; vertical-align: middle">
                        {{ $i->display_name }}
                    </td>
                    <td style="border-right: 2px solid; border-left: 2px solid; vertical-align: middle">
                        {{ $i->email }}
                    </td>
                    <td style="border-right: 2px solid; border-left: 2px solid; vertical-align: middle">
                        {{ $i->tel }}
                    </td>
                    <td style="border-right: 2px solid; border-left: 2px solid; vertical-align: middle">
                        @if ($i->role == "master")
                        <a class="btn btn-warning" href="{{ route('master-account-role', ['username' => $i->username]) }}">
                            Change to User
                        </a>
                        @else
                            <a title="View Detail" class="btn btn-primary" href="{{ route('master-account', ['search' => $i->username]) }}">
                                <i class="icon-eye"></i>
                            </a>
                            <a class="btn btn-success" href="{{ route('master-account-role', ['username' => $i->username]) }}">
                                Change to Master
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection

