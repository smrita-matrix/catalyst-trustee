{{-- Layout 7 — a searchable table (Revision in Credit Ratings)

     Used where there are far too many rows for cards: 179 credit rating
     revisions read as a table but would be an unusable wall of cards. The
     issuer name links to its document; the other two columns come from the
     document's own fields. --}}
<section class="credit-rating-section notice-table-section">
  <div class="container">
    <div class="row">
      <div class="col-sm-12">
        <div class="heading" data-aos="fade-up" data-aos-duration="1000">
          <h2>{{ $category->page_title ?: $category->name }}</h2>
        </div>
      </div>
    </div>

    @include('frontend.public-notice.layouts._alert')

    @if($notices->count())
    <div class="row">
      <div class="col-sm-12">

        {{-- With this many rows, finding one by scrolling is impractical. --}}
        <div class="notice-table-search">
          <label for="notice-table-filter" class="sr-only">Search this table</label>
          <input type="search" id="notice-table-filter" class="form-control"
                 placeholder="Search by issuer, agency or date…" autocomplete="off">
          <span class="notice-table-count" data-table-count>{{ $notices->count() }} entries</span>
        </div>

        <div class="notice-table-wrap">
          <table class="notice-table" id="notice-table">
            <thead>
              <tr>
                <th scope="col">{{ $category->col_one_label ?: 'Issuer Name' }}</th>
                <th scope="col">{{ $category->col_two_label ?: 'Issued By' }}</th>
                <th scope="col">{{ $category->col_three_label ?: 'Date' }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($notices as $notice)
              @php $url = $notice->document_url; @endphp
              <tr>
                <td data-label="{{ $category->col_one_label ?: 'Issuer Name' }}">
                  @if($url)
                    <a href="{{ $url }}" target="_blank" rel="noopener noreferrer">{{ $notice->title }}</a>
                  @else
                    {{ $notice->title }}
                  @endif
                </td>
                <td data-label="{{ $category->col_two_label ?: 'Issued By' }}">{{ $notice->category }}</td>
                <td data-label="{{ $category->col_three_label ?: 'Date' }}">{{ $notice->period }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <p class="notice-table-empty" data-table-empty hidden>No entries match your search.</p>

      </div>
    </div>
    @else
      @include('frontend.public-notice.layouts._empty')
    @endif

  </div>
</section>
