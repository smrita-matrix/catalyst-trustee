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
                  <h4>Edit Testimonials</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('testimonial-details.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Testimonials</li>
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
                        <h4>Testimonials</h4>
                        <p class="f-m-light mt-1">Update the section heading and the testimonial slides shown on the home page.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('testimonial-details.update', $testimonial->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            @php
                                $heading = $testimonial->heading;
                                // Re-populate from the submitted values after a failed validation,
                                // otherwise from what is saved.
                                $saved = $testimonial->items ?: [['text' => '', 'name' => '', 'designation' => '', 'company' => '']];
                                $oldTexts = old('item_text');
                                $rows = [];
                                if ($oldTexts !== null) {
                                    foreach ($oldTexts as $i => $t) {
                                        $rows[] = [
                                            'text'        => $t,
                                            'name'        => old('item_name')[$i] ?? '',
                                            'designation' => old('item_designation')[$i] ?? '',
                                            'company'     => old('item_company')[$i] ?? '',
                                        ];
                                    }
                                } else {
                                    $rows = $saved;
                                }
                            @endphp

                            @include('backend.home-page.testimonial-details._form')

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('testimonial-details.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
</body>

</html>
