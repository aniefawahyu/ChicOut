@extends('layout.general')

@section('title')
    TastyPastries - {{$selectedItem->name}}
@endsection

@section('menu')

    <li class="@if ($selectedItem->Category->name == "Food") active @endif">
        <a href="{{ url('TastyPastries/Category/Food') }}">Food</a>
    </li>
    <li class="@if ($selectedItem->Category->name == "Drink") active @endif">
        <a href="{{ url('TastyPastries/Category/Drink') }}">Drink</a>
    </li>
    <li class="has-dropdown @if ($selectedItem->Category->name != "Food" && $selectedItem->Category->name != "Drink") active @endif">
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
            <li><a href="{{route("master-home")}}">Master</a></li>
        @else
            <li><a href="{{route("cart")}}">Cart</a></li>
            <li><a href="{{route("profile")}}">Profile</a></li>
        @endif
    @endauth
    @guest
        <li><a href="{{route("login")}}">Login</a></li>
    @endguest
@endsection


@section('content')

<div class="container fh5co-section">
    @auth
        @if (Auth::user()->role == "master")
        <div class="" style="margin-bottom: 2%">
            <a href="{{ url()->previous() }}" class="btn btn-primary"><i class="icon-back"></i></a>
        </div>
        @endif
    @endauth

    @if (Session::has("pesan"))
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{Session::get("pesan")}}',
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
        <div class="col-md-6" style="text-align: center">
            <img src="{{$selectedItem->img}}" class="img-thumbnail" alt="{{$selectedItem->name}}" style="width: 100%">
        </div>
        <div class="col-md-6"><br>
            <h2 class="custom-card-title">{{$selectedItem->name}}</h2>
            <div class="row">
                <div class="col-md-6">
                    @if ($selectedItem->discount != 0)
                    {{-- has discount --}}
                        <h3 class="custom-card-title">
                            <span>Rp {{ number_format(floor($selectedItem->price - ($selectedItem->price * $selectedItem->discount / 100)), 0, ',', '.') }}</span><br>
                            <small><span style="background-color: #d43f3a; color: white; border-radius: 10%; padding: 1% 1%">{{$selectedItem->discount}}%</span> <del>Rp {{ number_format($selectedItem->price, 0, ',', '.') }}</del></small>
                        </h3>
                    @else
                    {{-- no discount --}}
                        <h3 class="custom-card-title">Rp {{ number_format($selectedItem->price, 0, ',', '.') }}</h3>
                    @endif
                </div>
                <div class="col-md-6">
                    <p>
                        <span style="color: #ffcc00;">&#9733;</span>
                        {{ number_format($averageRate, 1) }}
                        ({{ count($itemReviews) }} rating)
                    </p>
                </div>
            </div>
            <hr>
            <p class="custom-card-text">{{$selectedItem->description}}</p>

            @if ($selectedItem->stock <= 0 || $selectedItem->trashed())
                We apologize, the item is currently out of stock or no longer available.
            @else
                <form method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <input class="form-control" type="number" min="1" max="{{$selectedItem->stock}}" name="qty" id="qty" value="1" style="color: black">
                            Stock : {{$selectedItem->stock}}
                        </div>
                        <div class="col-md-6">
                            <button class="btn" type="submit" name="addCart" style="color: black; background-color: greenyellow"><i class="icon-shopping-cart"></i></button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
    <div class="fh5co-section">
        <h3 class="custom-card-title">Reviews</h3>
        <hr>
        <form method="post">
            @csrf
            @if (count($itemReviews) != 0)
                @foreach ($itemReviews as $r)
                    <span style="color: white">
                        @if ($r->comment)
                            {{ $r->Account->display_name }} said, "{{ $r->comment }}"
                        @else
                            {{ $r->Account->display_name }} said nothing.
                        @endif
                    </span><br>
                    @for ($i = 0; $i < $r->rating; $i++)
                        <span style="color: #ffcc00; font-size: 24px;">&#9733;</span>
                    @endfor
                    <p>
                        {{ \Carbon\Carbon::parse($r->created_at)->format('d F Y - H:i') }}
                    </p>
                    @auth
                        @if (Auth::user()->username == $r->username)
                            <button type="submit" class="btn btn-danger" name="delete_comment" value="{{$r->ID_reviews}}"><i class="icon-trash2"></i></button>
                        @endif
                    @endauth
                    <hr>
                @endforeach
            @else
                <p style="color: white">This item has not been reviewed.</p>
            @endif

            @auth
                @if ($buyed && Auth::user()->role != "master")
                    <div class="rating">
                        @for ($i = 1; $i <= 5; $i++)
                            <input type="radio" name="rating" id="rating{{ $i }}" value="{{ $i }}" onclick="handleRating({{ $i }})">
                            <label for="rating{{ $i }}">&#9733;</label>
                        @endfor
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <input class="form-control" type="text" name="comment" placeholder="Type your comment...">
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-primary" type="submit" name="postComment"><i class="icon-edit2"></i></button>
                        </div>
                    </div>
                @endif
            @endauth
        </form>
    </div>
</div>
@endsection

@push('style')
<style>
    .rating input {
        display: none;
    }

    .rating label {
        cursor: pointer;
        font-size: 24px;
        color: #ccc;
    }

    .rating label.selected,
    .rating input:checked ~ label.selected {
        color: #ffcc00;
    }
</style>
@endpush
@push('script')
<script>
    function handleRating(rating) {
        console.log('User rated:', rating);
        $('label').removeClass('selected');
        for (let i = 1; i <= rating; i++) {
            $(`#rating${i}`).next('label').addClass('selected');
        }
    }
</script>
@endpush
