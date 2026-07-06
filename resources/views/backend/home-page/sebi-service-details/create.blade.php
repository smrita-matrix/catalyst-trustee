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
                  <h4>Add SEBI Services Section</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('sebi-service-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Add SEBI Services</li>
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
                        <h4>SEBI Regulated Trustee Services</h4>
                        <p class="f-m-light mt-1">Set the section heading, then add one or more service cards.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('sebi-service-details.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <!-- Heading -->
                            <div class="col-12">
                                <label class="form-label" for="heading">Section Heading <span class="txt-danger">*</span></label>
                                <input class="form-control" id="heading" type="text" name="heading" value="{{ old('heading') }}" placeholder="e.g. SEBI Regulated Trustee Services" required>
                                <div class="invalid-feedback">Please enter the Heading.</div>
                            </div>

                            <!-- ============ Service Cards (repeater) ============ -->
                            <div class="col-12">
                                <hr class="mt-2">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h5 class="mb-0">Service Cards</h5>
                                    <button type="button" id="btn-add-item" class="btn btn-outline-primary btn-sm">
                                        <i class="fa fa-plus"></i> Add More
                                    </button>
                                </div>

                                <div id="items-wrapper">
                                    @php
                                        $oldTitles = old('item_title', ['']);
                                    @endphp
                                    @foreach ($oldTitles as $i => $oldTitle)
                                        <div class="item-block border rounded p-3 mb-3 position-relative">
                                            <div class="d-flex justify-content-between align-items-center mb-3">
                                                <h6 class="mb-0 item-block-title">Card <span class="block-index">{{ $i + 1 }}</span></h6>
                                                <button type="button" class="btn btn-outline-danger btn-sm btn-remove-item" title="Remove"><i class="fa fa-trash"></i></button>
                                            </div>
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <label class="form-label">Service Image</label>
                                                    <input class="form-control service-img-input" type="file" name="item_service_img[]" accept=".png, .jpg, .jpeg, .webp, .svg">
                                                    <div class="img-preview mt-2 service-img-preview"></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Icon</label>
                                                    <input class="form-control icon-input" type="file" name="item_icon[]" accept=".png, .jpg, .jpeg, .webp, .svg">
                                                    <div class="img-preview mt-2 icon-preview"></div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Title</label>
                                                    <input class="form-control" type="text" name="item_title[]" value="{{ $oldTitle }}" placeholder="e.g. Debenture Trustee Services (Listed)">
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Title Link</label>
                                                    <input class="form-control" type="text" name="item_title_link[]" value="{{ old('item_title_link')[$i] ?? '' }}" placeholder="e.g. /services/debenture-trustee or #">
                                                </div>
                                                <div class="col-12">
                                                    <label class="form-label">Description</label>
                                                    <textarea class="form-control" name="item_description[]" rows="3" placeholder="Card description">{{ old('item_description')[$i] ?? '' }}</textarea>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Read More Link</label>
                                                    <input class="form-control" type="text" name="item_read_more_link[]" value="{{ old('item_read_more_link')[$i] ?? '' }}" placeholder="e.g. /services/debenture-trustee or #">
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-secondary"><i class="fa fa-info-circle"></i> Each card: service image + icon (.png/.jpg/.webp/.svg, max 2MB each), title, link, description and a read-more link. Empty cards are ignored.</small>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('sebi-service-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
        const wrapper = document.getElementById('items-wrapper');
        const addBtn  = document.getElementById('btn-add-item');

        function reindex() {
            wrapper.querySelectorAll('.item-block').forEach(function (block, idx) {
                block.querySelector('.block-index').textContent = idx + 1;
            });
        }

        function blockTemplate() {
            const block = document.createElement('div');
            block.className = 'item-block border rounded p-3 mb-3 position-relative';
            block.innerHTML =
                '<div class="d-flex justify-content-between align-items-center mb-3">' +
                    '<h6 class="mb-0 item-block-title">Card <span class="block-index"></span></h6>' +
                    '<button type="button" class="btn btn-outline-danger btn-sm btn-remove-item" title="Remove"><i class="fa fa-trash"></i></button>' +
                '</div>' +
                '<div class="row g-3">' +
                    '<div class="col-lg-6"><label class="form-label">Service Image</label><input class="form-control service-img-input" type="file" name="item_service_img[]" accept=".png, .jpg, .jpeg, .webp, .svg"><div class="img-preview mt-2 service-img-preview"></div></div>' +
                    '<div class="col-lg-6"><label class="form-label">Icon</label><input class="form-control icon-input" type="file" name="item_icon[]" accept=".png, .jpg, .jpeg, .webp, .svg"><div class="img-preview mt-2 icon-preview"></div></div>' +
                    '<div class="col-lg-6"><label class="form-label">Title</label><input class="form-control" type="text" name="item_title[]" placeholder="e.g. Debenture Trustee Services (Listed)"></div>' +
                    '<div class="col-lg-6"><label class="form-label">Title Link</label><input class="form-control" type="text" name="item_title_link[]" placeholder="e.g. #"></div>' +
                    '<div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="item_description[]" rows="3" placeholder="Card description"></textarea></div>' +
                    '<div class="col-lg-6"><label class="form-label">Read More Link</label><input class="form-control" type="text" name="item_read_more_link[]" placeholder="e.g. #"></div>' +
                '</div>';
            return block;
        }

        addBtn.addEventListener('click', function () {
            wrapper.appendChild(blockTemplate());
            reindex();
        });

        wrapper.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-item');
            if (!removeBtn) return;

            const blocks = wrapper.querySelectorAll('.item-block');
            if (blocks.length > 1) {
                removeBtn.closest('.item-block').remove();
            } else {
                const block = removeBtn.closest('.item-block');
                block.querySelectorAll('input, textarea').forEach(function (el) { el.value = ''; });
                block.querySelectorAll('.img-preview').forEach(function (p) { p.innerHTML = ''; });
            }
            reindex();
        });

        // Live image preview (service image + icon)
        wrapper.addEventListener('change', function (e) {
            const input = e.target.closest('.service-img-input, .icon-input');
            if (!input) return;

            const preview = input.parentElement.querySelector('.img-preview');
            if (!preview) return;

            preview.innerHTML = '';
            const file = input.files[0];
            if (!file) return;

            const ext = file.name.split('.').pop().toLowerCase();
            const validExts = ['svg', 'png', 'jpg', 'jpeg', 'webp'];
            if (!validExts.includes(ext)) {
                alert('Please upload a valid image (svg, png, jpg, jpeg, webp).');
                input.value = '';
                return;
            }

            const reader = new FileReader();
            reader.onload = function (ev) {
                preview.innerHTML = '<img src="' + ev.target.result + '" alt="preview">';
            };
            reader.readAsDataURL(file);
        });
    });
</script>
</body>

</html>
