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
                  <h4>Edit Proofs Section</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('proofs-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Proofs</li>
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
                        <h4>Build on Trust. Recognized for Excellence</h4>
                        <p class="f-m-light mt-1">Update the section heading and its proof / recognition cards.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('proofs-details.update', $proofs->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <!-- Heading -->
                            <div class="col-12">
                                <label class="form-label" for="heading">Section Heading</label>
                                <input class="form-control" id="heading" type="text" name="heading" value="{{ old('heading', $proofs->heading) }}" placeholder="e.g. Build on Trust. Recognized for Excellence">
                            </div>

                            <!-- ============ Items (table repeater) ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Proof Cards</h5>
                                    <button type="button" id="btn-add-item" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>

                                <div class="table-responsive custom-scrollbar">
                                    <table class="table table-bordered align-middle" id="items-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 55px;">#</th>
                                                <th style="width: 260px;">Background Image</th>
                                                <th style="width: 300px;">Icon (upload or paste SVG)</th>
                                                <th>Text</th>
                                                <th style="width: 60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="items-tbody">
                                            @php
                                                $existingItems = old('item_text')
                                                    ? collect(old('item_text'))->map(function ($t, $i) {
                                                        return [
                                                            'text'     => $t,
                                                            'icon_svg' => old('item_icon_svg')[$i] ?? null,
                                                            'image'    => old('item_existing_image')[$i] ?? null,
                                                            'icon'     => old('item_existing_icon')[$i] ?? null,
                                                        ];
                                                    })->all()
                                                    : ($proofs->items ?: [['text' => '', 'icon_svg' => null, 'image' => null, 'icon' => null]]);
                                            @endphp
                                            @foreach ($existingItems as $i => $item)
                                                <tr class="item-row">
                                                    <td class="row-index">{{ $i + 1 }}</td>
                                                    <td>
                                                        <input class="form-control mb-2 bg-image-input" type="file" name="item_image[]" accept=".png, .jpg, .jpeg, .webp, .svg">
                                                        <input type="hidden" name="item_existing_image[]" value="{{ $item['image'] ?? '' }}">
                                                        <div class="img-preview bg-image-preview">
                                                            @if (!empty($item['image']))
                                                                <img src="{{ asset('home/proofs/' . $item['image']) }}" alt="bg">
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <input class="form-control mb-2 icon-input" type="file" name="item_icon[]" accept=".svg, .png, .jpg, .jpeg, .webp">
                                                        <input type="hidden" name="item_existing_icon[]" value="{{ $item['icon'] ?? '' }}">
                                                        <textarea class="form-control icon-svg-input mb-2" name="item_icon_svg[]" rows="2" placeholder="…or paste &lt;svg&gt;…&lt;/svg&gt; code">{{ $item['icon_svg'] ?? '' }}</textarea>
                                                        <div class="img-preview icon-preview">
                                                            @if (!empty($item['icon_svg']))
                                                                {!! $item['icon_svg'] !!}
                                                            @elseif (!empty($item['icon']))
                                                                <img src="{{ asset('home/proofs/' . $item['icon']) }}" alt="icon">
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td><textarea class="form-control" name="item_text[]" rows="2" placeholder="Card text">{{ $item['text'] ?? '' }}</textarea></td>
                                                    <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-item" title="Remove"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <small class="text-secondary"><i class="fa fa-info-circle"></i> Leave a file empty to keep the current image. Max 2MB each. Empty rows are ignored.</small>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('proofs-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                                <button class="btn btn-primary px-4" type="submit">Update</button>
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
            tbody.querySelectorAll('.item-row').forEach(function (row, idx) {
                row.querySelector('.row-index').textContent = idx + 1;
            });
        }

        function rowTemplate() {
            const row = document.createElement('tr');
            row.className = 'item-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><input class="form-control mb-2 bg-image-input" type="file" name="item_image[]" accept=".png, .jpg, .jpeg, .webp, .svg"><input type="hidden" name="item_existing_image[]" value=""><div class="img-preview bg-image-preview"></div></td>' +
                '<td><input class="form-control mb-2 icon-input" type="file" name="item_icon[]" accept=".svg, .png, .jpg, .jpeg, .webp"><input type="hidden" name="item_existing_icon[]" value=""><textarea class="form-control icon-svg-input mb-2" name="item_icon_svg[]" rows="2" placeholder="…or paste <svg>…</svg> code"></textarea><div class="img-preview icon-preview"></div></td>' +
                '<td><textarea class="form-control" name="item_text[]" rows="2" placeholder="Card text"></textarea></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-item" title="Remove"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        addBtn.addEventListener('click', function () {
            tbody.appendChild(rowTemplate());
            reindex();
        });

        tbody.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-item');
            if (!removeBtn) return;
            const rows = tbody.querySelectorAll('.item-row');
            if (rows.length > 1) {
                removeBtn.closest('.item-row').remove();
            } else {
                const row = removeBtn.closest('.item-row');
                row.querySelectorAll('input, textarea').forEach(function (el) {
                    if (el.type !== 'hidden') el.value = '';
                });
                row.querySelectorAll('input[type="hidden"]').forEach(function (el) { el.value = ''; });
                row.querySelectorAll('.img-preview').forEach(function (p) { p.innerHTML = ''; });
            }
            reindex();
        });

        tbody.addEventListener('change', function (e) {
            const input = e.target.closest('.bg-image-input, .icon-input');
            if (!input) return;
            const preview = input.parentElement.querySelector('.img-preview');
            if (!preview) return;
            const file = input.files[0];
            if (!file) return;
            const ext = file.name.split('.').pop().toLowerCase();
            if (['svg', 'png', 'jpg', 'jpeg', 'webp'].indexOf(ext) === -1) {
                alert('Please upload a valid image (svg, png, jpg, jpeg, webp).');
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function (ev) { preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">'; };
            reader.readAsDataURL(file);
        });

        tbody.addEventListener('input', function (e) {
            const svgInput = e.target.closest('.icon-svg-input');
            if (!svgInput) return;
            const preview = svgInput.parentElement.querySelector('.icon-preview');
            if (!preview) return;
            const code = svgInput.value.trim();
            if (code.toLowerCase().indexOf('<svg') !== -1) {
                preview.innerHTML = code;
                preview.querySelectorAll('svg').forEach(function (svg) { svg.style.maxHeight = '60px'; svg.style.maxWidth = '60px'; });
            }
        });
    });
</script>
</body>

</html>
