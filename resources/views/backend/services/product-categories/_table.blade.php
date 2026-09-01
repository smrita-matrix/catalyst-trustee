@php $showCategory = $showCategory ?? false; @endphp
<div class="table-responsive custom-scrollbar">
<table class="table table-bordered align-middle mb-0">
    <thead class="table-light">
        <tr>
            <th style="width:50px;">#</th>
            @if($showCategory)<th>Service Category</th>@endif
            <th>Product Name</th>
            <th>Layout</th>
            <th style="width:90px;">Status</th>
            <th style="width:330px;">Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($rows as $key => $item)
            <tr>
                <td>{{ $key + 1 }}</td>
                @if($showCategory)<td>{{ optional($item->serviceCategory)->name ?? '—' }}</td>@endif
                <td><b>{{ $item->name }}</b></td>
                <td>
                    @if ($item->layout && isset(\App\Models\ProductCategory::LAYOUTS[$item->layout]))
                        <span class="badge bg-info">{{ \App\Models\ProductCategory::LAYOUTS[$item->layout] }}</span>
                    @else
                        <span class="badge bg-warning text-dark">Not set</span>
                    @endif
                </td>
                <td>
                    @include('components.backend.status-toggle', [
                        'item' => $item,
                        'url'  => route('product-category.toggle', $item->id),
                    ])
                </td>
                <td>
                    @if($item->layout)
                    <a href="{{ route('product-page.open', $item->id) }}" class="btn btn-sm btn-success" title="Edit this service page's content">
                        <i class="fa fa-file-text-o"></i> Edit Page
                    </a>
                    @else
                    <a href="{{ route('product-category.edit', $item->id) }}" class="btn btn-sm btn-outline-secondary" title="Set a layout under Product Categories to enable the page editor">
                        <i class="fa fa-cog"></i> Set Layout
                    </a>
                    @endif
                    <form action="{{ route('product-category.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this product?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                            <i class="fa fa-trash"></i> Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="{{ $showCategory ? 6 : 5 }}" class="text-center text-secondary py-4">No products here yet.</td>
            </tr>
        @endforelse
    </tbody>
</table>
</div>
