<div class="js-cookie-consent cookie-consent alert alert-dismissible fade show" role="alert">
    <div class="main-cookie-content">
        <p class="cookie-consent__message m-0">
            {!! trans('cookie-consent::texts.message') !!}
        </p>
        <button class="allow-button primary-btn js-cookie-consent-agree cookie-consent__agree">
            {{ trans('cookie-consent::texts.agree') }}
        </button>
    </div>
    <button type="button" class="btn-close front-close-btn" data-bs-dismiss="alert" aria-label="Close">
    </button>
</div>
