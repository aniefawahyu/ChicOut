@extends("layout.crud")
@section('title')
    TastyPastries -
    @if ($item != null)
        {{$item->name}}
    @else
        Add Item
    @endif
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
                <a href="{{ route('master-item', ['name' => $c->name]) }}">{{$c->name}}</a>
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
        <h1 style="color: white">
            @if ($item != null)
                Update {{$item->name}}
            @else
                Insert New Item
            @endif
        </h1>
        <div class="row mb-5">
            <div class="col-md-6">
                <form method="POST">
                    @csrf
                    @if ($item != null)
                        {{-- update --}}
                        Name: <input type="text" name="name" id="name" class="form-control" value="{{$item->name}}">
                        Image: <input type="text" name="img" id="img" class="form-control" value="{{$item->img}}" placeholder="URL">
                        Description: <input type="text" name="description" id="description" class="form-control" value="{{$item->description}}">
                        Stock: <input type="text" name="stock" id="stock" class="form-control" value="{{$item->stock}}">
                        Price: <input type="text" name="price" id="price" class="form-control" value="{{$item->price}}">
                        Discount (%): <input type="text" name="discount" id="discount" class="form-control" value="{{$item->discount}}" placeholder="0 - 100">
                        Category:
                        <select id="ID_categories" name="ID_categories" class="form-control">
                            @foreach ($listCategory as $category)
                                @if ($item->ID_categories == $category->ID_categories)
                                    <option value="{{$category->ID_categories}}" selected>{{$category->name}}</option>
                                @else
                                    <option value="{{$category->ID_categories}}">{{$category->name}}</option>
                                @endif
                            @endforeach
                        </select>
                        <br>
                        <button type="submit" name="save" class="btn btn-success" value="{{$item->ID_items}}"><i class="icon-save"></i></button>
                    @else
                        Name: <input type="text" name="name" id="name" class="form-control" value="{{ old('name')}}">
                        Image: <input type="text" name="img" id="img" class="form-control" value="{{ old('img')}}" placeholder="URL">
                        Description: <input type="text" name="description" id="description" class="form-control" value="{{ old('description')}}">
                        Stock: <input type="text" name="stock" id="stock" class="form-control" value="{{ old('stock')}}">
                        Price: <input type="text" name="price" id="price" class="form-control" value="{{ old('price')}}">
                        Discount (%): <input type="text" name="discount" id="discount" class="form-control" value="{{ old('discount')}}" placeholder="0 - 100">
                        Category:
                        <select id="ID_categories" name="ID_categories" class="form-control">
                            @foreach ($listCategory as $category)
                                <option @if (old('ID_categories') == $category->ID_categories) selected @endif value="{{$category->ID_categories}}">{{$category->name}}</option>
                            @endforeach
                        </select><br>
                        <button type="submit" name="add" class="btn btn-success"><i class="icon-save"></i></button>
                    @endif
                </form>
            </div>
            Preview Image:
            <div class="col-md-6" style="background-color: white">
                @if ($item != null)
                    <img id="newImage" src="{{$item->img}}" alt="" style="width: 100%">
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
