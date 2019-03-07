<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=EDGE">

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ wp_title('|', false, 'right') }}{{ get_bloginfo('name') }}</title>

    <meta name="pubdate" content="{{ the_time('Y-m-d') }}">
    <meta name="moddate" content="{{ the_modified_time('Y-m-d') }}">

    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="format-detection" content="telephone=no">
    <meta name="HandheldFriendly" content="true" />
    <!-- TEST VARNISH -->
    <script>
        var ajaxurl = '{!! apply_filters('Municipio/ajax_url_in_head', admin_url('admin-ajax.php')) !!}';
    </script>

    <!--[if lt IE 9]>
        <script type="text/javascript">
            document.createElement('header');
            document.createElement('nav');
            document.createElement('section');
            document.createElement('article');
            document.createElement('aside');
            document.createElement('footer');
            document.createElement('hgroup');
        </script>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <![endif]-->

    {!! wp_head() !!}
</head>

<body {!! body_class('no-js') !!}>
    <!--[if lt IE 9]>
        <div class="notice info browserupgrade">
            <div class="container"><div class="grid-table grid-va-middle">
                <div class="grid-col-icon">
                    <i class="fa fa-plug"></i>
                </div>
                <div class="grid-sm-12">
                <strong>Du använder en gammal webbläsare.</strong> För att hemsidan ska fungera så bra som möjligt bör du byta till en modernare webbläsare. På <a href="http://browsehappy.com">browsehappy.com</a> kan du få hjälp att hitta en ny modern webbläsare.
                </div>
            </div></div>
        </div>
    <![endif]-->

    <a href="#main-menu" class="btn btn-default btn-block btn-lg btn-offcanvas" tabindex="1"><?php _e('Jump to the main menu', 'municipio'); ?></a>
    <a href="#main-content" class="btn btn-default btn-block btn-lg btn-offcanvas" tabindex="2"><?php _e('Jump to the main content', 'municipio'); ?></a>

    <div id="wrapper">
        @if (isset($notice) && !empty($notice))
            <div class="notices">
            @if (!isset($notice['text']) && is_array($notice))
                @foreach ($notice as $notice)
                    @include('partials.notice')
                @endforeach
            @else
                @include('partials.notice')
            @endif
            </div>
        @endif

        @if (get_field('show_google_translate', 'option') == 'header')
            @include('partials.translate')
        @endif

        @include('partials.header')

            <?php if( !empty(get_field('visualComposerACFHero', get_the_ID())) && get_field('visualComposerACFHero', get_the_ID()) != '<div id="vcHero"></div>'): ?>
                <div class="heroWrapper row search searchNSR" data-searchDesignation='<?php echo get_the_title(); ?>' data-bgimage="<?php echo the_field('HImage', get_the_ID()); ?>" style="background-image: url(<?php echo the_field('HImage', get_the_ID()); ?>);">
                    <div class="hero-search">
                        <?php echo the_field('visualComposerACFHero', get_the_ID()); ?>
                    </div>
                    <div class="searchMenu"></div>
                </div>
            <?php endif; ?>
        <main id="main-content" class="clearfix">
            <?php if( !empty(get_field('visualComposerACFHero', get_the_ID())) && get_field('visualComposerACFHero', get_the_ID()) != '<div id="vcHero"></div>'): ?>
            <div id="nsr-searchResult" class="hide">
                <div class="search-hits hide"></div>
                <div class="sorteringsguiden hide searchView"><div class="sorteringsguiden-data"></div></div>
                <div class="search-autocomplete hide searchView"><div class="search-autocomplete-data"></div></div>
                <div class="search-fetchPlanner hide searchView"><div class="search-fetchPlanner-data"></div></div>
                <div class="search-ao hide searchView">
                    <ul class="ao-nav vc_col-sm-10">
                        <li class="a-o-trigger vc_col-sm-1 active" data-letter="a-c">A-C</li>
                        <li class="a-o-trigger vc_col-sm-1" data-letter="d-f">D-F</li>
                        <li class="a-o-trigger vc_col-sm-1" data-letter="g-i">G-I</li>
                        <li class="a-o-trigger vc_col-sm-1" data-letter="j-l">J-L</li>
                        <li class="a-o-trigger vc_col-sm-1" data-letter="m-o">M-O</li>
                        <li class="a-o-trigger vc_col-sm-1" data-letter="p-r">P-R</li>
                        <li class="a-o-trigger vc_col-sm-1" data-letter="s-u">S-U</li>
                        <li class="a-o-trigger vc_col-sm-1" data-letter="v-w">V-W</li>
                        <li class="a-o-trigger vc_col-sm-1" data-letter="y-ö">Y-Ö</li>
                    </ul>
                    <div class="search-ao-data"></div>
                </div>
                <div class="errorSortguide hide"></div>
                <div class="errorPages hide"></div>
            </div>
            <?php endif; ?>
            @yield('content')
<
            @if (is_active_sidebar('content-area-bottom'))
            <div class="container gutter-xl gutter-vertical sidebar-content-area-bottom">
                <div class="grid">
                    <?php dynamic_sidebar('content-area-bottom'); ?>
                </div>
            </div>
            @endif
        </main>

        @include('partials.footer')

        @if (in_array(get_field('show_google_translate', 'option'), array('footer', 'fold')))
            @include('partials.translate')
        @endif
     </div>

    {!! wp_footer() !!}

</body>
</html>
