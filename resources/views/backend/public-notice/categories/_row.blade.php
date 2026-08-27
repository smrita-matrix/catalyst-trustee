{{-- One row of the menu tree. $depth 0 = column, 1 = menu link, 2 = fly-out link. --}}
<tr>
    <td>
        <span style="display:inline-block; width:{{ $depth * 26 }}px;"></span>
        @if($depth > 0)<i class="fa fa-level-up fa-rotate-90 text-muted"></i>@endif
        <strong style="{{ $depth === 0 ? '' : 'font-weight:400;' }}">{{ $item->name }}</strong>
    </td>
    <td>
        @switch($item->link_type)
            @case('page')
                <span class="badge bg-primary">Page</span>
                @if($item->slug)
                    <a href="{{ route('frontend.notice_page', $item->slug) }}" target="_blank" rel="noopener noreferrer" class="ms-1 small">/{{ $item->slug }}</a>
                @endif
                @break
            @case('pdf')
                <span class="badge bg-info">PDF</span>
                @if(!$item->document_file)<span class="text-danger small ms-1">not uploaded</span>@endif
                @break
            @case('url')
                <span class="badge bg-warning">Link</span>
                @break
            @default
                <span class="badge bg-secondary">Heading only</span>
        @endswitch
    </td>
    <td>{{ $item->layout ? \App\Models\NoticeCategory::LAYOUTS[$item->layout] ?? $item->layout : '—' }}</td>
    <td>{{ $item->sort_order }}</td>
    <td>
        @if ($item->status)
            <span class="badge bg-success">Shown</span>
        @else
            <span class="badge bg-secondary">Hidden</span>
        @endif
    </td>
    <td>
        @if($depth < 2)
            <a href="{{ route('notice-category.create', ['parent' => $item->id]) }}" class="btn btn-sm btn-outline-primary" title="Add an item inside this one">
                <i class="fa fa-plus"></i>
            </a>
        @endif
        <a href="{{ route('notice-category.edit', $item->id) }}" class="btn btn-sm btn-primary" title="Edit">
            <i class="fa fa-edit"></i> Edit
        </a>
        <form action="{{ route('notice-category.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this menu item? Its page will no longer be reachable.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                <i class="fa fa-trash"></i>
            </button>
        </form>
    </td>
</tr>
