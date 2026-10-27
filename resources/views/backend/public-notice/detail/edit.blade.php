<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

    @include('components.backend.header')
    @include('components.backend.sidebar')

    <div class="page-body">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-12">
            <div class="card">
              <div class="card-body">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <nav aria-label="breadcrumb" role="navigation">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">Public Notice</li>
                            <li class="breadcrumb-item"><a href="{{ route('notice-category.index', ['tab' => 'pages']) }}">Menu &amp; Pages</a></li>
                            @if($category)
                            <li class="breadcrumb-item"><a href="{{ route('notice-category.edit', $category->id) }}">{{ $category->name }}</a></li>
                            @endif
                            @if($parent)
                            <li class="breadcrumb-item"><a href="{{ route('notice-detail.edit', $parent->id) }}">{{ $parent->title }}</a></li>
                            @endif
                            <li class="breadcrumb-item active" aria-current="page">{{ $notice->title }}</li>
                        </ol>
                    </nav>
                    <div class="d-flex gap-2">
                        @if($notice->detail_url)
                        <a href="{{ $notice->detail_url }}" target="_blank" rel="noopener" class="btn btn-outline-primary radius-30"><i class="fa fa-eye"></i> View on website</a>
                        @endif
                        @if($parent)
                        <a href="{{ route('notice-detail.edit', $parent->id) }}" class="btn btn-secondary radius-30">Back</a>
                        @elseif($category)
                        <a href="{{ route('notice-category.edit', $category->id) }}" class="btn btn-secondary radius-30">Back</a>
                        @endif
                    </div>
                </div>

                @if ($errors->any())
                  <div class="alert alert-danger">
                    <ul class="mb-0">
                      @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                    </ul>
                  </div>
                @endif

                <form action="{{ route('notice-detail.update', $notice->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="form-check form-switch mb-4">
                      <input type="checkbox" name="has_detail_page" value="1" class="form-check-input" id="detail-toggle"
                        {{ old('has_detail_page', $notice->has_detail_page) ? 'checked' : '' }}>
                      <label class="form-check-label" for="detail-toggle">
                        <b>Open this page from the card</b>
                        <small class="text-secondary d-block">When off, the card opens its single document instead, as before.</small>
                      </label>
                    </div>

                    <div class="card border">
                      <div class="card-header py-2"><b>Page Banner</b></div>
                      <div class="card-body">
                        <div class="row g-4">
                          <div class="col-lg-6">
                            <label class="form-label">Banner Heading <span class="txt-danger">*</span></label>
                            <input type="text" name="detail_banner_title" class="form-control" required
                                   value="{{ old('detail_banner_title', $notice->detail_banner_title ?: $notice->title) }}">
                            @if($notice->detail_slug && $category)
                              <small class="text-secondary d-block mt-1">Address: /public-notice/{{ $category->slug }}/{{ $notice->detail_slug }}</small>
                            @endif
                          </div>
                          <div class="col-lg-6">
                            <label class="form-label">Banner Image <span class="txt-danger">*</span></label>
                            <input type="file" name="detail_banner_image" class="form-control" accept=".jpg,.jpeg,.png,.webp" {{ $notice->detail_banner_image ? '' : 'required' }}>
                            <small class="text-secondary d-block mt-1">
                              <i class="fa fa-info-circle"></i>
                              Recommended <b>1500 × 976 px</b> — WebP, JPG or PNG, max 8 MB. Keep the subject in the middle.
                            </small>
                            @if($notice->detail_banner_image)
                              <div class="mt-2 p-2 border rounded" style="display:inline-block; background:#f7f8fa;">
                                <img src="{{ asset('public-notice/detail-banner/'.$notice->detail_banner_image) }}" alt="banner"
                                     style="max-height:120px; max-width:100%; border-radius:6px; display:block;">
                              </div>
                            @endif
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="card border mt-4">
                      <div class="card-header py-2 d-flex justify-content-between align-items-center">
                        <b>Page Content — Documents</b>
                        <button type="button" id="btn-add-item" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
                      </div>
                      <div class="card-body">
                        <div class="table-responsive custom-scrollbar">
                          <table class="table table-bordered align-middle">
                            <thead class="table-light">
                              <tr>
                                <th style="width:45px;">#</th>
                                <th style="width:170px;">Show As</th>
                                <th style="width:200px;">Heading</th>
                                <th>Title <span class="txt-danger">*</span></th>
                                <th style="width:260px;">PDF / Link</th>
                                <th style="width:90px;"></th>
                              </tr>
                            </thead>
                            <tbody id="items-tbody">
                              @foreach ($items as $i => $item)
                              <tr class="item-row">
                                <td class="item-index">{{ $i + 1 }}</td>
                                <td>
                                  <input type="hidden" name="item_id[]" value="{{ $item->id }}">
                                  <select name="item_highlight[]" class="form-control item-highlight">
                                    <option value="0" {{ $item->is_highlight ? '' : 'selected' }}>Under a heading</option>
                                    <option value="1" {{ $item->is_highlight ? 'selected' : '' }}>On its own (bold)</option>
                                  </select>
                                </td>
                                <td><input class="form-control item-group" type="text" name="item_group[]" value="{{ $item->group_title }}" placeholder="e.g. Press Release"></td>
                                <td><textarea class="form-control" name="item_title[]" rows="2">{{ $item->title }}</textarea></td>
                                <td>
                                  <input class="form-control mb-1" type="file" name="item_file[]" accept=".pdf,.jpg,.jpeg,.png,.webp">
                                  @if($item->document_file)
                                    <small class="d-block mb-1"><a href="{{ $item->url }}" target="_blank" rel="noopener noreferrer"><i class="fa fa-file-pdf-o"></i> Current file</a></small>
                                  @endif
                                  <input class="form-control" type="text" name="item_link[]" value="{{ $item->document_file ? '' : $item->document_link }}" placeholder="or paste a link">
                                </td>
                                <td class="text-center text-nowrap">
                                  <button type="button" class="btn btn-outline-secondary btn-sm btn-move-up" title="Move up"><i class="fa fa-arrow-up"></i></button>
                                  <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item" title="Remove"><i class="fa fa-trash"></i></button>
                                </td>
                              </tr>
                              @endforeach
                            </tbody>
                          </table>
                        </div>
                        <small class="text-secondary">
                          <i class="fa fa-info-circle"></i>
                          Documents appear on the page in this order. Rows with the same <b>Heading</b> next to each other
                          are shown together under it (e.g. <i>Press Release</i>, <i>NCLT Orders</i>).
                          <b>On its own</b> shows a single document as a bold box, like the Status Report.
                          Upload a PDF or paste a link. Rows removed here disappear from the website when you save.
                        </small>
                      </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary px-5 radius-30">Save</button>
                    </div>
                </form>

                @unless($parent)
                {{-- Pages inside this one, like DHFL > DHFL IBC. Only one level deep. --}}
                <div class="card border mt-5">
                  <div class="card-header py-2"><b>Pages Inside This Page</b></div>
                  <div class="card-body">
                    @if($subPages->count())
                    <table class="table table-bordered align-middle">
                      <thead class="table-light">
                        <tr><th>Page</th><th>Address - paste into a document row's link box</th><th style="width:130px;"></th></tr>
                      </thead>
                      <tbody>
                        @foreach($subPages as $sub)
                        <tr>
                          <td>{{ $sub->title }}</td>
                          <td><code>{{ $sub->detail_url ? parse_url($sub->detail_url, PHP_URL_PATH) : '' }}</code></td>
                          <td class="text-nowrap">
                            <a href="{{ route('notice-detail.edit', $sub->id) }}" class="btn btn-sm btn-primary" title="Edit"><i class="fa fa-pencil"></i></a>
                            <form action="{{ route('notice-detail.destroy', $sub->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this page?');">
                              @csrf
                              @method('DELETE')
                              <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove"><i class="fa fa-trash"></i></button>
                            </form>
                          </td>
                        </tr>
                        @endforeach
                      </tbody>
                    </table>
                    @endif
                    <form action="{{ route('notice-detail.sub-page', $notice->id) }}" method="POST" class="row g-2 align-items-center">
                      @csrf
                      <div class="col-lg-6"><input type="text" name="sub_title" class="form-control" placeholder="New page name, e.g. DHFL IBC" required></div>
                      <div class="col-lg-3"><button type="submit" class="btn btn-outline-primary"><i class="fa fa-plus"></i> Add a page inside</button></div>
                    </form>
                    <small class="text-secondary d-block mt-2">
                      <i class="fa fa-info-circle"></i>
                      For a heading on this page that leads to a whole page of documents rather than one PDF.
                      Add the page here, then put its address in the link box of a document row above - it opens on this website.
                    </small>
                  </div>
                </div>
                @endunless

              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    @include('components.backend.footer')
    @include('components.backend.main-js')

<script>
  (function () {
    var tbody  = document.getElementById('items-tbody');
    var addBtn = document.getElementById('btn-add-item');

    function reindex() {
      tbody.querySelectorAll('.item-row').forEach(function (row, i) {
        row.querySelector('.item-index').textContent = i + 1;
        // A bold, stand-alone document has no heading.
        var bold = row.querySelector('.item-highlight').value === '1';
        row.querySelector('.item-group').disabled = bold;
      });
    }

    function newRow() {
      var row = document.createElement('tr');
      row.className = 'item-row';
      row.innerHTML =
        '<td class="item-index"></td>' +
        '<td><input type="hidden" name="item_id[]" value="">' +
        '<select name="item_highlight[]" class="form-control item-highlight"><option value="0">Under a heading</option><option value="1">On its own (bold)</option></select></td>' +
        '<td><input class="form-control item-group" type="text" name="item_group[]" placeholder="e.g. Press Release"></td>' +
        '<td><textarea class="form-control" name="item_title[]" rows="2"></textarea></td>' +
        '<td><input class="form-control mb-1" type="file" name="item_file[]" accept=".pdf,.jpg,.jpeg,.png,.webp">' +
        '<input class="form-control" type="text" name="item_link[]" placeholder="or paste a link"></td>' +
        '<td class="text-center text-nowrap"><button type="button" class="btn btn-outline-secondary btn-sm btn-move-up" title="Move up"><i class="fa fa-arrow-up"></i></button> ' +
        '<button type="button" class="btn btn-outline-danger btn-sm btn-remove-item" title="Remove"><i class="fa fa-trash"></i></button></td>';
      // A new row takes the heading of the row above it - usually the next press release.
      var last = tbody.querySelector('.item-row:last-child .item-group');
      if (last) { row.querySelector('.item-group').value = last.value; }
      return row;
    }

    addBtn.addEventListener('click', function () {
      tbody.appendChild(newRow());
      reindex();
    });

    tbody.addEventListener('click', function (e) {
      var remove = e.target.closest('.btn-remove-item');
      if (remove) { remove.closest('.item-row').remove(); reindex(); return; }

      var up = e.target.closest('.btn-move-up');
      if (up) {
        var row = up.closest('.item-row');
        if (row.previousElementSibling) { tbody.insertBefore(row, row.previousElementSibling); reindex(); }
      }
    });

    tbody.addEventListener('change', function (e) {
      if (e.target.classList.contains('item-highlight')) { reindex(); }
    });

    // A disabled heading box is not sent, which would shift every row after it; enable before submitting.
    tbody.closest('form').addEventListener('submit', function () {
      tbody.querySelectorAll('.item-group').forEach(function (input) { input.disabled = false; });
    });

    if (!tbody.querySelectorAll('.item-row').length) { tbody.appendChild(newRow()); }
    reindex();
  })();
</script>

</body>

</html>
