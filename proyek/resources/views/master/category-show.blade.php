@extends('layout.crud')
@section('title')
    ChicOut - Categories
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
    <li class="active">
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
        <div>
            <a class="btn btn-success" href="{{ route('master-category-cru', ['id' => 'Insert']) }}" title="Insert New Category"><i class="icon-add-to-list"></i></a>
        </div> <br>
        <table class="table" style="background-color: #1a1814">
            <tr style="font-weight: bold; color: white; border-bottom: 2px solid">
                <th style="border-right: 2px solid; border-left: 2px solid; text-align: center">No</th>
                <th style="border-right: 2px solid; text-align: center">Name</th>
                <th style="border-right: 2px solid; text-align: center">Image</th>
                <th style="border-right: 2px solid; text-align: center">Action</th>
            </tr>
            @foreach ($listCategoryWithTrashed as $i)
                <tr class="text-center" style="color: white; border-bottom: 2px solid">
                    <td style="border-right: 2px solid; border-left: 2px solid; vertical-align: middle">
                        {{ $i->ID_categories }}</td>
                    <td style="border-right: 2px solid; border-left: 2px solid; vertical-align: middle">{{ $i->name }}
                    </td>
                    <td style="border-right: 2px solid; border-left: 2px solid; vertical-align: middle"><img
                            src="{{ $i->img }}" alt="{{ $i->name }}" width="100px"></td>
                    <td style="border-right: 2px solid; border-left: 2px solid; vertical-align: middle">
                        @if ($i->trashed())
                            <a class="btn btn-warning"
                                href="{{ route('master-delete-category', ['id' => $i->ID_categories]) }}" title="Restore {{$i->name}}"><i
                                class="icon-folder2"></i></a>
                        @else
                            @if ($i->ID_categories > 2)
                                <a class="btn btn-primary"
                                    href="{{ route('master-category-cru', ['id' => $i->ID_categories]) }}" title="Update {{$i->name}}"><i
                                    class="icon-pencil"></i></a>
                                {{-- <a class="btn btn-danger"
                                    href="{{ route('master-delete-category', ['id' => $i->ID_categories]) }}" title="Delete {{$i->name}}"><i
                                    class="icon-trash2"></i></a> --}}
                            @else
                                <div>
                                    <a style="margin: auto 0" class="btn btn-primary"
                                        href="{{ route('master-category-cru', ['id' => $i->ID_categories]) }}" title="Update {{$i->name}}"><i
                                        class="icon-pencil"></i></a>
                                </div>
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
@endsection
