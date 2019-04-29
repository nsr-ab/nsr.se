@extends('templates.master')

@section('content')

<section class="creamy creamy-border-bottom gutter-lg gutter-vertical sidebar-content-area archive-filters">
    <div class="container">
        <?php echo do_shortcode('[wp-listings-search-form]'); ?>
    </div>
</section>

<div class="container main-container">
    @include('partials.breadcrumbs')

    <div class="grid">
        @if (get_field('archive_' . sanitize_title($postType) . '_show_sidebar_navigation', 'option'))
            @include('partials.sidebar-left')
        @endif

        <?php
            $cols = 'grid-md-12';
            if (is_active_sidebar('right-sidebar') && get_field('archive_' . sanitize_title($postType) . '_show_sidebar_navigation', 'option')) {
                $cols = 'grid-md-8 grid-lg-6';
            } elseif (is_active_sidebar('right-sidebar') || get_field('archive_' . sanitize_title($postType) . '_show_sidebar_navigation', 'option')) {
                $cols = 'grid-md-12 grid-lg-9';
            }
        ?>

        <div class="{{ $cols }}">

            @if (is_active_sidebar('content-area-top'))
                <div class="grid sidebar-content-area sidebar-content-area-top">
                    <?php dynamic_sidebar('content-area-top'); ?>
                </div>
            @endif

            {{ do_action('wp-listings/archive/before') }}

            <div class="grid">
                <div class="grid-xs-12">
                @if (have_posts())

                    <ul>
                    @while(have_posts())
                        {!! the_post() !!}
                        <?php global $post; $thumbnail = municipio_get_thumbnail_source($post->ID, array(500, 500)); ?>

                        <li class="box box-news box-news-horizontal">
                            <a href="{{ the_permalink() }}" class="box-image-container" style="width: 200px;">
                                @if ($thumbnail)
                                    <img src="{{ municipio_get_thumbnail_source(null,array(500,500)) }}" alt="{{ the_title() }}">
                                @else
                                    <figure class="image-placeholder"></figure>
                                @endif
                            </a>
                            <div class="box-content">

                                <?php
                                    $terms = array();
                                    foreach ((array) get_the_terms(get_the_id(), \WpListings\Listings::$taxonomySlug) as $term) {
                                        //if (is_object($term)) {
                                            $terms[] = $term->name;
                                        //}
                                    }
                                    $terms      = implode(', ', (array) $terms);
                                    $location   = isset(get_the_terms(get_the_id(), \WpListings\Listings::$placesTaxonomySlug)[0]) ? get_the_terms(get_the_id(), \WpListings\Listings::$placesTaxonomySlug)[0]->name : "";
                                ?>

                                @if ($terms != "" || $location != "")

                                    <div>
                                        {{ $terms }}
                                        @if ($terms != "" && $location != "")
                                        ,
                                        @endif
                                        {{ $location }}
                                    </div>

                                @endif

                                <h2 class="box-title text-highlight"><a href="{{ the_permalink() }}">{{ the_title() }}</a></h2>

                                @if (wp_listings_use_price())
                                <p class="text-xl" style="margin-top: 0;">{{ municiipio_format_currency(get_post_meta(get_the_id(), 'listing_price', true)) }}{{ apply_filters('wp-listings/currency', ':-') }}</p>
                                @endif
                            </div>
                        </li>
                    @endwhile
                    </ul>
                @else
                    <div class="notice info pricon pricon-info-o pricon-space-right"><?php _e('No posts to show'); ?>…</div>
                @endif
                </div>
            </div>

            @if (is_active_sidebar('content-area'))
                <div class="grid sidebar-content-area sidebar-content-area-bottom">
                    <?php dynamic_sidebar('content-area'); ?>
                </div>
            @endif

            <div class="grid">
                <div class="grid-sm-12 text-center">
                    {!!
                        paginate_links(array(
                            'type' => 'list'
                        ))
                    !!}
                </div>
            </div>

        </div>

        @include('partials.sidebar-right')
    </div>
</div>

@stop
