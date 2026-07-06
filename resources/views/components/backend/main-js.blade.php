<!-- latest jquery-->
<script src="{{ asset('admin/assets/js/jquery.min.js') }}"></script>
    <!-- Bootstrap js-->
    <script src="{{ asset('admin/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <!-- feather icon js-->
    <script src="{{ asset('admin/assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('admin/assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <!-- scrollbar js-->
    <script src="{{ asset('admin/assets/js/scrollbar/simplebar.js') }}"></script>
    <script src="{{ asset('admin/assets/js/scrollbar/custom.js') }}"></script>
    <!-- Sidebar jquery-->
    <script src="{{ asset('admin/assets/js/config.js') }}"></script>
    <!-- Plugins JS start-->
    <script src="{{ asset('admin/assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('admin/assets/js/sidebar-pin.js') }}"></script>
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
    <script src="{{ asset('admin/assets/js/script.js') }}"></script>

    <script>new WOW().init();</script>

    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let editor;
    ClassicEditor
        .create(document.querySelector('#editor'))
        .then(newEditor => {
            editor = newEditor;
        })
        .catch(error => { console.error(error); });

    // Attach submit listener to the form
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Update textarea value with CKEditor data
        if (editor) {
            document.querySelector('#editor').value = editor.getData();
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let editor;
    ClassicEditor
        .create(document.querySelector('#editor1'))
        .then(newEditor => {
            editor = newEditor;
        })
        .catch(error => { console.error(error); });

    // Attach submit listener to the form
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Update textarea value with CKEditor data
        if (editor) {
            document.querySelector('#editor1').value = editor.getData();
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    let editor;
    ClassicEditor
        .create(document.querySelector('#editor3'))
        .then(newEditor => {
            editor = newEditor;
        })
        .catch(error => { console.error(error); });

    // Attach submit listener to the form
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Update textarea value with CKEditor data
        if (editor) {
            document.querySelector('#editor3').value = editor.getData();
        }
    });
});
</script>
<script>
  $(document).ready(function() {
    $('#summernote').summernote({
      height: 200, // Adjust height as needed
      focus: true   // Focus the editor when initialized
    });
  });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.querySelector('input[name="password"]');
        const showHideSpan = document.querySelector('.show-hide .show');

        showHideSpan.addEventListener('click', function () {
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
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


