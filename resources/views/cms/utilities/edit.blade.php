@extends('layouts.cms')
 
@section('title', $title)

@section('description', $description)

@section('css')
    @parent
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('content')
<nav aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-light">
        <li class="breadcrumb-item"><a href="{{ route('cms.dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('cms.'.$routePrefix . '.index', ['type' => $model->type]) }}">List of {{ $title }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">Update Utility</li>
    </ol>
</nav>
<div class="card card-default">
    <div class="card-body text-center">
        <h3 class="card-title">Update @yield('title')</h3>
        <p class="card-text pb-4 pt-1">
            @yield('description')
        </p>
    </div>
</div>
<div class="card card-default">
    <div class="card-footer card-profile-footer">
        <ul class="nav nav-border-top justify-content-center">
            <li class="nav-item">
                <a class="nav-link" href="{{ route('cms.'.$routePrefix . '.show', $model) }}">Data</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="{{ route('cms.'.$routePrefix . '.edit', $model) }}">Form</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="{{ route('cms.'.$routePrefix . '.historical-changes', $model) }}">Historical Changes</a>
            </li>
        </ul>
    </div>
    <div class="card-header">
        <h2>Utility</h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('cms.'.$routePrefix . '.update', $model->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <br>
                {{ $model->name }}
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <br>
                {{ ucfirst($model->type) }}
            </div>
            <div class="form-group">
                <label for="form_type">Form Type</label>
                <br>
                {{ ucfirst($model->form_type) }}
                <input class="form-control" hidden id="form_type" name="form_type" value="{{ old('title', $model->form_type) }}">
            </div>
            <div class="form-group">
                <label for="title">Title @include('cms._include.required')</label>
                <input class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Title" value="{{ old('title', $model->title) }}">
                @error('title')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                <label for="description">Description @include('cms._include.required')</label>
                <input class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Description" value="{{ old('description', $model->description) }}">
                @error('description')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
            <div class="form-group">
                {{-- Label --}}
                <label for="value">Value @include('cms._include.required')</label>

                {{-- Value Form --}}
                @if ($model->form_type == "image")
                    <div class="accordion" id="accordionImage">
                        <div class="card border-0">
                            <div class="card-header" id="headingImage">
                                <h2 class="mb-0">
                                    <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseImage" aria-expanded="false" aria-controls="collapseImage">
                                        <i class="mdi mdi-cursor-default-click"></i>
                                        Click Here to Change The Image
                                    </button>
                                </h2>
                            </div>
                            <div id="collapseImage" class="collapse {{ old('value') === null ? '' : 'show' }}" aria-labelledby="headingImage" data-parent="#accordionImage">
                                <input type="file" class="form-control @error('value') is-invalid @enderror" id="value" name="value" placeholder="Image" value="{{ old('value') }}" accept="image/*">
                            </div>
                        </div>
                    </div>

                    @error('value')
                        <small class="mb-0 text-danger">{{ $message }}</small>
                        <br>
                    @enderror

                    @if ($image = $model->getFirstMedia(Str::plural('value')))
                        <img id="image-preview" height="150px" src="{{ $image->getUrl() }}" alt="Uploaded image" class="mt-3"/>
                    @else
                        <img id="image-preview" height="150px" src="#" alt="Uploaded image" class="mt-3" style="display:none;"/>
                    @endif
                @elseif ($model->form_type == "text")
                    <input class="form-control @error('value') is-invalid @enderror" id="value" name="value" placeholder="Value" value="{{ old('value', $model->value) }}">
                    @error('value')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                @elseif ($model->form_type == "textarea")
                    <textarea id="value" class="form-control @error('value') is-invalid @enderror" name="value">{{ old('value', $model->value) }}</textarea>
                    @error('value')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                @elseif ($model->form_type == "wysiwyg")
                    <textarea id="value" class="wysiwyg form-control @error('value') is-invalid @enderror" name="value">{{ old('value', $model->value) }}</textarea>
                    @error('value')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                @elseif ($model->form_type == "switch")
                    <label class="switch switch-text switch-primary switch-pill form-control-label mr-2">
                        <input id="value" name="value" type="checkbox" class="switch-input form-check-input" value="on" {{ $model->value == "on" ? 'checked' : ''}}>
                        <span class="switch-label" data-on="Yes" data-off="No"></span>
                        <span class="switch-handle"></span>
                    </label>
                @endif
            </div>
            <br />
            @include('cms._include.buttons.back', ['backUrl' => route('cms.'.$routePrefix . '.index', ['type' => $model->type])])
            @include('cms._include.buttons.save')
        </form>
    </div>
</div>
@endsection

@section('js')
    @parent
    @include('cms._include.tinymce')
    <script>
        value.onchange = evt => {
            preview = document.getElementById('image-preview');
            preview.style.display = 'block';
            const [file] = value.files
            if (file) {
                preview.src = URL.createObjectURL(file)
            }
        }
    </script>
@endsection
