@extends('admin.layout')

@section('main')
<div class="content-wrapper">

<div class="container py-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h3 class="mb-3">Add Skills</h3>
            <div class="card">
                <div class="card-body p-5">

                    <form method="POST" action="{{ route('products.index') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Category</label>
                            <select class="form-control" name="categories_id">
                                @foreach($catogroys as $catogroy)
                                <option value="{{ $catogroy->id }}">{{ $catogroy->name }}</option>
                            @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Name Skill</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Image</label>
                            <input type="file" name="image_catogory" class="form-control-file" required>
                        </div>

                        @if(session('existing_product'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('existing_product') }}
                            </div>
                        @endif

                        <div class="text-center mt-5">
                            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </form>
                    <script>
                        // JavaScript to check if file input is empty
                        document.querySelector('form').onsubmit = function() {
                            var fileInput = document.querySelector('input[type=file]');
                            if (!fileInput.files.length) {
                                alert('Please select an image.');
                                return false; // Prevent form submission
                            }
                        };
                    </script>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@endsection
