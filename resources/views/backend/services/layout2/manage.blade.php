<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

    @include('components.backend.header')

    <!--start sidebar wrapper-->
    @include('components.backend.sidebar')
    <!--end sidebar wrapper-->

    @php $imgBase = 'services/layout2/'; @endphp

        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6"><h4>{{ $product->name }} — Page (Services 2 layout)</h4></div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Services</li>
                    <li class="breadcrumb-item">{{ optional($product->serviceCategory)->name }}</li>
                    <li class="breadcrumb-item active">{{ $product->name }}</li>
                </ol>
                </div>
              </div>
            </div>
          </div>
          <!-- Container-fluid starts-->
          <div class="container-fluid">
            <form class="needs-validation custom-input banner-form" novalidate method="POST" enctype="multipart/form-data"
                  action="{{ route('service-layout2.update', $product->id) }}">
                @csrf
                @method('PUT')

                @include('backend.services._switcher')

                <!-- ===== SECTION 1 : BANNER ===== -->
                <div class="card">
                    <div class="card-header"><h4>1. Banner</h4>
                        <p class="f-m-light mt-1 mb-0">Breadcrumb labels + background image. Title uses the product name (<b>{{ $product->name }}</b>).</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-4">
                            <label class="form-label" for="banner_breadcrumb_parent">Breadcrumb Parent</label>
                            <input class="form-control" id="banner_breadcrumb_parent" type="text" name="banner_breadcrumb_parent" value="{{ old('banner_breadcrumb_parent', $page->banner_breadcrumb_parent ?? 'Services') }}" placeholder="e.g. Services">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="banner_breadcrumb_child">Breadcrumb Sub-parent</label>
                            <input class="form-control" id="banner_breadcrumb_child" type="text" name="banner_breadcrumb_child" value="{{ old('banner_breadcrumb_child', $page->banner_breadcrumb_child ?? optional($product->serviceCategory)->name) }}" placeholder="e.g. SEBI Regulated Services">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="banner_background_image">Background Image</label>
                            <input class="form-control single-image-input" id="banner_background_image" type="file" name="banner_background_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->banner_background_image)<img src="{{ asset($imgBase.'banner/'.$page->banner_background_image) }}" alt="bg">@endif</div>
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 2 : NATURE OF WORK ===== -->
                <div class="card">
                    <div class="card-header"><h4>2. Nature Of Work</h4>
                        <p class="f-m-light mt-1 mb-0">Left image, heading and description.</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-6">
                            <label class="form-label" for="nature_image">Image</label>
                            <input class="form-control single-image-input" id="nature_image" type="file" name="nature_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->nature_image)<img src="{{ asset($imgBase.'nature/'.$page->nature_image) }}" alt="nature">@endif</div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="nature_heading">Heading</label>
                            <input class="form-control" id="nature_heading" type="text" name="nature_heading" value="{{ old('nature_heading', $page->nature_heading ?? 'Nature Of Work') }}" placeholder="e.g. Nature Of Work">
                            <label class="form-label mt-3" for="nature_description">Description</label>
                            <textarea class="form-control rich-editor" id="nature_description" name="nature_description" rows="5" placeholder="Paragraphs">{{ old('nature_description', $page->nature_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 3 : PROCESS & EXECUTION ===== -->
                <div class="card">
                    <div class="card-header"><h4>3. Process &amp; Execution</h4>
                        <p class="f-m-light mt-1 mb-0">Left image, heading and the points list.</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-6">
                            <label class="form-label" for="process_image">Image</label>
                            <input class="form-control single-image-input" id="process_image" type="file" name="process_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->process_image)<img src="{{ asset($imgBase.'process/'.$page->process_image) }}" alt="process">@endif</div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="process_heading">Heading</label>
                            <input class="form-control" id="process_heading" type="text" name="process_heading" value="{{ old('process_heading', $page->process_heading ?? 'Process & Execution') }}" placeholder="e.g. Process & Execution">
                            <label class="form-label mt-3" for="process_points">Points <span class="text-secondary">(use the bullet-list button)</span></label>
                            <textarea class="form-control rich-editor" id="process_points" name="process_points" rows="6" placeholder="Add bullet points">{{ old('process_points', $page->process_points ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 4 : KEY FACTS ===== -->
                <div class="card">
                    <div class="card-header"><h4>4. Key Facts</h4>
                        <p class="f-m-light mt-1 mb-0">Left image, heading and the points list.</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-6">
                            <label class="form-label" for="keyfacts_image">Image</label>
                            <input class="form-control single-image-input" id="keyfacts_image" type="file" name="keyfacts_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->keyfacts_image)<img src="{{ asset($imgBase.'keyfacts/'.$page->keyfacts_image) }}" alt="keyfacts">@endif</div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="keyfacts_heading">Heading</label>
                            <input class="form-control" id="keyfacts_heading" type="text" name="keyfacts_heading" value="{{ old('keyfacts_heading', $page->keyfacts_heading ?? 'Key Facts') }}" placeholder="e.g. Key Facts">
                            <label class="form-label mt-3" for="keyfacts_points">Points <span class="text-secondary">(use the bullet-list button)</span></label>
                            <textarea class="form-control rich-editor" id="keyfacts_points" name="keyfacts_points" rows="6" placeholder="Add bullet points">{{ old('keyfacts_points', $page->keyfacts_points ?? '') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="keyfacts_note">Note Strip <span class="text-secondary">(optional)</span></label>
                            <textarea class="form-control rich-editor" id="keyfacts_note" name="keyfacts_note" rows="2" placeholder="e.g. Catalyst is presently handling assignments in excess of Rs. 8,50,000 Crores.">{{ old('keyfacts_note', $page->keyfacts_note ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body d-flex justify-content-end">
                        <button class="btn btn-primary px-5" type="submit">{{ $page ? 'Update Page' : 'Save Page' }}</button>
                    </div>
                </div>
            </form>
          </div>
        </div>
        <!-- footer start-->
        @include('components.backend.footer')
        </div>
        </div>

       @include('components.backend.main-js')

@include('backend.services.layout2._manage-js')
</body>

</html>
