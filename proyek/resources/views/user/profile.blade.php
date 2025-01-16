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
    <li>
        <a href="{{ url('ChicOut/Category/Kids') }}">Kids</a>
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
    <div class="container">
        <div class="page">
            <br><br><br><br>
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


            <div class="custom-card-history">
                <h3 class="custom-card-title">Purchase History <br></h3>
                @if (count($listHtrans) <= 0)
                    <h4 class="custom-card-title">
                        It seems like you haven't made a purchase yet. Start shopping now!
                    </h4>
                @else
                    @foreach ($listHtrans as $h)
                        <a href="{{ route('dtrans', ['id' => $h->ID_htrans]) }}" style="color: white">
                            <div class="row" style="padding: 2% 0; background-color: #cda45e; text-align: center; border-bottom: 2px solid">
                                <div class="col-md-4">
                                    {{ \Carbon\Carbon::parse($h->purchase_date)->format('d F Y - H:i') }}
                                </div>
                                <div class="col-md-3">
                                    Rp {{ number_format($h->total, 0, ',', '.') }}
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

@push('style')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
@endpush
