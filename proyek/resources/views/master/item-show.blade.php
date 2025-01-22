@extends('layout.crud')
@section('title')
    ChicOut - Items
@endsection
@section('menu')
    <li>
        <a href="{{ route('master-home') }}">Home</a>
    </li>
    <li class="has-dropdown active" style="color:rgba(255, 255, 255, 0.7)">
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
    <li class="">
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
    @if (Session::has('fail'))
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '{{ Session::get('fail') }}',
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
    <div class="" id="itemList" style="margin-top: 15%">
        <div class="row" style="margin-bottom: 2%">
            <div class="col-md-6">
                <a class="btn btn-success" href="{{ route('master-insert-update', ['id' => 'Insert']) }}"  title="Insert New Item">
                    <i class="icon-add-to-list"></i>
                </a>
            </div>
            <div class="col-md-6" style="text-align: end">
                <a class="btn btn-success" href="{{route('master-report-item')}}" title="Download List All Items">
                    <i class="icon-download"></i>
                </a>
            </div>
        </div>

        <table class="table" style="background-color: #1a1814">
            <tr style="font-weight: bold; color: white; border-bottom: 2px solid">
                <th style="border-right: 2px solid; border-left: 2px solid; text-align: center">No</th>
                <th style="border-right: 2px solid; text-align: center">Name</th>
                <th style="border-right: 2px solid; text-align: center">Image</th>
                <th style="border-right: 2px solid; text-align: center">Description</th>
                <th style="border-right: 2px solid; text-align: center">Stock</th>
                <th style="border-right: 2px solid; text-align: center">Price</th>
                <th style="border-right: 2px solid; text-align: center">Discount</th>
                <th style="border-right: 2px solid; text-align: center">Category</th>
                <th style="border-right: 2px solid; text-align: center">Action</th>
            </tr>
            @foreach ($listItem as $i)
                <tr class="text-center" style="color: white; border-bottom: 2px solid">
                    <td style="border-right: 2px solid; border-left: 2px solid; vertical-align: middle">{{ $i->ID_items }}</td>
                    <td style="border-right: 2px solid; vertical-align: middle">{{ $i->name }}</td>
                    <td style="border-right: 2px solid; vertical-align: middle">
                        <a href="{{ route('detail', ['id' => $i->ID_items]) }}">
                            <img class="thumbnail" src="{{ $i->img }}" alt="{{ $i->name }}" width="100px">
                        </a>
                    </td>
                    <td style="border-right: 2px solid; vertical-align: middle">
                        {{ implode(' ', array_slice(explode(' ', $i->description), 0, 5)) . '...' }}
                    </td>
                    <td style="border-right: 2px solid; vertical-align: middle">{{ $i->stock }}</td>
                    <td style="border-right: 2px solid; vertical-align: middle; margin:5px">
                        <div style="display: flex; gap: 3px; text-align: center">
                            <p style="margin: auto 0">Rp </p>
                            <p style="margin: auto 0">{{ number_format($i->price, 0, ',', '.') }}</p>
                        </div>
                    </td>
                    <td style="border-right: 2px solid; vertical-align: middle">
                        @if ($i->discount == 0)
                            -
                        @else
                            {{ $i->discount }}%
                        @endif
                    </td>
                    <td style="border-right: 2px solid; vertical-align: middle">{{ $i->Category->name }}</td>
                    <td style="vertical-align: middle; border-right: 2px solid; ">
                        @if ($i->trashed())
                            <a class="btn btn-warning"
                                href="{{ route('master-delete-recover', ['id' => $i->ID_items]) }}"><i
                                    class="icon-folder2"></i></a>
                        @else
                            <div style="display: flex; flex-direction: column; gap: 7px">
                                <a class="btn btn-primary"
                                    href="{{ route('master-insert-update', ['id' => $i->ID_items]) }}"><i
                                        class="icon-pencil"></i></a>
                                <a class="btn btn-danger"
                                    href="{{ route('master-delete-recover', ['id' => $i->ID_items]) }}"><i
                                        class="icon-trash2"></i></a>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </div>
    <div class="pagination-container">
        {{ $listItem->appends(request()->query())->links() }}
    </div>
    <style>
        .pagination-container {
            width: 100%;
            margin-top: 30px;
            margin-bottom: 30px;
            display: flex;
            justify-content: space-between;
        }

        .pagination {
            /* display: flex; */
            list-style: none;
            padding: 0;
            margin: 0;
            gap: 5px;
            justify-content: space-between;
        }

        .page-item .page-link {
            background-color: #1a1814;
            border: 1px solid #ffffff;
            color: #ffffff;
            padding: 8px 16px;
            text-decoration: none;
            transition: all 0.3s ease;
            min-width: 40px;
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 38px;
        }

        .page-item.active .page-link {
            background-color: #ffffff;
            color: #1a1814;
            border-color: #ffffff;
        }

        .page-item.disabled .page-link {
            background-color: #1a1814;
            color: #6c757d;
            border-color: #6c757d;
            cursor: not-allowed;
        }

        .page-link:hover:not(.disabled) {
            background-color: #ffffff;
            color: #1a1814;
            border-color: #ffffff;
        }

        .page-item:first-child .page-link,
        .page-item:last-child .page-link {
            padding: 8px 12px;
        }

        /* Responsive styling */
        @media (max-width: 768px) {
            .page-link {
                padding: 6px 12px;
                min-width: 35px;
                height: 35px;
            }
            
            .pagination {
                gap: 3px;
            }
        }
    </style> 
    @endsection
