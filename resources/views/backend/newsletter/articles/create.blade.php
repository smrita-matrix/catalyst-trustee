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
                <div class="col-6"><h4>Add Articles</h4></div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('articles.index') }}">Home</a></li>
                    <li class="breadcrumb-item active">Add Articles</li>
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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h4>Newsletter Articles</h4>
                            <p class="f-m-light mt-1 mb-0">Add one or more articles. Set the <b>Year</b> (use <b>Archive</b> for old ones). Click <b>Add More</b> to add another row. Only <b>Title</b> is required.</p>
                            <p class="mt-1 mb-0"><span class="badge badge-light-warning"><i class="fa fa-info-circle"></i> Cover Image: <b>WebP only</b>, max <b>2 MB</b> &nbsp;|&nbsp; PDF: max <b>25 MB</b></span></p>
                        </div>
                        <button type="button" id="btn-add-article" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
                    </div>
                    <div class="card-body">
                        <form class="needs-validation custom-input banner-form" novalidate action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="table-responsive custom-scrollbar">
                                <table class="table table-bordered align-middle" id="articles-table" style="min-width: 950px;">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 40px;">#</th>
                                            <th style="width: 130px;">Year <span class="txt-danger">*</span></th>
                                            <th style="width: 220px;">Title (label) <span class="txt-danger">*</span></th>
                                            <th>Cover Image <span class="text-secondary">(WebP, ≤2 MB)</span></th>
                                            <th>PDF file <span class="text-secondary">(PDF, ≤25 MB)</span></th>
                                            <th style="width: 80px;">Order</th>
                                            <th style="width: 45px;"></th>
                                        </tr>
                                    </thead>
                                    <tbody id="articles-tbody">
                                        @php $rows = old('title', ['']); @endphp
                                        @foreach ($rows as $i => $r)
                                        <tr class="article-row">
                                            <td class="row-index">{{ $i + 1 }}</td>
                                            <td><input class="form-control" type="text" name="year[]" value="{{ old('year.'.$i, $year ?? '') }}" placeholder="2025"></td>
                                            <td><input class="form-control" type="text" name="title[]" value="{{ old('title.'.$i) }}" placeholder="e.g. October"></td>
                                            <td>
                                                <input class="form-control mb-2 nl-img-input" type="file" name="image[]" accept=".webp">
                                                <div class="img-preview nl-img-preview"></div>
                                            </td>
                                            <td>
                                                <input class="form-control nl-pdf-input" type="file" name="pdf_file[]" accept=".pdf">
                                                <div class="nl-pdf-name small text-muted"></div>
                                            </td>
                                            <td><input class="form-control" type="number" name="sort_order[]" value="{{ old('sort_order.'.$i, 0) }}" placeholder="0"></td>
                                            <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-article" title="Remove"><i class="fa fa-trash"></i></button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <small class="text-secondary"><i class="fa fa-info-circle"></i> "Title" is the label on the card (e.g. a month name). Lower "Order" numbers appear first. Empty rows (no Title) are ignored.</small>

                            <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-3">
                                <a href="{{ route('articles.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                                <button class="btn btn-primary px-4" type="submit">Submit</button>
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
        const tbody  = document.getElementById('articles-tbody');
        const addBtn = document.getElementById('btn-add-article');

        function reindex() {
            tbody.querySelectorAll('.article-row').forEach(function (row, idx) {
                row.querySelector('.row-index').textContent = idx + 1;
            });
        }

        function articleRow() {
            const row = document.createElement('tr');
            row.className = 'article-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><input class="form-control" type="text" name="year[]" placeholder="2025"></td>' +
                '<td><input class="form-control" type="text" name="title[]" placeholder="e.g. October"></td>' +
                '<td><input class="form-control mb-2 nl-img-input" type="file" name="image[]" accept=".webp"><div class="img-preview nl-img-preview"></div></td>' +
                '<td><input class="form-control nl-pdf-input" type="file" name="pdf_file[]" accept=".pdf"><div class="nl-pdf-name small text-muted"></div></td>' +
                '<td><input class="form-control" type="number" name="sort_order[]" value="0" placeholder="0"></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-article" title="Remove"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        addBtn.addEventListener('click', function () { tbody.appendChild(articleRow()); reindex(); });

        tbody.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-article');
            if (!removeBtn) return;
            const rows = tbody.querySelectorAll('.article-row');
            if (rows.length > 1) {
                removeBtn.closest('.article-row').remove();
            } else {
                const row = removeBtn.closest('.article-row');
                row.querySelectorAll('input').forEach(function (el) { el.value = ''; });
                const p = row.querySelector('.nl-img-preview'); if (p) p.innerHTML = '';
                const n = row.querySelector('.nl-pdf-name'); if (n) n.textContent = '';
            }
            reindex();
        });

        tbody.addEventListener('change', function (e) {
            const img = e.target.closest('.nl-img-input');
            if (img) {
                const preview = img.parentElement.querySelector('.nl-img-preview');
                if (preview) {
                    preview.innerHTML = '';
                    const file = img.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (ev) { preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview" style="max-height:60px;">'; };
                        reader.readAsDataURL(file);
                    }
                }
                return;
            }
            const pdf = e.target.closest('.nl-pdf-input');
            if (pdf) {
                const nameBox = pdf.parentElement.querySelector('.nl-pdf-name');
                const file = pdf.files[0];
                if (nameBox) nameBox.textContent = file ? file.name : '';
            }
        });
    });
</script>
</body>

</html>
