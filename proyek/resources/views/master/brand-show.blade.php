@extends("layout.crud")
@section('title')
ChicOut - Brands
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
<li class="active">
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
@if (Session::has('sukses'))
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'success',
        title: 'Yay...',
        text: '{{ Session::get('
        sukses ') }}',
        customClass: {
            confirmButton: 'btn btn-success',
            container: 'my-swal'
        }
    });
</script>
@endif

<div id="itemList" style="margin-top: 15%">
    <div>
        <a class="btn btn-success" href="{{ route('master-brand-cru', ['id' => 'Insert']) }}" title="Insert New Brand">
            <i class="icon-add-to-list"></i>
        </a>
    </div>
    <br>
    <table class="table" style="background-color: #1a1814">
        <tr style="font-weight: bold; color: white; border-bottom: 2px solid">
            <th style="border-right: 2px solid; border-left: 2px solid; text-align: center">ID</th>
            <th style="border-right: 2px solid; text-align: center">Name</th>
            <th style="border-right: 2px solid; text-align: center">Logo</th>
            <th style="border-right: 2px solid; text-align: center">Description</th>
            <th style="border-right: 2px solid; text-align: center">Premium</th>
            <th style="border-right: 2px solid; text-align: center">Action</th>
        </tr>
        @foreach ($listBrandWithTrashed as $b)
        <tr class="text-center" style="color: white; border-bottom: 2px solid">
            <td style="border-right: 2px solid; border-left: 2px solid; vertical-align: middle">{{ $b->ID_brands }}</td>
            <td style="border-right: 2px solid; vertical-align: middle">{{ $b->name }}</td>
            <td style="border-right: 2px solid; vertical-align: middle"><img src="{{ $b->logo }}" alt="{{ $b->name }}" width="100px"></td>
            <td style="border-right: 2px solid; vertical-align: middle">{{ $b->description }}</td>
            <td style="border-right: 2px solid; vertical-align: middle">
                @if ($b->premium)
                <i class="icon-check"></i>
                @else
                <i class=" icon-cross"></i>
                @endif
            </td>
            <td style=" border-right: 2px solid; vertical-align: middle">
                @if ($b->trashed())
                <a class="btn btn-warning" href="{{ route('master-delete-brand', ['id' => $b->ID_brands]) }}" title="Restore {{ $b->name }}">
                    <i class="icon-folder2"></i>
                </a>
                @else
                <a class="btn btn-primary" href="{{ route('master-brand-cru', ['id' => $b->ID_brands]) }}" title="Update {{ $b->name }}">
                    <i class="icon-pencil"></i>
                </a>
                <a class="btn btn-danger" href="{{ route('master-delete-brand', ['id' => $b->ID_brands]) }}" title="Delete {{ $b->name }}">
                    <i class="icon-trash2"></i>
                </a>
                @endif
            </td>
        </tr>
        @endforeach
    </table>
</div>
@endsection