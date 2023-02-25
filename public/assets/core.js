$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            async: true,
            cache: false,
        }
    });

    // Variables
    let uploadedImage = $('#uploaded-image')

    const bootstrapSelect = $('.selectpicker'),
        flatpickr = $('.flatpickr'),
        imageFileInput = $('.image-file-input'),
        imageFileReset = $('.image-file-reset');

    // Plugins
    if (bootstrapSelect.length) {
        bootstrapSelect.selectpicker()
    }

    if (typeof flatpickr !== undefined) {
        flatpickr.flatpickr({
            enableTime: true,
            altInput: true,
            altFormat: 'd-m-Y H:i',
            dateFormat: 'Y-m-d H:i'
        });
    }

    // Methods
    if (uploadedImage) {
        const resetImage = uploadedImage.attr('src')

        imageFileInput.change(function () {
            const getFiles = $(this).prop('files')[0]

            if (getFiles) {
                uploadedImage.attr('src', window.URL.createObjectURL(getFiles))
            }
        })

        imageFileReset.click(function () {
            imageFileInput.val('')
            uploadedImage.attr('src', resetImage)
        })
    }
})
