@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white rounded-lg shadow p-8">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        {{ $post->id ? 'Edit Post' : 'Create Post' }}
    </h1>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded p-4 mb-6">
            @foreach($errors->all() as $error)
                <p class="text-red-600 text-sm">{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 rounded p-4 mb-6">
            <p class="text-green-600">{{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 rounded p-4 mb-6">
            <p class="text-red-600">{{ session('error') }}</p>
        </div>
    @endif

    <form action="{{ $post->id ? route('posts.update', $post->id) : route('posts.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @if($post->id)
            @method('PUT')
        @endif

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Title</label>
            <input type="text"
                   id="title"
                   name="title"
                   value="{{ old('title', $post->title) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Slug</label>
            <input type="text"
                   id="slug"
                   name="slug"
                   value="{{ old('slug', $post->slug) }}"
                   class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400">
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Body</label>
            <textarea name="body"
                      rows="5"
                      class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:border-blue-400">{{ old('body', $post->body) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-gray-700 font-medium mb-1">Image</label>
            @if($post->media)
                <img src="{{ Storage::url($post->media->path) }}"
                     class="w-32 h-32 object-cover rounded mb-2">
            @endif
            <input type="file"
                   name="image"
                   accept="image/*"
                   class="w-full border border-gray-300 rounded px-3 py-2">
        </div>

        <div class="mb-6">
            <label class="flex items-center gap-2 text-gray-700 font-medium">
                <input type="checkbox"
                       name="is_published"
                       value="1"
                       @checked(old('is_published', $post->is_published))>
                Published
            </label>
        </div>

        <div class="flex gap-3">
            <button type="submit"
                    name="action"
                    value="stay"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                {{ $post->id ? 'Update' : 'Create' }}
            </button>

            @if($post->id)
                <button type="submit"
                        name="action"
                        value="back"
                        class="bg-gray-600 text-white px-6 py-2 rounded hover:bg-gray-700">
                    Save and go back
                </button>
            @endif

            <a href="{{ route('posts.index') }}"
               class="bg-gray-200 text-gray-700 px-6 py-2 rounded hover:bg-gray-300">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('title').addEventListener('input', function() {
    let slug = this.value
        .toLowerCase()
        .trim()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/[\s]+/g, '-');
    document.getElementById('slug').value = slug;
});
</script>
@endpush