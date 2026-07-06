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
                  <h4>Add Marquee Items</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('marquee-inner.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Add Marquee Items</li>
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
                        <h4>Marquee Strip Items</h4>
                        <p class="f-m-light mt-1">Add one or more scrolling marquee items. Use "Add More" to add multiple at once.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('marquee-inner.store') }}" method="POST">
                            @csrf

                            <div class="col-12">
                                <div id="marquee-items-wrapper">
                                    @php
                                        $oldTitles = old('title', ['']);
                                    @endphp
                                    @foreach ($oldTitles as $oldTitle)
                                        <div class="row g-2 align-items-center marquee-item-row mb-3">
                                            <div class="col">
                                                <label class="form-label">Marquee Item <span class="txt-danger">*</span></label>
                                                <input class="form-control" type="text" name="title[]" value="{{ $oldTitle }}" placeholder="e.g. Debenture Trustee Services (Listed)" required>
                                                <div class="invalid-feedback">Please enter the marquee item text.</div>
                                            </div>
                                            <div class="col-auto d-flex align-items-end" style="align-self: flex-end;">
                                                <button type="button" class="btn btn-outline-danger btn-remove-item" title="Remove">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <button type="button" id="btn-add-more" class="btn btn-outline-primary">
                                    <i class="fa fa-plus"></i> Add More
                                </button>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('marquee-inner.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
        const wrapper = document.getElementById('marquee-items-wrapper');
        const addBtn  = document.getElementById('btn-add-more');

        // Template for a new row
        function rowTemplate() {
            const row = document.createElement('div');
            row.className = 'row g-2 align-items-center marquee-item-row mb-3';
            row.innerHTML =
                '<div class="col">' +
                    '<label class="form-label">Marquee Item <span class="txt-danger">*</span></label>' +
                    '<input class="form-control" type="text" name="title[]" placeholder="e.g. Debenture Trustee Services (Listed)" required>' +
                    '<div class="invalid-feedback">Please enter the marquee item text.</div>' +
                '</div>' +
                '<div class="col-auto d-flex align-items-end" style="align-self: flex-end;">' +
                    '<button type="button" class="btn btn-outline-danger btn-remove-item" title="Remove"><i class="fa fa-trash"></i></button>' +
                '</div>';
            return row;
        }

        addBtn.addEventListener('click', function () {
            wrapper.appendChild(rowTemplate());
        });

        // Remove a row (keep at least one)
        wrapper.addEventListener('click', function (e) {
            const removeBtn = e.target.closest('.btn-remove-item');
            if (!removeBtn) return;

            const rows = wrapper.querySelectorAll('.marquee-item-row');
            if (rows.length > 1) {
                removeBtn.closest('.marquee-item-row').remove();
            } else {
                // Clear the last remaining input instead of removing it
                const input = wrapper.querySelector('input[name="title[]"]');
                if (input) input.value = '';
            }
        });
    });
</script>
</body>

</html>
