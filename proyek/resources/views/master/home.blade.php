@extends("layout.crud")
@section('title')
ChicOut - Master
@endsection
@section('menu')
<li class="active">
    <a href="{{ route('master-home') }}">Home</a>
</li>
<li class="has-dropdown" style="color:rgba(255, 255, 255, 0.7)">
    <a href="{{ route('master-item', ['name' => 'All']) }}">Items</a>
    <ul class="dropdown">
        @foreach ($listCategory as $c)
            <li>
                <a href="{{ route('master-item', ['name' => $c->name]) }}">{{$c->name}}</a>
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
<li>
    <a href="{{ route('master-profile') }}">Profile</a>
</li>
@endsection

@section("content")
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
<div class="container" style="margin-top: 15%">
    <form action="" method="post">
        <div class="row">
            <div class="col-md-2">
                Start Date:
                <input type="date" name="startDate" id="" style="color: black" value="{{ old('startDate') }}">
            </div>
            <div class="col-md-2">
                End Date:
                <input type="date" name="endDate" id="" style="color: black" value="{{ old('endDate', now()->format('Y-m-d')) }}">
            </div>
            <div class="col-md-4">
                <br>
                <button type="submit" class="btn btn-primary" style="height: 100%"><i class='icon-search'></i></button>
            </div>
        </div>
        @csrf
    </form><br>
    <h2 style="color: white">Period : {{$period}} </h2>
    <div class="row" style="text-align: center; background-color:white; padding: 2% 0;">
        <div class="col-md-8" style="margin: 2% 0">
            <h3>Total Income per Day</h3>
            {!! $totalIncome->container() !!}
            <script src="{{ $totalIncome->cdn() }}"></script>
            {{ $totalIncome->script() }}
        </div>
        <div class="col-md-4">
            <h3>Top-Selling Items by Category</h3>
            {!! $topSellingCategory->container() !!}
            <script src="{{ $topSellingCategory->cdn() }}"></script>
            {{ $topSellingCategory->script() }}
        </div>
    </div><br>
    <h1 style="color: white">Transaction List</h1>
    <table class="table" style="background-color: #1a1814">
        <tr style="font-weight: bold; color: white; border-bottom: 2px solid">
            <th style="border-right: 2px solid; border-left: 2px solid; text-align: center">Name</th>
            <th style="border-right: 2px solid; text-align: center">Purchase Date</th>
            <th style="border-right: 2px solid; text-align: center">Total</th>
            <th style="border-right: 2px solid; text-align: center">Payment</th>
        </tr>
        @foreach ($htrans as $h)
        <tr class="text-center" style="color: white; border-bottom: 2px solid">
            <td style="border-right: 2px solid; border-left: 2px solid; vertical-align: middle">
                <a href="{{ route('master-dtrans', ['id'=>$h->ID_htrans]) }}" style="color: white">
                    {{$h->Account->display_name}}
                </a>
            </td>
            <td style="border-right: 2px solid; vertical-align: middle">
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
        </tr>
        @endforeach
    </table>
</div>

@endsection
