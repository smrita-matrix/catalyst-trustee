{{--
    One-click Shown / Hidden switch for anything that appears in the website menu.

    Saves opening the edit form just to take one item off the menu. Expects:
      $item - the row (needs a status column)
      $url  - the POST address that flips it
--}}
<form action="{{ $url }}" method="POST" class="d-inline">
    @csrf
    <button type="submit"
            class="btn btn-sm {{ $item->status ? 'btn-success' : 'btn-outline-secondary' }}"
            style="min-width:96px;"
            title="{{ $item->status
                        ? 'Showing on the website menu — click to hide it'
                        : 'Hidden from the website menu — click to show it' }}">
        <i class="fa {{ $item->status ? 'fa-eye' : 'fa-eye-slash' }}"></i>
        {{ $item->status ? 'Shown' : 'Hidden' }}
    </button>
</form>
