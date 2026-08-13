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
                <div class="col-6"><h4>Edit Office</h4></div>
                <div class="col-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('contact.index') }}">Home</a></li>
                    <li class="breadcrumb-item active">Edit Office</li>
                </ol>
                </div>
              </div>
            </div>
          </div>
          <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                    <div class="card-header"><h4>Edit Office</h4></div>
                    <div class="card-body">
                        <form class="needs-validation custom-input" novalidate action="{{ route('contact.update', $office->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row g-4">
                                <div class="col-lg-4">
                                    <label class="form-label">Group <span class="txt-danger">*</span></label>
                                    <select class="form-control" name="type">
                                        @foreach (\App\Models\ContactOffice::TYPES as $val => $label)
                                            <option value="{{ $val }}" {{ old('type', $office->type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">City <span class="txt-danger">*</span></label>
                                    <input class="form-control" type="text" name="city" value="{{ old('city', $office->city) }}" required>
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">Order</label>
                                    <input class="form-control" type="number" name="sort_order" value="{{ old('sort_order', $office->sort_order) }}">
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">Role <span class="text-secondary">(Main office)</span></label>
                                    <input class="form-control" type="text" name="role" value="{{ old('role', $office->role) }}" placeholder="Corporate Office">
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">Tag <span class="text-secondary">(Branch, e.g. PAN India)</span></label>
                                    <input class="form-control" type="text" name="tag" value="{{ old('tag', $office->tag) }}">
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label">Contact / Phone <span class="text-secondary">(Main office)</span></label>
                                    <input class="form-control" type="text" name="contact" value="{{ old('contact', $office->contact) }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Address</label>
                                    <textarea class="form-control" name="address" rows="3">{{ old('address', $office->address) }}</textarea>
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Email</label>
                                    <input class="form-control" type="text" name="email" value="{{ old('email', $office->email) }}">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Map Link</label>
                                    <input class="form-control" type="text" name="map_link" value="{{ old('map_link', $office->map_link) }}" placeholder="https://maps...">
                                </div>
                                <div class="col-lg-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-control" name="status">
                                        <option value="1" {{ (int)$office->status === 1 ? 'selected' : '' }}>Shown</option>
                                        <option value="0" {{ (int)$office->status === 0 ? 'selected' : '' }}>Hidden</option>
                                    </select>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end gap-2 border-top pt-4 mt-4">
                                <a href="{{ route('contact.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
                                <button class="btn btn-primary px-5" type="submit">Update</button>
                            </div>
                        </form>
                    </div>
                    </div>
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
