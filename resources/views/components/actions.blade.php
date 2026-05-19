<div style="display:flex; gap:8px;">
    <a href="{{ route($repository->modelNames . '.edit', [$repository->modelKey => $row->id]) }}">
        Edit
    </a>

    <form action="{{ route($repository->modelNames . '.destroy', [$repository->modelKey => $row->id]) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" onclick="return confirm('Are you sure?')">
            Delete
        </button>
    </form>
</div>