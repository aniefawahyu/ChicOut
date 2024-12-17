@extends('layout.general')
@section('title')
    TastyPastries - {{ Auth::user()->display_name }}
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
<li class="active">
    <a href="{{ route('master-profile') }}">Profile</a>
</li>
@endsection


@section('content')
    <div class="container" style="margin-top: 10%">
        <div class="page">
            <a href="{{ route('logout') }}" class="btn btn-danger"><i class="icon-log-out"></i></a>
            @if ($errors->any())
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '{{ $errors->first() }}',
                        customClass: {
                            confirmButton: 'btn btn-danger',
                            container: 'my-swal'
                        }
                    });
                </script>
                <style>
                    .my-swal .swal2-confirm {
                        margin: 1.25em;
                    }
                </style>
            @endif
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

            <div class="row">
                <div class="col-md-6">
                    <form method="post">
                        @csrf
                        <form method="post">
                            @csrf
                            <div class="mb-3">
                                <label for="username" class="form-label">Username:</label>
                                <input type="text" name="username" class="form-control"
                                    value="{{ Auth::user()->username }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="display_name" class="form-label">Display Name:</label>
                                <input type="text" name="display_name" class="form-control"
                                    value="{{ Auth::user()->display_name }}">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="text" name="email" class="form-control"
                                    value="{{ Auth::user()->email }}">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">Current Password:</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">New Password:</label>
                                <input type="password" name="newPassword" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="tel" class="form-label">Tel:</label>
                                <input type="text" name="tel" class="form-control" value="{{ Auth::user()->tel }}">
                            </div>
                            <div class="mb-3">
                                <label for="address" class="form-label">Address:</label>
                                <input type="text" name="address" class="form-control"
                                    value="{{ Auth::user()->address }}">
                            </div>
                            <br>
                            <button type="submit" class="btn btn-success"><i class="icon-save"></i></button>
                            <br><br>
                        </form>
                    </form>
                </div>
                <!-- Kolom Pertama: Profile Card -->
                <div class="col-md-6">
                    <div class="custom-card-history">
                        <div class="card-profile-kiri">
                            <img src="{{asset('image/profile.svg')}}">
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@push('style')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
@endpush
