<a href="{{ route('notice-category.edit', $item->id) }}" class="btn btn-sm btn-primary" title="Edit">
    <i class="fa fa-edit"></i> Edit
</a>
<form action="{{ route('notice-category.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this? Its page will no longer be reachable.');">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-sm btn-danger" title="Delete"><i class="fa fa-trash"></i></button>
</form>
