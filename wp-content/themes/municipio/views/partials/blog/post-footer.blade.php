<footer class="post-footer">
    @if (get_field('post_show_share', get_the_id()) !== false && get_field('page_show_share', 'option') !== false && is_single())
        <div class="grid">
            <div class="grid-xs-12">
                <div class="box box-border gutter gutter-horizontal no-margin">
                    <div class="gutter gutter-vertical gutter-sm">
                    <div class="grid grid-table grid-va-middle no-margin no-padding">
                        <div class="grid-md-8">
                            <i class="pricon pricon-share pricon-lg" style="margin-right:5px;"></i> <strong><?php _e('Share the page', 'municipio'); ?>:</strong> {{ the_title() }}
                        </div>
                        <div class="grid-md-4 text-right-md text-right-lg">
                            @include('partials.social-share')
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-table grid-table-autofit {{ is_single() ? 'no-padding' : '' }}">
        <div class="grid-md-4">
            @if (in_array('tags', (array)get_field('archive_' . sanitize_title(get_post_type()) . '_post_display_info', 'option')))
                @include('partials.blog.post-tags')
            @endif

            @if (in_array('category', (array)get_field('archive_' . sanitize_title(get_post_type()) . '_post_display_info', 'option')))
                @include('partials.blog.post-categories')
            @endif
        </div>

        @if (in_array('author', (array)get_field('archive_' . sanitize_title(get_post_type()) . '_post_display_info', 'option')) && get_field('post_show_author', get_the_id()) !== false)
            <div class="grid-md-4">
                <strong><?php echo apply_filters('Municipio/author_display/title', __('Published by', 'municipio')); ?>:</strong>
                <span class="post-author post-author-margin-left">
                    @if (in_array('author_image', (array)get_field('archive_' . sanitize_title(get_post_type()) . '_post_display_info', 'option')) && get_field('post_show_author_image', get_the_id()) !== false && !empty(get_field('user_profile_picture', 'user_' . get_the_author_meta('ID'))))
                        <span class="post-author-image" style="background-image:url('{{ get_field('user_profile_picture', 'user_' . get_the_author_meta('ID')) }}');"><img src="{{ get_field('user_profile_picture', 'user_' . get_the_author_meta('ID')) }}" alt="{{ (!empty(get_the_author_meta('first_name')) && !empty(get_the_author_meta('last_name'))) ? get_the_author_meta('first_name') . ' ' . get_the_author_meta('last_name')  : get_the_author() }}"></span>
                    @endif

                    @if (!empty(get_the_author_meta('first_name')) && !empty(get_the_author_meta('last_name')))
                        <span class="post-author-name">{!! apply_filters('Municipio/author_display/name', get_the_author_meta('first_name') . ' ' . get_the_author_meta('last_name'), get_the_author_meta('ID')) !!}</span>
                    @else
                        <span class="post-author-name">{!! apply_filters('Municipio/author_display/name', get_the_author(), get_the_author_meta('ID')) !!}</span>
                    @endif
                </span>
            </div>
        @endif

        @if (get_field('post_show_share', get_the_id()) !== false && get_field('page_show_share', 'option') !== false && !is_single())
        <div class="grid-md-4 text-right">
            @include('partials.social-share')
        </div>
        @endif
    </div>
</footer>
