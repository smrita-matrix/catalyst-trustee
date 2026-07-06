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
                  <h4>Edit Marquee Item</h4>
                </div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                    <a href="{{ route('marquee-inner.index') }}">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Edit Marquee Item</li>
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
                        <h4>Marquee Strip Item</h4>
                        <p class="f-m-light mt-1">Update the scrolling marquee item text.</p>
                    </div>
                    <div class="card-body">
                        <form class="row g-4 needs-validation custom-input banner-form" novalidate action="{{ route('marquee-inner.update', $marquee_inner->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Marquee Item -->
                            <div class="col-12">
                                <label class="form-label" for="title">Marquee Item <span class="txt-danger">*</span></label>
                                <input class="form-control" id="title" type="text" name="title" value="{{ old('title', $marquee_inner->title) }}" placeholder="e.g. Debenture Trustee Services (Listed)" required>
                                <div class="invalid-feedback">Please enter the marquee item text.</div>
                            </div>

                            <!-- Form Actions -->
                            <div class="col-12 d-flex justify-content-end gap-2 border-top pt-4 mt-2">
                                <a href="{{ route('marquee-inner.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
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
