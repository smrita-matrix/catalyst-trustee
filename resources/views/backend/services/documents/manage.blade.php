<!doctype html>
<html lang="en">

<head>
    @include('components.backend.head')
</head>

    @include('components.backend.header')
    @include('components.backend.sidebar')

    @php $imgBase = 'service-uploads/documents/'; @endphp

        <div class="page-body">
          <div class="container-fluid">
            <div class="page-title">
              <div class="row">
                <div class="col-6"><h4>{{ $product->name }} &mdash; Page (Documents layout)</h4></div>
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

          <div class="container-fluid">

            {{-- The page around the documents. --}}
            <form class="needs-validation custom-input banner-form" novalidate method="POST" enctype="multipart/form-data"
                  action="{{ route('service-documents.update', $product->id) }}">
                @csrf
                @method('PUT')

                @include('backend.services._switcher')

                <div class="card">
                    <div class="card-header"><h4>The page</h4>
                        <p class="f-m-light mt-1 mb-0">The banner, the heading and the line under it.</p></div>
                    <div class="card-body row g-4">
                        <div class="col-lg-3">
                            <label class="form-label" for="banner_title">Banner title</label>
                            <input class="form-control" id="banner_title" type="text" name="banner_title" value="{{ old('banner_title', $page->banner_title ?? $product->name) }}" placeholder="{{ $product->name }}">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="banner_breadcrumb_parent">Breadcrumb Parent</label>
                            <input class="form-control" id="banner_breadcrumb_parent" type="text" name="banner_breadcrumb_parent" value="{{ old('banner_breadcrumb_parent', $page->banner_breadcrumb_parent ?? 'Services') }}" placeholder="e.g. Services">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="banner_breadcrumb_child">Breadcrumb Sub-parent</label>
                            <input class="form-control" id="banner_breadcrumb_child" type="text" name="banner_breadcrumb_child" value="{{ old('banner_breadcrumb_child', $page->banner_breadcrumb_child ?? optional($product->serviceCategory)->name) }}" placeholder="e.g. GIFT City Services">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="banner_background_image">Background picture</label>
                            <input class="form-control single-image-input" id="banner_background_image" type="file" name="banner_background_image" accept=".jpg,.jpeg,.png,.webp">
                            <div class="img-preview mt-2">@if($page && $page->banner_background_image)<img src="{{ asset($imgBase.'banner/'.$page->banner_background_image) }}" alt="bg">@endif</div>
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="page_title">Heading</label>
                            <input class="form-control" id="page_title" type="text" name="page_title" value="{{ old('page_title', $page->page_title ?? '') }}" placeholder="{{ $product->name }}">
                        </div>
                        <div class="col-lg-6">
                            <label class="form-label" for="page_intro">Line under the heading</label>
                            <input class="form-control" id="page_intro" type="text" name="page_intro" value="{{ old('page_intro', $page->page_intro ?? '') }}" placeholder="Optional">
                        </div>
                        <div class="col-12 d-flex justify-content-end">
                            <button class="btn btn-primary px-4" type="submit">Save the page</button>
                        </div>
                    </div>
                </div>
            </form>

            {{-- The documents themselves, one row at a time. --}}
            <div class="card">
                <div class="card-header"><h4>Documents</h4>
                    <p class="f-m-light mt-1 mb-0">Each one becomes a card on the page. Attach a PDF, or give a link to one held elsewhere.</p></div>
                <div class="card-body">
                    <div class="table-responsive custom-scrollbar">
                        <table class="table table-bordered align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:55px;">#</th>
                                    <th>Name</th>
                                    <th style="width:300px;">Description</th>
                                    <th style="width:220px;">PDF or link</th>
                                    <th style="width:90px;">Order</th>
                                    <th style="width:170px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($documents as $i => $doc)
                                <tr>
                                    <form method="POST" enctype="multipart/form-data" action="{{ route('service-documents.save', [$product->id, $doc->id]) }}">
                                        @csrf @method('PUT')
                                        <td>{{ $i + 1 }}</td>
                                        <td><input class="form-control" type="text" name="title" value="{{ $doc->title }}" required></td>
                                        <td><textarea class="form-control" name="description" rows="2">{{ $doc->description }}</textarea></td>
                                        <td>
                                            <input class="form-control mb-1" type="file" name="document_file" accept=".pdf">
                                            @if ($doc->document_file)
                                            <small class="d-block"><a href="{{ $doc->document_url }}" target="_blank" rel="noopener noreferrer">current PDF</a></small>
                                            @endif
                                            <input class="form-control mt-1" type="text" name="document_link" value="{{ $doc->document_link }}" placeholder="or a link">
                                        </td>
                                        <td><input class="form-control" type="number" name="sort_order" value="{{ $doc->sort_order }}"></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" type="submit">Save</button>
                                    </form>
                                            <form method="POST" action="{{ route('service-documents.remove', [$product->id, $doc->id]) }}"
                                                  onsubmit="return confirm('Remove this document?');" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button class="btn btn-outline-danger btn-sm">Remove</button>
                                            </form>
                                        </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="text-center text-secondary py-4">No documents yet. Add the first one below.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <hr class="mt-4">
                    <h5 class="mb-3">Add a document</h5>
                    <form class="row g-3" method="POST" enctype="multipart/form-data" action="{{ route('service-documents.add', $product->id) }}">
                        @csrf
                        <div class="col-lg-3">
                            <label class="form-label" for="new_title">Name <span class="txt-danger">*</span></label>
                            <input class="form-control" id="new_title" type="text" name="title" required placeholder="e.g. Complaint Handling and Grievance Policy">
                        </div>
                        <div class="col-lg-3">
                            <label class="form-label" for="new_description">Description</label>
                            <input class="form-control" id="new_description" type="text" name="description" placeholder="Optional">
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label" for="new_file">PDF</label>
                            <input class="form-control" id="new_file" type="file" name="document_file" accept=".pdf">
                        </div>
                        <div class="col-lg-2">
                            <label class="form-label" for="new_link">Or a link</label>
                            <input class="form-control" id="new_link" type="text" name="document_link" placeholder="https://...">
                        </div>
                        <div class="col-lg-1">
                            <label class="form-label" for="new_order">Order</label>
                            <input class="form-control" id="new_order" type="number" name="sort_order" value="0">
                        </div>
                        <div class="col-lg-1 d-flex align-items-end">
                            <button class="btn btn-primary w-100" type="submit">Add</button>
                        </div>
                    </form>
                </div>
            </div>

          </div>
        </div>

        @include('components.backend.footer')
        </div>
        </div>

       @include('components.backend.main-js')
</body>

</html>
