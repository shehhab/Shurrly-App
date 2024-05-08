@extends('admin.layout')

@section('main')
<div class="content-wrapper">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <h3 class="mb-3">Edit Skills</h3>
                <div class="card">
                    <div class="card-body p-5">
                        <label for="name">Skill Name:</label>
                        <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <input type="text" id="name" name="name" value="{{ $category->name }}" class="form-control" style="height: 50px;">
                            <br>
                            <label for="image_catogory">Skill Image:</label>
                            <input type="file" id="image_catogory" name="image_catogory" class="form-control-file">
                            <br>
                            @if($category->getFirstMedia('image_catogory'))
                            <img src="{{ $category->getFirstMedia('image_catogory')->getUrl() }}" alt="Skill Image" style="max-width: 200px;">
                            @else
                            <p>No image found</p>
                            @endif
                            <br>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
