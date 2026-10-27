<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

    @include('components.backend.header')

    <!--start sidebar wrapper-->
    @include('components.backend.sidebar')
    <!--end sidebar wrapper-->

        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6">
                  <h4>Global Presence</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home Page</li>
                    <li class="breadcrumb-item active">Global Presence</li>
                </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header">
                        <h4>Our Global Presence</h4>
                        <p class="f-m-light mt-1">The strip under the home page banner: a short introduction, the financial centres with their landmark drawings, and a button.</p>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach
                                </ul>
                            </div>
                        @endif

                        <form class="row g-4 custom-input" action="{{ route('global-presence-details.update') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="col-lg-6">
                                <label class="form-label" for="eyebrow">Small Title</label>
                                <input class="form-control" id="eyebrow" type="text" name="eyebrow" value="{{ old('eyebrow', $presence->eyebrow) }}" placeholder="e.g. Our Global Presence">
                                <small class="text-secondary">Shown in small capital letters above the heading.</small>
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label d-block">Status</label>
                                <div class="form-check form-switch mt-2">
                                    <input type="checkbox" name="status" value="1" class="form-check-input" id="status-toggle" {{ old('status', $presence->status ?? 1) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status-toggle">Show this section on the home page</label>
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="heading">Heading <span class="txt-danger">*</span></label>
                                <input class="form-control" id="heading" type="text" name="heading" value="{{ old('heading', $presence->heading) }}" placeholder="e.g. Extending Our Expertise Across Key Financial Centres" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label" for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="2" placeholder="One or two lines under the heading">{{ old('description', $presence->description) }}</textarea>
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label" for="button_text">Button Text</label>
                                <input class="form-control" id="button_text" type="text" name="button_text" value="{{ old('button_text', $presence->button_text) }}" placeholder="e.g. Explore Our Global Presence">
                                <small class="text-secondary">Leave empty to hide the button.</small>
                            </div>

                            <div class="col-lg-6">
                                <label class="form-label" for="button_link">Button Link</label>
                                <input class="form-control" id="button_link" type="text" name="button_link" value="{{ old('button_link', $presence->button_link) }}" placeholder="e.g. group-companies or https://...">
                                <small class="text-secondary">A page of this website (e.g. <code>group-companies</code>) or a full address.</small>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Background Image</label>
                                <input class="form-control" type="file" name="background_image" accept=".jpg,.jpeg,.png,.webp">
                                <small class="text-secondary d-block mt-1">
                                    <i class="fa fa-info-circle"></i>
                                    Shown faintly behind the strip. Wide image recommended (about 1600 &times; 400 px), max 8 MB.
                                    Leave empty to use the dotted world map.
                                </small>
                                <div class="mt-2 p-2 border rounded" style="display:inline-block; background:#f7f2ee;">
                                    <img src="{{ $presence->background_image ? asset('home/global-presence/'.$presence->background_image) : asset('frontend/assets/images/bg/map.webp') }}"
                                         alt="background" style="max-height:80px; max-width:320px; display:block; opacity:.7;">
                                    <small class="text-secondary d-block mt-1">
                                        {{ $presence->background_image ? 'Your uploaded image.' : 'Default: dotted world map.' }}
                                    </small>
                                </div>
                                @if ($presence->background_image)
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" name="remove_background" value="1" id="remove-bg">
                                        <label class="form-check-label" for="remove-bg">Remove it and go back to the world map</label>
                                    </div>
                                @endif
                            </div>

                            <!-- ============ Locations (repeater) ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Locations</h5>
                                    <button type="button" id="btn-add-item" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>

                                <div class="table-responsive custom-scrollbar">
                                    <table class="table table-bordered align-middle">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width:45px;">#</th>
                                                <th style="width:300px;">Landmark Icon</th>
                                                <th>Location Name <span class="txt-danger">*</span></th>
                                                <th style="width:280px;">Link (optional)</th>
                                                <th style="width:90px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="items-tbody">
                                            @foreach (($presence->items ?? []) as $i => $item)
                                            <tr class="item-row">
                                                <td class="row-index">{{ $i + 1 }}</td>
                                                <td>
                                                    <input class="form-control mb-2" type="file" name="item_icon[]" accept=".svg,.png,.jpg,.jpeg,.webp">
                                                    <input type="hidden" name="item_existing_icon[]" value="{{ $item['icon'] ?? '' }}">
                                                    @if (!empty($item['icon']))
                                                        <img src="{{ asset('home/global-presence/'.$item['icon']) }}" alt="icon"
                                                             style="width:54px; height:54px; padding:6px; border-radius:50%; background:#efe7e1;">
                                                    @else
                                                        <span class="text-muted small">No icon</span>
                                                    @endif
                                                </td>
                                                <td><input class="form-control" type="text" name="item_name[]" value="{{ $item['name'] ?? '' }}" placeholder="e.g. Mauritius"></td>
                                                <td><input class="form-control" type="text" name="item_link[]" value="{{ $item['link'] ?? '' }}" placeholder="e.g. group-companies"></td>
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
                                    Locations appear in this order. Leave the file empty to keep a row's current icon.
                                    Icons: SVG or PNG with a transparent background, square, max 4 MB - they sit in a round beige circle.
                                    Three or four locations fit best on one line.
                                </small>
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <button class="btn btn-primary px-4" type="submit">Save</button>
                            </div>
                        </form>
                    </div>
                    </div>
                </div>
            </div>
          </div>
        </div>
        <!-- footer start-->
        @include('components.backend.footer')
        </div>
        </div>

       @include('components.backend.main-js')

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tbody  = document.getElementById('items-tbody');
        const addBtn = document.getElementById('btn-add-item');

        function reindex() {
            tbody.querySelectorAll('.item-row').forEach(function (row, i) {
                row.querySelector('.row-index').textContent = i + 1;
            });
        }

        function newRow() {
            const row = document.createElement('tr');
            row.className = 'item-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><input class="form-control mb-2" type="file" name="item_icon[]" accept=".svg,.png,.jpg,.jpeg,.webp">' +
                '<input type="hidden" name="item_existing_icon[]" value=""><span class="text-muted small">No icon</span></td>' +
                '<td><input class="form-control" type="text" name="item_name[]" placeholder="e.g. Mauritius"></td>' +
                '<td><input class="form-control" type="text" name="item_link[]" placeholder="e.g. group-companies"></td>' +
                '<td class="text-center text-nowrap">' +
                '<button type="button" class="btn btn-outline-secondary btn-sm btn-move-up" title="Move up"><i class="fa fa-arrow-up"></i></button> ' +
                '<button type="button" class="btn btn-outline-danger btn-sm btn-remove-item" title="Remove"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        addBtn.addEventListener('click', function () {
            tbody.appendChild(newRow());
            reindex();
        });

        tbody.addEventListener('click', function (e) {
            const remove = e.target.closest('.btn-remove-item');
            if (remove) { remove.closest('.item-row').remove(); reindex(); return; }

            const up = e.target.closest('.btn-move-up');
            if (up) {
                const row = up.closest('.item-row');
                if (row.previousElementSibling) { tbody.insertBefore(row, row.previousElementSibling); reindex(); }
            }
        });

        if (!tbody.querySelectorAll('.item-row').length) { tbody.appendChild(newRow()); reindex(); }
    });
</script>

</body>

</html>
