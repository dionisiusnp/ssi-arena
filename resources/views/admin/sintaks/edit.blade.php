@extends('layouts.admin.app')

@section('title', 'Edit Code Block')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Edit Code Block</h1>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('code-blocks.update', $codeBlock->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="description">Description (Optional)</label>
                    <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description', $codeBlock->description) }}">
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="language">Language (Optional, e.g., php, javascript, dart)</label>
                    <input type="text" name="language" id="language" class="form-control @error('language') is-invalid @enderror" value="{{ old('language', $codeBlock->language) }}">
                    @error('language')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="code_content">Code Content</label>
                    <textarea name="code_content" id="code_content" class="form-control @error('code_content') is-invalid @enderror" rows="15">{{ old('code_content', $codeBlock->code_content) }}</textarea>
                    @error('code_content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Update Code Block</button>
                    <a href="{{ route('code-blocks.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
