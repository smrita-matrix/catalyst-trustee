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
                  <h4>Add Footer</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('footer-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Add Footer</li>
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
                        <h4>Footer</h4>
                        <p class="f-m-light mt-1">Set the footer logo, description, contact details and social links.</p>
                    </div>
                    <div class="card-body">
                        @php
                            $socialOptions = [
                                'fa fa-facebook'  => 'Facebook',
                                'fa fa-linkedin'  => 'LinkedIn',
                                'fa fa-instagram' => 'Instagram',
                                'fa fa-twitter'   => 'Twitter / X',
                                'fa fa-youtube'   => 'YouTube',
                                'fa fa-whatsapp'  => 'WhatsApp',
                            ];
                            $oldIcons = old('social_icon', ['fa fa-facebook', 'fa fa-linkedin', 'fa fa-instagram']);
                            $oldUrls  = old('social_url', ['', '', '']);
                        @endphp

                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('footer-details.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Logo -->
                            <div class="col-lg-6">
                                <label class="form-label" for="logo">Logo</label>
                                <input class="form-control" id="logo" type="file" name="logo" accept=".png, .jpg, .jpeg, .webp, .svg" onchange="previewLogo()">
                                <small class="d-block text-secondary mt-2"><i class="fa fa-info-circle"></i> Max 2MB &middot; Allowed: .png, .jpg, .webp, .svg</small>
                            </div>

                            <!-- Logo Preview -->
                            <div class="col-lg-6">
                                <label class="form-label">Logo Preview</label>
                                <div class="img-preview logo-preview"></div>
                            </div>

                            <!-- Description -->
                            <div class="col-12">
                                <label class="form-label" for="description">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3" placeholder="e.g. Catalyst Trusteeship Limited is one of the leading providers...">{{ old('description') }}</textarea>
                            </div>

                            <!-- Phone -->
                            <div class="col-lg-4">
                                <label class="form-label" for="phone">Phone</label>
                                <input class="form-control" id="phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="e.g. +91 (022) 4922 0555">
                            </div>

                            <!-- Email -->
                            <div class="col-lg-4">
                                <label class="form-label" for="email">Email</label>
                                <input class="form-control" id="email" type="text" name="email" value="{{ old('email') }}" placeholder="e.g. dt.mumbai@ctltrustee.com">
                            </div>

                            <!-- Address -->
                            <div class="col-lg-4">
                                <label class="form-label" for="address">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="1" placeholder="e.g. 901, 9th Floor, Towr-B, Peninsula Business Park...">{{ old('address') }}</textarea>
                            </div>

                            <!-- ============ Social Links (repeater) ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Social Links</h5>
                                    <button type="button" id="btn-add-social" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>

                                <div class="table-responsive custom-scrollbar">
                                    <table class="table table-bordered align-middle" id="social-table">
                                        <thead class="table-light">
                                            <tr>
                                                <th style="width: 55px;">#</th>
                                                <th style="width: 240px;">Platform</th>
                                                <th>URL</th>
                                                <th style="width: 60px;"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="social-tbody">
                                            @foreach ($oldIcons as $i => $oldIcon)
                                                <tr class="social-row">
                                                    <td class="row-index">{{ $i + 1 }}</td>
                                                    <td>
                                                        <select class="form-control" name="social_icon[]">
                                                            @foreach ($socialOptions as $val => $lbl)
                                                                <option value="{{ $val }}" {{ $oldIcon === $val ? 'selected' : '' }}>{{ $lbl }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td><input class="form-control" type="text" name="social_url[]" value="{{ $oldUrls[$i] ?? '' }}" placeholder="https://..."></td>
                                                    <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-social" title="Remove"><i class="fa fa-trash"></i></button></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('footer-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
    function previewLogo() {
        const file = document.getElementById('logo').files[0];
        const preview = document.querySelector('.logo-preview');
        preview.innerHTML = '';
        if (!file) return;
        const ext = file.name.split('.').pop().toLowerCase();
        if (['svg', 'png', 'jpg', 'jpeg', 'webp'].indexOf(ext) === -1) {
            alert('Please upload a valid image (svg, png, jpg, jpeg, webp).');
            document.getElementById('logo').value = '';
            return;
        }
        const reader = new FileReader();
        reader.onload = function (ev) { preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">'; };
        reader.readAsDataURL(file);
    }

    document.addEventListener('DOMContentLoaded', function () {
        const tbody  = document.getElementById('social-tbody');
        const addBtn = document.getElementById('btn-add-social');
        const optionsHtml = `@foreach ($socialOptions as $val => $lbl)<option value="{{ $val }}">{{ $lbl }}</option>@endforeach`;

        function reindex() {
            tbody.querySelectorAll('.social-row').forEach(function (r, idx) {
                r.querySelector('.row-index').textContent = idx + 1;
            });
        }

        function rowTemplate() {
            const row = document.createElement('tr');
            row.className = 'social-row';
            row.innerHTML =
                '<td class="row-index"></td>' +
                '<td><select class="form-control" name="social_icon[]">' + optionsHtml + '</select></td>' +
                '<td><input class="form-control" type="text" name="social_url[]" placeholder="https://..."></td>' +
                '<td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-social" title="Remove"><i class="fa fa-trash"></i></button></td>';
            return row;
        }

        addBtn.addEventListener('click', function () {
            tbody.appendChild(rowTemplate());
            reindex();
        });

        tbody.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-social');
            if (!removeBtn) return;
            const rows = tbody.querySelectorAll('.social-row');
            if (rows.length > 1) {
                removeBtn.closest('.social-row').remove();
            } else {
                const row = removeBtn.closest('.social-row');
                row.querySelector('input').value = '';
            }
            reindex();
        });
    });
</script>
</body>

</html>
