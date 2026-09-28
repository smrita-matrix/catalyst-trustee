<!doctype html>
<html lang="en">

<head>
  @include('components.backend.head')
</head>

@include('components.backend.header')
@include('components.backend.sidebar')

<div class="page-body">
  <div class="container-fluid">
    <div class="page-title">
      <div class="row">
        <div class="col-6"><h4>Grievance &mdash; Pages</h4></div>
        <div class="col-6">
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">
                <svg class="stroke-icon"><use href="{{ asset('admin/assets/svg/icon-sprite.svg#stroke-home') }}"></use></svg></a></li>
            <li class="breadcrumb-item">Grievance</li>
            <li class="breadcrumb-item active">Pages</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <div>
          <h4>Pages under Grievance</h4>
          <p class="f-m-light mt-1 mb-0">
            These are the entries in the Grievance menu, in the order shown here.
            A page can carry one of the two grievance forms, or none at all where
            it is simply something to read.
          </p>
        </div>
        <a href="{{ route('grievance-pages.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> Add Page</a>
      </div>

      <div class="card-body">
        <div class="table-responsive custom-scrollbar">
          <table class="table table-bordered align-middle">
            <thead class="table-light">
              <tr>
                <th style="width:55px;">#</th>
                <th>Title</th>
                <th style="width:230px;">Form on the page</th>
                <th style="width:90px;">Order</th>
                <th style="width:110px;">On the site</th>
                <th style="width:230px;">Action</th>
              </tr>
            </thead>
            <tbody>
              @forelse ($pages as $i => $page)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                  <b>{{ $page->title }}</b>
                  <div class="text-secondary small mt-1">/grievance/{{ $page->slug }}</div>
                </td>
                <td>
                  {{ \App\Models\GrievancePage::FORMS[$page->form_type] ?? 'No form' }}
                  @if ($page->opens_document)
                  <div class="text-secondary small mt-1">Opens the PDF straight away</div>
                  @endif
                </td>
                <td>{{ $page->sort_order }}</td>
                <td>
                  <a href="{{ route('grievance-pages.toggle', $page->id) }}"
                     class="badge {{ $page->status ? 'bg-success' : 'bg-secondary' }}"
                     title="Click to {{ $page->status ? 'hide it' : 'show it' }}">
                    {{ $page->status ? 'Shown' : 'Hidden' }}
                  </a>
                </td>
                <td>
                  <div class="d-flex flex-wrap gap-1">
                    <a href="{{ route('frontend.grievance_page', $page->slug) }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline-secondary btn-sm">View</a>
                    <a href="{{ route('grievance-pages.edit', $page->id) }}" class="btn btn-primary btn-sm">Edit</a>
                    <form action="{{ route('grievance-pages.destroy', $page->id) }}" method="POST"
                          onsubmit="return confirm('Delete this page? It will no longer appear in the menu.');">
                      @csrf @method('DELETE')
                      <button class="btn btn-outline-danger btn-sm">Delete</button>
                    </form>
                  </div>
                </td>
              </tr>
              @empty
              <tr><td colspan="6" class="text-center text-secondary py-4">No pages yet. Use <b>Add Page</b> to make the first one.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

@include('components.backend.footer')
@include('components.backend.main-js')
</html>
