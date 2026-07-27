<!-- latest jquery-->
<script src="{{ asset('admin/assets/js/jquery.min.js') }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('admin/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- feather icon js-->
    <script src="{{ asset('admin/assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <!-- scrollbar js-->
    <script src="{{ asset('admin/assets/js/scrollbar/simplebar.js') }}"></script>
    <script src="{{ asset('admin/assets/js/scrollbar/custom.js') }}?v=sidebarfix2"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('admin/assets/js/config.js') }}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('admin/assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('admin/assets/js/sidebar-pin.js') }}?v=sidebarfix2"></script>
    <script src="{{ asset('admin/assets/js/slick/slick.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/slick/slick.js') }}"></script>
    <script src="{{ asset('admin/assets/js/header-slick.js') }}"></script>
    <script src="{{ asset('admin/assets/js/editors/quill.js') }}"></script>
    <script src="{{ asset('admin/assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <!-- calendar js-->
    <!-- <script src="{{ asset('admin/assets/js/dashboard/default.js') }}"></script> -->
    <script src="{{ asset('admin/assets/js/notify/index.js') }}"></script>
    <script src="{{ asset('admin/assets/js/typeahead/handlebars.js') }}"></script>
    <script src="{{ asset('admin/assets/js/typeahead/typeahead.bundle.js') }}"></script>
    <script src="{{ asset('admin/assets/js/typeahead/typeahead.custom.js') }}"></script>
    <script src="{{ asset('admin/assets/js/typeahead-search/handlebars.js') }}"></script>
    <script src="{{ asset('admin/assets/js/typeahead-search/typeahead-custom.js') }}"></script>
    <script src="{{ asset('admin/assets/js/height-equal.js') }}"></script>
    <!-- Plugins JS Ends-->

    <script src="{{ asset('admin/assets/js/datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/datatable/datatables/datatable.custom.js') }}"></script>
            <script src="https://cdn.ckeditor.com/ckeditor5/41.4.2/classic/ckeditor.js"></script>

    <!-- Theme js-->
    <script src="{{ asset('admin/assets/js/script.js') }}?v=sidebarfix2"></script>

    <script>if (typeof WOW !== 'undefined') { new WOW().init(); }</script>

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
    // Initialise CKEditor only where the target textarea exists (guards other pages).
    ['#editor', '#editor1', '#editor3'].forEach(function (sel) {
        var el = document.querySelector(sel);
        if (!el || typeof ClassicEditor === 'undefined') return;
        var editor;
        ClassicEditor.create(el).then(function (ed) { editor = ed; }).catch(function (e) { console.error(e); });
        var form = el.closest('form');
        if (form) form.addEventListener('submit', function () { if (editor) el.value = editor.getData(); });
    });
</script>
<script>
  if (typeof $ !== 'undefined' && $('#summernote').length) {
    $('#summernote').summernote({ height: 200, focus: true });
  }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var passwordInput = document.querySelector('input[name="password"]');
        var showHideSpan = document.querySelector('.show-hide .show');
        if (!showHideSpan || !passwordInput) return;
        showHideSpan.addEventListener('click', function () {
            passwordInput.type = (passwordInput.type === 'password') ? 'text' : 'password';
        });
    });
</script>

   <!-- Toastr Messages-->
    @if (session('message'))
    <script>
        (function ($) {
            "use strict";
            var notify = $.notify(
                '<i class="fa fa-bell-o"></i><strong>{{ session('message') }}</strong>',
                {
                    type: "theme",
                    allow_dismiss: true,
                    delay: 5000,
                    showProgressbar: true,
                    timer: 300,
                    animate: {
                        enter: "animated fadeInDown",
                        exit: "animated fadeOutUp",
                    },
                }
            );
        })(jQuery);
    </script>
@endif

@if ($errors->any())
    <script>
        (function ($) {
            "use strict";
            var notify = $.notify(
               '<i class="fa fa-bell-o"></i><strong>@foreach ($errors->all() as $error) {{ $error }}<br> @endforeach</strong>',
                {
                    type: "theme",
                    allow_dismiss: true,
                    delay: 5000,
                    showProgressbar: true,
                    timer: 300,
                    animate: {
                        enter: "animated fadeInDown",
                        exit: "animated fadeOutUp",
                    },
                }
            );
        })(jQuery);
    </script>
@endif
