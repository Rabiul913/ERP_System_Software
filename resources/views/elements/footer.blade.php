<script src="https://unpkg.com/axios@1.1.2/dist/axios.min.js"></script>
<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.min.js') }}"></script>
<script src="{{ asset('js/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
<script src="{{ asset('js/modernizr.js') }}"></script>
<script src="{{ asset('js/css-scrollbars.js') }}"></script>
<script src="{{ asset('js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/SmoothScroll.js') }}"></script>
<script src="{{ asset('js/jquery.mCustomScrollbar.concat.min.js') }}"></script>
<script src="{{ asset('js/custom-function.js') }}"></script>
@yield('script')
<script src="{{ asset('js/pcoded.min.js') }}"></script>
<script src="{{ asset('js/vertical-layout.min.js') }}"></script>
<script src="{{ asset('js/script.js') }}"></script>
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/Datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/Datatables/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/Datatables/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/Datatables/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('js/Datatables/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/Datatables/buttons.print.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
<script>
    $(document).ready(function() {
        $(".mtl-summernote").summernote({
            placeholder: "Enter content here...",
            height: 200,
            fullscreen: true,
            toolbar: [
                ['style', ['bold', 'italic', 'underline']],
                ['font', ['strikethrough', 'superscript', 'subscript']],
                ['color', []],
                // ['insert', ['picture']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['fontsize', ['fontsize']],
                ['link', ['link']],
                ['view', ['fullscreen', 'codeview']],
            ],
        });
        $(".note-icon-caret").hide();
        $(".note-view button:last").hide();
        $(".delete").click(function() {
            if (!confirm("Are you sure! you want to delete this data?")) {
                return false;
            }
        });
        $('.select2').select2({
            'placeholder': 'Please Select',
        });
        $('#dataTable').DataTable();

        $('.date-picker').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true,
            showOtherMonths: true
        });

        // alert duration set
        const duration = 20000;
        setTimeout(function() {
            const alertBox = document.querySelector(".alert");
            if (alertBox) {
                alertBox.style.display = "none";
            }
        }, duration);
    });

    //file upload validations
    function FileUploaderValidation(that, filesize_mb, accepted_files) {
        const file = that.files[0];
        const reader = new FileReader();
        const fileError = $("#file-error");

        const fileType = file.type;
        const fileSize = file.size / 1024 / 1024; // convert to MB
        let readershow = true;
        if (!file) {
            fileError.text("No file selected");
            readershow = false;
            return;
        }

        if (!accepted_files.includes(fileType)) {
            fileError.text("Invalid file type");
            that.value = ""; // clear the file input
            readershow = false;
            return;
        }

        if (fileSize > filesize_mb) {
            fileError.text(`File size exceeds ${filesize_mb}MB`);
            that.value = ""; // clear the file input
            readershow = false;
            return;
        }

        fileError.text(""); // clear any previous errors

        if (readershow) {
            reader.onload = function(event) {
                const imageUrl = event.target.result;
                $(".selected-file").html(
                    '<img src="' + imageUrl + '" alt="Selected File" class="w-50">'
                );
            };

            reader.readAsDataURL(file);
        }


    }
</script>
