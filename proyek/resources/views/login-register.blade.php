@extends('layout.pagelogin')

@section('title')
    Chic Out - SignIn SignUp
@endsection

@section('isi')
    <div class="fh5co-loader-wrapper">
        <div class="fh5co-loader"></div>
    </div>

    <div class="section-body">
        <div class="main">

            <input type="checkbox" id="chk" aria-hidden="true">
            @if (Session::has('pesan'))
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    // Menambahkan script JavaScript untuk memunculkan SweetAlert
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: '{{ Session::get('pesan') }}',
                        customClass: {
                            confirmButton: 'btn btn-danger',
                            container: 'my-swal'
                        }
                    });
                </script>
                <style>
                    /* Menambahkan style untuk mengatur margin tombol OK */
                    .my-swal .swal2-confirm {
                        margin: 1em;
                    }
                </style>
            @endif

            @if (Session::has('sukses'))
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    // Menambahkan script JavaScript untuk memunculkan SweetAlert
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
                    /* Menambahkan style untuk mengatur margin tombol OK */
                    .my-swal .swal2-confirm {
                        margin: 1em;
                    }
                </style>
            @endif

            @if ($errors->any())
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                <script>
                    // Menambahkan script JavaScript untuk memunculkan SweetAlert
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
                    /*style untuk mengatur margin tombol OK */
                    .my-swal .swal2-confirm {
                        margin: 1.25em;
                    }
                </style>
            @endif
            <div class="signup">
                <form method="post">
                    @csrf
                    <label for="chk" aria-hidden="true">Sign up</label>
                    <input type="text" name="username" placeholder="Username" value="{{ old('username') }}">
                    <input type="text" name="display_name" placeholder="Display Name" value="{{ old('display_name') }}">
                    <input type="text" name="email" placeholder="Email" value="{{ old('email') }}">
                    <input type="password" name="password" placeholder="Password">
                    <input type="password" name="confirmPassword" placeholder="Confirm Password">
                    <input type="text" name="tel" placeholder="Nomor Telepon" value="{{ old('tel') }}">
                    <input type="text" name="address" placeholder="Address" value="{{ old('address') }}">
                    <button type="submit" name="register" class="btn btn-success">Register</button>
                </form>
            </div>
            <div class="login">
                <form method="post">
                    @csrf
                    <label for="chk" aria-hidden="true">Sign In</label>
                    <input type="text" name="loginUsername" placeholder="Username"
                        value="{{ old('loginUsername') }}">
                    <input type="password" name="loginPassword" placeholder="Password">
                    <button type="submit" name="login" class="btn btn-login">Login</button>
                </form>
            </div>
        </div>
    </div>

@endsection
