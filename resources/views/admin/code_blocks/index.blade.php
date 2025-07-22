@extends('layouts.admin.app')

@section('title', 'Code Blocks')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-3 text-gray-800">Code Blocks</h1>

    <div class="card shadow">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">List of Code Blocks</h6>
            <a href="{{ route('code-blocks.create') }}" class="btn btn-primary btn-sm">Add New Code Block</a>
        </div>
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Description</th>
                            <th>Language</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($data as $codeBlock)
                        <tr>
                            <td>{{ $codeBlock->id }}</td>
                            <td>{{ $codeBlock->description ?? 'N/A' }}</td>
                            <td>{{ $codeBlock->language ?? 'N/A' }}</td>
                            <td>
                                <a href="{{ route('code-blocks.edit', $codeBlock->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('code-blocks.destroy', $codeBlock->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center">No code blocks found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $data->links() }}
        </div>
    </div>
</div>
@endsection
