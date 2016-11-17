<ul class="article-timestamps">
    @if (get_the_modified_time() != get_the_time())

        @if (is_array(get_field('show_date_updated','option')) || is_array(get_field('show_date_published','option')) || in_array(get_post_type(get_the_id()),
        get_field('show_date_updated','option')) || in_array(get_post_type(get_the_id()), get_field('show_date_published','option')))

            @if(is_array(get_field('show_date_published','option')) && in_array(get_post_type(get_the_id()),get_field('show_date_published','option')))
                <li>
                    <strong><?php _e("Published", 'municipio'); ?>:</strong>
                    <time datetime="<?php echo the_time('Y-m-d'); ?>">
                        <?php the_time('j F Y'); ?> <?php //the_time('H:i'); ?>
                    </time>
                </li>
            @endif

            @if(is_array(get_field('show_date_updated','option')) && in_array(get_post_type(get_the_id()), get_field('show_date_updated','option')))
                <li class="pull-left grid-md-6 grid-sm-6">
                    <?php _e("Last updated", 'nsr'); ?>:
                    <time datetime="<?php echo the_modified_time('Y-m-d H:i'); ?>">
                        <?php the_modified_time('j M Y'); ?> <?php //the_modified_time('H:i'); ?>
                    </time>
                </li>
            @endif

        @endif

    @else

        @if (is_array(get_field('show_date_published','option')) && in_array(get_post_type(get_the_id()), get_field('show_date_published','option')))
            <li>
                <strong><?php _e("Published", 'municipio'); ?>:</strong>
                <time datetime="<?php echo the_time('Y-m-d H:i'); ?>">
                    <?php the_time('j F Y'); ?> {!! __("at", 'municipio'); !!} <?php the_time('H:i'); ?>
                </time>
            </li>
        @endif

    @endif



    @if (get_field('page_show_author', 'option') !== false && get_field('post_show_author', get_the_id()) !== false && get_the_author())
        <li class="pull-right grid-md-6 grid-sm-6 text-right ">
            <?php echo apply_filters('Municipio/author_display/title', __('page manager', 'nsr')); ?>:
            <span class="post-author post-author-margin-left">
                @if (get_field('page_show_author_image', 'option') !== false && get_field('post_show_author_image', get_the_id()) !== false && !empty(get_the_author_meta('user_profile_picture')))
                    <span class="post-author-image" style="background-image:url('{{ get_the_author_meta('user_profile_picture') }}');"><img src="{{ get_the_author_meta('user_profile_picture') }}" alt="{{ (!empty(get_the_author_meta('first_name')) && !empty(get_the_author_meta('last_name'))) ? get_the_author_meta('first_name') . ' ' . get_the_author_meta('last_name')  : get_the_author() }}"></span>
                @endif

                <a href="mailto:{!! apply_filters('Municipio/author_display/name', get_the_author_meta('email'), get_the_author_meta('ID')) !!}">

                @if (!empty(get_the_author_meta('first_name')) && !empty(get_the_author_meta('last_name')))
                    <span class="post-author-name">{!! apply_filters('Municipio/author_display/name', get_the_author_meta('first_name') . ' ' . get_the_author_meta('last_name'), get_the_author_meta('ID')) !!}</span>
                @else
                    <span class="post-author-name">{!! apply_filters('Municipio/author_display/name', get_the_author(), get_the_author_meta('ID')) !!}</span>
                @endif
                </a>
            </span>
        </li>
    @endif
</ul>
