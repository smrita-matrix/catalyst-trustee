<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

    @include('components.backend.header')

    <!--start sidebar wrapper-->
    @include('components.backend.sidebar')
    <!--end sidebar wrapper-->

    @php $imgBase = 'services/debenture-trustee-listed/'; @endphp

        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6">
                  <h4>{{ $product->name }} — Page (Layout 1)</h4>
                </div>
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
                  action="{{ route('service-layout1.update', $product->id) }}">
                @csrf
                @method('PUT')

                @include('backend.services._switcher')

                <!-- ================= SECTION 1 : BANNER ================= -->
                <div class="card">
                    <div class="card-header"><h4>1. Banner</h4>
                        <p class="f-m-light mt-1 mb-0">Breadcrumb labels + background image. Title uses the product name (<b>{{ $product->name }}</b>).</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-3">
                            <label class="form-label" for="banner_breadcrumb_parent">Breadcrumb Parent</label>
                            <input class="form-control" id="banner_breadcrumb_parent" type="text" name="banner_breadcrumb_parent" value="{{ old('banner_breadcrumb_parent', $page->banner_breadcrumb_parent ?? 'Services') }}" placeholder="e.g. Services">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="banner_breadcrumb_child">Breadcrumb Sub-parent</label>
                            <input class="form-control" id="banner_breadcrumb_child" type="text" name="banner_breadcrumb_child" value="{{ old('banner_breadcrumb_child', $page->banner_breadcrumb_child ?? optional($product->serviceCategory)->name) }}" placeholder="e.g. SEBI Regulated Services">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="banner_background_image">Background Image</label>
                            <input class="form-control single-image-input" id="banner_background_image" type="file" name="banner_background_image" accept=".jpg,.jpeg,.png,.webp">
                            <small class="d-block text-secondary mt-2"><i class="fa fa-info-circle"></i> Leave empty to keep the current image. Max 2MB.</small>
                        </div>
                        <div class="col-lg-6">
                            <div class="img-preview bg-image-preview">
                                @if($page && $page->banner_background_image)<img src="{{ asset($imgBase.'banner/'.$page->banner_background_image) }}" alt="bg">@endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ================= SECTION 2 : INTRO ================= -->
                <div class="card">
                    <div class="card-header"><h4>2. Intro (Overview)</h4>
                        <p class="f-m-light mt-1 mb-0">Image, heading, description and the "Our Expertise" list.</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-6">
                            <label class="form-label" for="intro_image">Image</label>
                            <input class="form-control single-image-input" id="intro_image" type="file" name="intro_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview bg-image-preview mt-2">
                                @if($page && $page->intro_image)<img src="{{ asset($imgBase.'intro/'.$page->intro_image) }}" alt="intro">@endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="intro_heading">Heading</label>
                            <input class="form-control" id="intro_heading" type="text" name="intro_heading" value="{{ old('intro_heading', $page->intro_heading ?? '') }}" placeholder="e.g. Debenture Trustee Services (Listed)">
                            <label class="form-label mt-3" for="intro_description">Description</label>
                            <textarea class="form-control rich-editor" id="intro_description" name="intro_description" rows="4" placeholder="Intro paragraph">{{ old('intro_description', $page->intro_description ?? '') }}</textarea>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="intro_expertise_heading">Expertise Heading</label>
                            <input class="form-control" id="intro_expertise_heading" type="text" name="intro_expertise_heading" value="{{ old('intro_expertise_heading', $page->intro_expertise_heading ?? 'Our Expertise') }}" placeholder="e.g. Our Expertise">
                        </div>
                        <div class="col-12">
                            <label class="form-label" for="intro_expertise_points">Expertise Points <span class="text-secondary">(use the bullet-list button in the editor)</span></label>
                            <textarea class="form-control rich-editor" id="intro_expertise_points" name="intro_expertise_points" rows="4" placeholder="Add bullet points">{{ old('intro_expertise_points', $page->intro_expertise_points ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ================= SECTION 3 : OUR SERVICES INCLUDE ================= -->
                <div class="card">
                    <div class="card-header"><h4>3. Our Services Include</h4>
                        <p class="f-m-light mt-1 mb-0">Side image, heading and the list of services.</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-6">
                            <label class="form-label" for="services_include_image">Image</label>
                            <input class="form-control single-image-input" id="services_include_image" type="file" name="services_include_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview bg-image-preview mt-2">
                                @if($page && $page->services_include_image)<img src="{{ asset($imgBase.'services-include/'.$page->services_include_image) }}" alt="services">@endif
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="services_include_heading">Heading</label>
                            <input class="form-control" id="services_include_heading" type="text" name="services_include_heading" value="{{ old('services_include_heading', $page->services_include_heading ?? 'Our Services Include') }}" placeholder="e.g. Our Services Include">
                            <label class="form-label mt-3" for="services_include_points">Points <span class="text-secondary">(use the bullet-list button in the editor)</span></label>
                            <textarea class="form-control rich-editor" id="services_include_points" name="services_include_points" rows="6" placeholder="Add bullet points">{{ old('services_include_points', $page->services_include_points ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- ================= SECTION 4 : WHY CATALYST (cards) ================= -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div><h4>4. Why Catalyst Trustee Services?</h4>
                            <p class="f-m-light mt-1 mb-0">Heading + cards (icon &amp; title). Add as many as you need.</p></div>
                        <button type="button" id="btn-add-card" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="why_heading">Section Heading</label>
                            <input class="form-control" id="why_heading" type="text" name="why_heading" value="{{ old('why_heading', $page->why_heading ?? 'Why Catalyst Trustee Services?') }}" placeholder="e.g. Why Catalyst Trustee Services?">
                        </div>
                        <div class="table-responsive custom-scrollbar">
                            <table class="table table-bordered align-middle" id="cards-table">
                                <thead class="table-light"><tr><th style="width:45px;">#</th><th style="width:220px;">Icon</th><th>Title</th><th style="width:55px;"></th></tr></thead>
                                <tbody id="cards-tbody">
                                    @php $cards = old('why_card_title') ? collect(old('why_card_title'))->map(fn($t,$i)=>['title'=>$t,'icon'=>old('why_card_existing_icon')[$i]??null])->all() : ($page->why_cards ?? [['title'=>'','icon'=>null]]); @endphp
                                    @foreach($cards as $i => $c)
                                    <tr class="card-row">
                                        <td class="row-index">{{ $i + 1 }}</td>
                                        <td>
                                            <input class="form-control mb-2 repeater-image-input" type="file" name="why_card_icon[]" accept=".png,.jpg,.jpeg,.webp,.svg">
                                            <input type="hidden" name="why_card_existing_icon[]" value="{{ $c['icon'] ?? '' }}">
                                            <div class="img-preview repeater-preview">@if(!empty($c['icon']))<img src="{{ asset($imgBase.'why/'.$c['icon']) }}" alt="icon">@endif</div>
                                        </td>
                                        <td><input class="form-control" type="text" name="why_card_title[]" value="{{ $c['title'] ?? '' }}" placeholder="e.g. Proven Market Leadership"></td>
                                        <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-card"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- ================= SECTION 5 : SERVICES OFFERED (tabs) ================= -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div><h4>5. Services Offered (Tabs)</h4>
                            <p class="f-m-light mt-1 mb-0">Each tab = title + image + its points. e.g. Advisory, Documentation, Operational.</p></div>
                        <button type="button" id="btn-add-tab" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="services_offered_heading">Section Heading</label>
                            <input class="form-control" id="services_offered_heading" type="text" name="services_offered_heading" value="{{ old('services_offered_heading', $page->services_offered_heading ?? 'Services Offered') }}" placeholder="e.g. Services Offered">
                        </div>
                        <div id="tabs-wrap">
                            @php $tabs = old('tab_title') ? collect(old('tab_title'))->map(fn($t,$i)=>['title'=>$t,'points'=>old('tab_points')[$i]??'','image'=>old('tab_existing_image')[$i]??null])->all() : ($page->services_offered_tabs ?? [['title'=>'','points'=>'','image'=>null]]); @endphp
                            @foreach($tabs as $i => $t)
                            <div class="tab-item border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <b class="tab-index">Tab {{ $i + 1 }}</b>
                                    <button type="button" class="btn btn-outline-danger btn-sm btn-remove-tab"><i class="fa fa-trash"></i> Remove</button>
                                </div>
                                <div class="row g-3">
                                    <div class="col-lg-4">
                                        <label class="form-label">Tab Title</label>
                                        <input class="form-control" type="text" name="tab_title[]" value="{{ $t['title'] ?? '' }}" placeholder="e.g. Advisory">
                                        <label class="form-label mt-2">Image</label>
                                        <input class="form-control repeater-image-input" type="file" name="tab_image[]" accept=".jpg,.jpeg,.png,.webp">
                                        <input type="hidden" name="tab_existing_image[]" value="{{ $t['image'] ?? '' }}">
                                        <div class="img-preview repeater-preview mt-2">@if(!empty($t['image']))<img src="{{ asset($imgBase.'services-offered/'.$t['image']) }}" alt="tab">@endif</div>
                                    </div>
                                    <div class="col-lg-8">
                                        <label class="form-label">Points <span class="text-secondary">(use the bullet-list button in the editor)</span></label>
                                        <textarea class="form-control rich-editor" name="tab_points[]" rows="6" placeholder="Add bullet points">{{ $t['points'] ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- ================= SECTION 6 : RECOGNITION & REGISTRATION ================= -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div><h4>6. Recognition &amp; Registration</h4>
                            <p class="f-m-light mt-1 mb-0">Heading, certificate images (Add More) and the note strip.</p></div>
                        <button type="button" id="btn-add-cert" class="btn btn-outline-primary btn-sm"><i class="fa fa-plus"></i> Add More</button>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label" for="recognition_heading">Section Heading</label>
                            <input class="form-control" id="recognition_heading" type="text" name="recognition_heading" value="{{ old('recognition_heading', $page->recognition_heading ?? 'Recognition & Registration') }}" placeholder="e.g. Recognition & Registration">
                        </div>
                        <div class="table-responsive custom-scrollbar">
                            <table class="table table-bordered align-middle" id="certs-table">
                                <thead class="table-light"><tr><th style="width:45px;">#</th><th style="width:240px;">Certificate Image</th><th>Alt / Caption (for accessibility)</th><th style="width:55px;"></th></tr></thead>
                                <tbody id="certs-tbody">
                                    @php $certs = old('certificate_alt') ? collect(old('certificate_alt'))->map(fn($a,$i)=>['alt'=>$a,'image'=>old('certificate_existing_image')[$i]??null])->all() : ($page->certificates ?? [['alt'=>'','image'=>null]]); @endphp
                                    @foreach($certs as $i => $c)
                                    <tr class="cert-row">
                                        <td class="row-index">{{ $i + 1 }}</td>
                                        <td>
                                            <input class="form-control mb-2 repeater-image-input" type="file" name="certificate_image[]" accept=".jpg,.jpeg,.png,.webp">
                                            <input type="hidden" name="certificate_existing_image[]" value="{{ $c['image'] ?? '' }}">
                                            <div class="img-preview repeater-preview">@if(!empty($c['image']))<img src="{{ asset($imgBase.'certificates/'.$c['image']) }}" alt="cert">@endif</div>
                                        </td>
                                        <td><input class="form-control" type="text" name="certificate_alt[]" value="{{ $c['alt'] ?? '' }}" placeholder="e.g. SEBI Certificate of Registration"></td>
                                        <td class="text-center"><button type="button" class="btn btn-outline-danger btn-sm btn-remove-cert"><i class="fa fa-trash"></i></button></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3">
                            <label class="form-label" for="recognition_note">Note Strip</label>
                            <textarea class="form-control rich-editor" id="recognition_note" name="recognition_note" rows="3" placeholder="e.g. Catalyst is presently handling trusteeship assignments ... in excess of Rs. 8,50,000 Crores.">{{ old('recognition_note', $page->recognition_note ?? '') }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="card">
                    <div class="card-body d-flex justify-content-end gap-2">
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

@include('backend.services.debenture-trustee-listed-details._manage-js')
</body>

</html>
