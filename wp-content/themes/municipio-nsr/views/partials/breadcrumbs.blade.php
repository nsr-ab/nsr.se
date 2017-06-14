@if (!is_home())
    <div class="grid breadcrumbs-wrapper">
        <div class="grid-lg-12">
             {{ \Nsr\Theme\NSRTemplates::outputBreadcrumbs() }}
        </div>
    </div>
@endif