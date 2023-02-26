<div
    class="bs-toast toast toast-placement-ex top-0 end-0 m-2"
    role="alert"
    aria-live="assertive"
    aria-atomic="true"
    data-bs-delay="2000"
>
    <div class="toast-header text-white toast-type">
        <div class="me-auto fw-semibold text-capitalize toast-title"></div>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>

    <div class="toast-body text-capitalize"></div>
</div>

<div class="modal fade" id="action-dialog" tabindex="-1" style="display: none" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            {{ html()->form('POST', '')->id('form-action')->open() }}

            <div class="modal-header">
                <h5 class="modal-title text-capitalize">{{ __('trans.confirm.title.delete') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body text-capitalize">{{ __('trans.confirm.update') }}</div>

            <div class="modal-footer">
                {{ html()->submit(__('trans.btn.confirm'))->class('btn btn-sm btn-primary text-capitalize')->id('btn-action') }}
                {{ html()->button(__('trans.btn.cancel'))->type('button')->class('btn btn-sm btn-label-secondary text-capitalize')->attribute('data-bs-dismiss', 'modal') }}
            </div>
            {{ html()->form()->close() }}
        </div>
    </div>
</div>
