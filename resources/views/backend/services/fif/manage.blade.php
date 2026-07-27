<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

    @include('components.backend.header')

    <!--start sidebar wrapper-->
    @include('components.backend.sidebar')
    <!--end sidebar wrapper-->

    @php $imgBase = 'services/fif/'; @endphp

        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6"><h4>{{ $product->name }} — Page (Family Investment Funds layout)</h4></div>
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
                  action="{{ route('service-fif.update', $product->id) }}">
                @csrf
                @method('PUT')

                @include('backend.services._switcher')

                <!-- ===== SECTION 1 : BANNER ===== -->
                <div class="card">
                    <div class="card-header"><h4>1. Banner</h4>
                        <p class="f-m-light mt-1 mb-0">Breadcrumb + background image. Title uses the product name (<b>{{ $product->name }}</b>).</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-4">
                            <label class="form-label" for="banner_breadcrumb_parent">Breadcrumb Parent</label>
                            <input class="form-control" type="text" name="banner_breadcrumb_parent" value="{{ old('banner_breadcrumb_parent', $page->banner_breadcrumb_parent ?? 'Services') }}" placeholder="e.g. Services">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label" for="banner_breadcrumb_child">Breadcrumb Sub-parent</label>
                            <input class="form-control" type="text" name="banner_breadcrumb_child" value="{{ old('banner_breadcrumb_child', $page->banner_breadcrumb_child ?? optional($product->serviceCategory)->name) }}" placeholder="e.g. GIFT City Services">
                        </div>
                        <div class="col-lg-4">
                            <label class="form-label">Background Image</label>
                            <input class="form-control single-image-input" type="file" name="banner_background_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->banner_background_image)<img src="{{ asset($imgBase.'banner/'.$page->banner_background_image) }}" alt="bg">@endif</div>
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 2 : INTRO ===== -->
                <div class="card">
                    <div class="card-header"><h4>2. Intro</h4></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-6">
                            <label class="form-label">Image</label>
                            <input class="form-control single-image-input" type="file" name="intro_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->intro_image)<img src="{{ asset($imgBase.'intro/'.$page->intro_image) }}" alt="intro">@endif</div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="intro_subheading">Sub-heading</label>
                            <input class="form-control" type="text" name="intro_subheading" value="{{ old('intro_subheading', $page->intro_subheading ?? '') }}" placeholder="e.g. Family Office product offering through FIF in IFSC Gift City">
                            <label class="form-label mt-3">Description</label>
                            <textarea class="form-control rich-editor" name="intro_description" rows="4">{{ old('intro_description', $page->intro_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 3 : DEFINITION / CONCEPT ===== -->
                <div class="card">
                    <div class="card-header"><h4>3. Definition / Concept</h4>
                        <p class="f-m-light mt-1 mb-0">Left image + description (e.g. Definition of Single-Family, Concept of FIF). Use the editor for headings and paragraphs.</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-4">
                            <label class="form-label">Image</label>
                            <input class="form-control single-image-input" type="file" name="definition_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->definition_image)<img src="{{ asset($imgBase.'definition/'.$page->definition_image) }}" alt="def">@endif</div>
                        </div>
                        <div class="col-lg-8">
                            <label class="form-label">Description</label>
                            <textarea class="form-control rich-editor" name="definition_description" rows="8">{{ old('definition_description', $page->definition_description ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 4 : PROCESS TABS ===== -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div><h4>4. Process (Tabs)</h4>
                            <p class="f-m-light mt-1 mb-0">Left nav tabs (e.g. Process of Setting up FIF, Corpus, Investment Threshold, Benefits).</p></div>
                        <button type="button" id="btn-add-tab" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Section Heading <span class="text-secondary">(optional)</span></label>
                            <input class="form-control" type="text" name="process_heading" value="{{ old('process_heading', $page->process_heading ?? '') }}" placeholder="e.g. Process of Setting up FIF in IFSC">
                        </div>
                        <div id="tabs-wrap">
                            @php $tabs = old('tab_title') ? collect(old('tab_title'))->map(fn($t,$i)=>['title'=>$t,'description'=>old('tab_description')[$i]??'','points'=>old('tab_points')[$i]??''])->all() : ($page->process_tabs ?? [['title'=>'','description'=>'','points'=>'']]); @endphp
                            @foreach($tabs as $i => $t)
                            <div class="tab-item border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2"><b class="tab-index">Tab {{ $i + 1 }}</b>
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-tab"><i class="fa fa-trash"></i> Remove</button></div>
                                <div class="row g-3">
                                    <div class="col-lg-8">
                                        <label class="form-label">Tab Title</label>
                                        <input class="form-control" type="text" name="tab_title[]" value="{{ $t['title'] ?? '' }}" placeholder="e.g. Corpus">
                                    </div>
                                    <div class="col-lg-4">
                                        <label class="form-label">Image <span class="text-secondary">(optional)</span></label>
                                        <input class="form-control single-image-input" type="file" name="tab_image[]" accept=".jpg,.jpeg,.png,.webp,.svg">
                                        <input type="hidden" name="tab_existing_image[]" value="{{ $t['image'] ?? '' }}">
                                        <div class="img-preview mt-2">@if(!empty($t['image']))<img src="{{ asset($imgBase.'process/'.$t['image']) }}" alt="tab">@endif</div>
                                    </div>
                                </div>
                                <label class="form-label mt-2">Points <span class="text-secondary">(use the bullet-list button)</span></label>
                                <textarea class="form-control rich-editor" name="tab_points[]" rows="4">{{ $t['points'] ?? '' }}</textarea>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 5 : TAX COMPARISON ===== -->
                <div class="card">
                    <div class="card-header"><h4>5. Tax Comparison</h4>
                        <p class="f-m-light mt-1 mb-0">Intro text + the comparison table (edit the table's HTML).</p></div>
                    <div class="card-body row g-4">
                        <div class="col-12">
                            <label class="form-label">Intro Text</label>
                            <textarea class="form-control rich-editor" name="tax_intro" rows="2">{{ old('tax_intro', $page->tax_intro ?? '') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Comparison Table</label>
                            <textarea class="form-control rich-editor-table" name="tax_table_html" rows="10">{{ old('tax_table_html', $page->tax_table_html ?? '') }}</textarea>
                            <small class="text-secondary"><i class="fa fa-info-circle"></i> Use the <b>table</b> button in the toolbar to insert or edit the comparison table. It is styled automatically on the website.</small>
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 6 : FAMILY OFFICE SOLUTION ===== -->
                <div class="card">
                    <div class="card-header"><h4>6. Family Office Solution</h4></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-6">
                            <label class="form-label">Heading</label>
                            <input class="form-control" type="text" name="family_heading" value="{{ old('family_heading', $page->family_heading ?? '') }}" placeholder="e.g. Family Office Solution through FIF">
                            <label class="form-label mt-3">Description</label>
                            <textarea class="form-control rich-editor" name="family_description" rows="4">{{ old('family_description', $page->family_description ?? '') }}</textarea>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Structure Image</label>
                            <input class="form-control single-image-input" type="file" name="family_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->family_image)<img src="{{ asset($imgBase.'family/'.$page->family_image) }}" alt="family">@endif</div>
                        </div>
                    </div>
                </div>

                <!-- ===== SECTION 7 : CAPABILITIES ===== -->
                <div class="card">
                    <div class="card-header"><h4>7. Capabilities</h4></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-6">
                            <label class="form-label">Image</label>
                            <input class="form-control single-image-input" type="file" name="capabilities_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->capabilities_image)<img src="{{ asset($imgBase.'capabilities/'.$page->capabilities_image) }}" alt="cap">@endif</div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label">Heading</label>
                            <input class="form-control" type="text" name="capabilities_heading" value="{{ old('capabilities_heading', $page->capabilities_heading ?? '') }}" placeholder="e.g. Capabilities of Catalyst Trusteeship Limited">
                            <label class="form-label mt-3">Points <span class="text-secondary">(use the bullet-list button)</span></label>
                            <textarea class="form-control rich-editor" name="capabilities_points" rows="5">{{ old('capabilities_points', $page->capabilities_points ?? '') }}</textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Disclaimer <span class="text-secondary">(optional)</span></label>
                            <textarea class="form-control rich-editor" name="capabilities_disclaimer" rows="2">{{ old('capabilities_disclaimer', $page->capabilities_disclaimer ?? '') }}</textarea>
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

@include('backend.services.fif._manage-js')
</body>

</html>
