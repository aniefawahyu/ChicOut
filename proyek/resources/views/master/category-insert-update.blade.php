@extends("layout.crud")
@section('title')
    TastyPastries -
    @if ($category != null)
        {{$category->name}}
    @else
        Add Category
    @endif
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
                <a href="{{ route('master-item', ['name' => $c->name]) }}">{{$c->name}}</a>
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
        @if ($category != null)
            <h1 style="color: white">Update {{$category->name}}</h1>
        @else
            <h1 style="color: white">Insert New Category</h1>
        @endif
        <div class="row">
            <div class="col-md-6">
                <form method="POST">
                    @csrf
                    @if ($category != null)
                        Name: <input type="text" name="name" id="name" class="form-control" value="{{$category->name}}" @if ($category->ID_categories <= 2) readonly @endif>
                        Image: <input type="text" name="img" id="img" class="form-control" value="{{$category->img}}" placeholder="URL"><br>
                        <button type="submit" name="save" class="btn btn-success" value="{{$category->ID_categories}}"><i class="icon-save"></i></button>
                    @else
                        Name: <input type="text" name="name" id="name" class="form-control" value="{{ old('name')}}">
                        Image: <input type="text" name="img" id="img" class="form-control" value="{{ old('img')}}" placeholder="URL"><br>
                        <button type="submit" name="add" class="btn btn-success"><i class="icon-save"></i></button>
                    @endif
                </form>
            </div>
            Preview Image:
            <div class="col-md-6" style="background-color: white">
                @if ($category != null)
                    <img id="newImage" src="{{$category->img}}" alt="" style="width: 100%">
                @else
                    <img id="newImage" src="{{ old('img')}}" alt="" style="width: 100%">
                @endif
            </div>
        </div>
    </div>
@endsection

@push('script')
<script>
    $(document).ready(function () {
        $('#img').on('change', function () {
            updateImageSrc();
        });

        function updateImageSrc() {
            var newSrc = $('#img').val();
            $('#newImage').attr('src', newSrc);
        }
    });
</script>
@endpush

