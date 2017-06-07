<?php do_action('customer-feedback'); ?>

<footer class="page-footer">
    @if (get_field('show_share', get_the_id()) !== false && get_field('page_show_share', 'option') !== false)
    <div class="grid">
        <div class="grid-xs-12">
            <div class="box box-border gutter gutter-horizontal no-margin hidden-print">
                <div class="gutter gutter-vertical gutter-sm">
                <div class="grid grid-table grid-va-middle no-margin no-padding">
                    <div class="grid-md-8">
                        <i class="material-icons" style="margin-right:5px;">share</i> <strong><?php _e('Share the page', 'municipio'); ?>:</strong> {{ the_title() }} @include('partials.social-share')
                    </div>
                    <div class="grid-md-4 text-right-md text-right-lg">

                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="grid page-author">
        <div class="grid-xs-12">
            @include('partials.timestamps')
        </div>
    </div>
</footer>
