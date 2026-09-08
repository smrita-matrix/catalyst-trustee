{{-- A list of the site's own page addresses, offered as you type in a link box.

     Anything can still be typed - a full web address, an email link - the
     list is only there so the site's own pages do not have to be remembered.

     Use it as:
       <input list="site-pages" name="button_link" ...>
       @include('components.backend.link-picker')
--}}
<datalist id="site-pages">
  @foreach (site_page_links() as $path => $name)
  <option value="/{{ $path }}">{{ $name }}</option>
  @endforeach
</datalist>
