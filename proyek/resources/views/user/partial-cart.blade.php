@php
$total = 0; // Initialize the total variable
@endphp
@foreach ($listCart as $item)
<div class="">
<div class="row g-0 isi-cart p-5">
    <div class="col-md-2" style="padding-left: 0px !important; margin: auto 0">
        <img src="{{ $item->Item->img }}" class="img-thumbnail rounded-start" alt="...">
    </div>
    <div class="col-md-7">
        <a href="{{ route('detail', ['id' => $item->ID_items]) }}" class="">
            <h5 class="custom-card-title">{{ $item->Item->name }}</h5>
            <p>{{ implode(' ', array_slice(explode(' ', $item->Item->description), 0, 20)) }}..
            </p>
        </a>
        @if ($item->Item->discount != 0)
            {{-- has discount --}}
            <h3 class="custom-card-title">
                <span>Rp
                    {{ number_format(floor($item->Item->price - ($item->Item->price * $item->Item->discount) / 100), 0, ',', '.') }}</span><br>
                <small><span
                        style="background-color: #d43f3a; color: white; border-radius: 10%; padding: 1% 1%">{{ $item->Item->discount }}%</span>
                    <del>Rp {{ number_format($item->Item->price, 0, ',', '.') }}</del></small>
            </h3>
        @else
            {{-- no discount --}}
            <h3 class="custom-card-title">Rp
                {{ number_format($item->Item->price, 0, ',', '.') }}</h3>
        @endif
    </div>
    <div class="col-md-3" style="margin: auto 0">
        <div class="" style="margin: auto 0; display: flex">
            @if ($item->Item->discount != 0)
                @php
                    $subtotal = floor($item->Item->price - (($item->Item->price * $item->Item->discount) / 100)) * $item->qty;
                    $total += $subtotal; // Add the subtotal to the total
                @endphp
            @else
                {{-- no discount --}}
                @php
                    $subtotal = $item->Item->price * $item->qty;
                    $total += $subtotal; // Add the subtotal to the total
                @endphp
            @endif
            <div style="margin: 5px;">
                <p>Qty: <input type="number" min="1" data-idcart="{{ $item->ID_cart }}"
                        name="qty" class="qty" value="{{ $item->qty }}"
                        style="color: black; padding: 4.5px; border-radius: 5px">
                </p>
            </div>
            <div style="margin: auto 0">
                <button class="btn btn-danger" value="{{ $item->ID_cart }}" name="delete"><i
                        class="icon-trash2"></i></button>

            </div>
        </div>
        <div style="margin-left: 5px;">
            <p>
                Rp {{ number_format($subtotal, 0, ',', '.') }}
            </p>
        </div>
    </div>
</div>
</div>
@endforeach
<div class="row isi-cart" style="margin-bottom: 2%; padding-bottom: 2%;">
<div class="col-md-9"></div>
<div class="col-md-3">
    <h3 id="total" class="custom-card-title">Total: Rp {{ number_format($total, 0, ',', '.') }}</h3>
</div>
</div>
