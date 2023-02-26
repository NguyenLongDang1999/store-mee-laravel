$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            async: true,
            cache: false,
        }
    })

    // Variables
    let uploadedImage = $('#uploaded-image'),
        toastPlacement,
        dataID, action

    const bootstrapSelect = $('.selectpicker'),
        flatpickr = $('.flatpickr'),
        formRepeater = $('.form-repeater'),
        imageFileInput = $('.image-file-input'),
        imageFileReset = $('.image-file-reset'),
        toastPlacementExample = $('.toast-placement-ex'),
        actionDialog = $('#action-dialog'),
        btnAction = $('#btn-action')

    // Plugins
    if (bootstrapSelect.length) {
        bootstrapSelect.selectpicker()
    }

    if (flatpickr.length) {
        flatpickr.flatpickr({
            enableTime: true,
            altInput: true,
            altFormat: 'd-m-Y H:i',
            dateFormat: 'Y-m-d H:i'
        });
    }

    if (formRepeater.length) {
        const variationItem = $('.variation-item')
        let row = variationItem.length + 1;
        formRepeater.on('submit', function (e) {
            e.preventDefault();
        });
        formRepeater.repeater({
            show: function () {
                const formControl = $(this).find('.form-control');
                const formLabel = $(this).find('.form-label');

                formControl.each(function (i) {
                    const id = 'value-' + row;
                    $(formControl[i]).attr('id', id).removeAttr('disabled');
                    $(formLabel[i]).attr('for', id);
                });

                row++;

                $(this).slideDown();
            },
            hide: function (e) {
                $(this).slideUp();
            }
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

    actionDialog.on('show.bs.modal', function (event) {
        dataID = $(event.relatedTarget).data('id')
        action = $(event.relatedTarget).data('action')
    })

    $(btnAction).on('click', function (e) {
        e.preventDefault()
        actionDataWithDialog(dataID, action)
    })

    // Functions
    function actionDataWithDialog(dataID, action) {
        $.ajax({
            type: "post",
            url: action,
            data: {
                data: dataID
            }
        }).done(function (resp) {
            const toastResult = $('.toast-body'),
                toastType = $('.toast-type'),
                toastTitle = $('.toast-title')

            if (resp.result) {
                toastType.addClass('bg-primary')
                toastType.removeClass('bg-danger')
            } else {
                toastType.addClass('bg-danger')
                toastType.removeClass('bg-primary')
            }

            result.draw()
            actionDialog.modal('hide')
            toastTitle.text(resp.title)
            toastResult.text(resp.message)
            toastPlacement = new bootstrap.Toast(toastPlacementExample);
            toastPlacement.show();
        })
    }
})
