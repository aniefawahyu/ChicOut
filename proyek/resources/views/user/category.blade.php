@extends('layout.general')

@section('title')
ChicOut
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


{{-- menu selain kategori --}}
@auth
@if (auth()->user()->role === 'master')
<li><a href="{{ route('master-home') }}">Master</a></li>
@else
<li><a href="{{ route('cart') }}">Cart</a></li>
<li><a href="{{ route('profile') }}">Profile</a></li>
@endif
@endauth
@guest
<li><a href="{{ route('login') }}">Login</a></li>
@endguest
@endsection


@section('content')
<div id="page">
    <header id="fh5co-header" class="fh5co-cover js-fullheight" role="banner"
        {{-- gambar pake dari db jadi cari link url --}}
        style="background-image: url({{ $selectedCategory->img }});"
        data-stellar-background-ratio="0.5">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <div class="display-t js-fullheight">
                        <div class="display-tc js-fullheight animate-box" data-animate-effect="fadeIn">
                            <h1>{{ $selectedCategory->name }}</h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="container mt-4">
        <div class="row">
            @foreach ($listItem as $item)
            <a href="{{ route('detail', ['id' => $item->ID_items]) }}">
                <div class="col-md-4">
                    <div class="custom-card box" data-aos="zoom-in" data-aos-delay="200">
                        <img src="{{ $item->img }}" class="custom-card-img img-thumbnail"
                            alt="{{ $item->name }}">
                        <div class="custom-card-body ">
                            <h3 class="custom-card-title">{{ $item->name }}</h3>
                            <p class="custom-card-text">
                                {{ implode(' ', array_slice(explode(' ', $item->description), 0, 20))}}...
                            </p>
                            @if ($item->discount != 0)
                            @php
                            $priceDiscount = floor(($item->price - ($item->price * $item->discount) / 100));
                            @endphp

                            <h3 class="custom-card-title">
                                <span>Rp {{ number_format($priceDiscount, 0, ',', '.') }}</span><br>
                                <small>
                                    <span style="background-color: #d43f3a; color: white; border-radius: 10%; padding: 1% 1%">{{$item->discount}}%</span> <del>Rp {{ number_format($item->price, 0, ',', '.') }}</del>
                                </small>
                            </h3>
                            @else
                            <h3 class="custom-card-title">Rp {{ number_format($item->price, 0, ',', '.') }}</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>


    <div class="container mt-4">
        <h1 style="color: white; margin-top: 60px;">Collaboration</h1>
        <div class="row">
            @foreach ($listCollaborationItem as $item)
            <a href="{{ route('collaboration-detail', ['id' => $item->ID_items, 'categoryId'=> $item->ID_categories]) }}">
                <div class="col-md-4">
                    <div class="custom-card box" data-aos="zoom-in" data-aos-delay="200">
                        <img src="{{ $item->img }}" class="custom-card-img img-thumbnail"
                            alt="{{ $item->name }}">
                        <div class="custom-card-body ">
                            <h3 class="custom-card-title">{{ $item->name }}</h3>
                            <p class="custom-card-text">
                                {{ implode(' ', array_slice(explode(' ', $item->description), 0, 20))}}...
                            </p>
                            @if ($item->discount != 0)
                            @php
                            $priceDiscount = floor(($item->price - ($item->price * $item->discount) / 100));
                            @endphp

                            <h3 class="custom-card-title">
                                <span>Rp {{ number_format($priceDiscount, 0, ',', '.') }}</span><br>
                                <small>
                                    <span style="background-color: #d43f3a; color: white; border-radius: 10%; padding: 1% 1%">{{$item->discount}}%</span> <del>Rp {{ number_format($item->price, 0, ',', '.') }}</del>
                                </small>
                            </h3>
                            @else
                            <h3 class="custom-card-title">Rp {{ number_format($item->price, 0, ',', '.') }}</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>
<div style="margin-top: 20px;"></div>
@endsection