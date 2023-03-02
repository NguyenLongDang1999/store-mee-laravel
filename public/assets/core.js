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
        numeralMask = document.getElementsByClassName('numeral-mask'),
        imageFileInput = $('.image-file-input'),
        imageFileReset = $('.image-file-reset'),
        toastPlacementExample = $('.toast-placement-ex'),
        actionDialog = $('#action-dialog'),
        btnAction = $('#btn-action'),
        categoryVal = $('#product-form #category_id')

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

    if (numeralMask.length) {
        const numberFormat = Array.from(numeralMask)

        numberFormat.forEach(function (field) {
            new Cleave(field, {
                numeral: true,
                numeralThousandsGroupStyle: 'thousand'
            });
        })
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

    categoryVal.on('change', function (e) {
        e.preventDefault()
        changeData($(this).val(), '.brand-data')
    })

    if (categoryVal.length && categoryVal[0]?.dataset?.brand) {
        changeData(categoryVal.val(), '.brand-data', categoryVal[0]?.dataset?.brand)
    }

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

    function changeData(dataID, className, val = '') {
        $.ajax({
            type: "post",
            url: url_change_data,
            data: {
                data: dataID
            }
        }).done(function (resp) {
            $(className).replaceWith(resp)

            if (val) {
                $(className).selectpicker('val', [val])
            } else {
                $(className).selectpicker()
            }
        })
    }
})
