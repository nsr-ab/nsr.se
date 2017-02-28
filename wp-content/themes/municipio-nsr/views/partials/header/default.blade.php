<!-- Mobile -->
<div class="mob grid-xs-12 grid-sm-12">
    <div class="mobile-logo center grid-xs-12 grid-sm-12 grid-md-12 text-center-sm text-center-xs hide">
        {!! municipio_get_logotype(get_field('header_logotype', 'option'), get_field('logotype_tooltip', 'option'), true, get_field('header_tagline_enable', 'option')) !!}
    </div>

    <div class="mobile-search">
        <a href="#translate" class="translate-icon-btn" aria-label="translate"><span data-tooltip="Translate"><i class="pricon pricon-globe"></i></span></a>
        <a class="waves-effect waves-light" href="#searchModal"><i class="left material-icons search quickSearch">search</i></a>
        @if (function_exists('ReadSpeakerHelper_playButton'))
            <?php

                ReadSpeakerHelper_playButton($classes = array(), 'icon');
            ?>
        @endif

    </div>

    <div class="mobile-nav hidden-lg hidden-md ">
        <a href="javascript:void(0)" data-activates="mobileNav" class="button-collapse "><i class="material-icons">menu</i></a>
    </div>

    <div class="side-nav " id="mobileNav">
        <div class="topnav">
            <i class="material-icons left search quickSearch">search</i>
            <i class="material-icons right close">close</i>
        </div>

        <h6><?php _e('What customer are you today?', 'nsr') ?></h6>
        <?php wp_nav_menu(
            array('theme_location' => 'header-tabs-menu',
                'container' => 'nav',
                'container_class' => 'nav-wrapper center-align ',
                'container_id' => '',
                'menu_class' => 'nav ',
                'menu_id' => '',
                'echo' => 'echo',
                'before' => '',
                'after' => '',
                'link_before' => '',
                'link_after' => '',
                'items_wrap' => '<ul class="%2$s center">%3$s</ul>',
                'depth' => 2,
                'fallback_cb' => '__return_false'
            )
        ); ?>
    </div>
</div>

<!-- Modal Structure -->
<div id="searchModal" class="modal bottom-sheet">
    <i class="material-icons modal-action modal-close waves-effect waves-green right">cancel</i>
    <div class="modal-content">
        <form itemprop="potentialAction" class="row" itemscope="" action=/sok/ itemtype="http://schema.org/SearchAction">
            <div class="searchForm input-field col s10">
                <i class="material-icons prefix">search</i>
                <input class="form-control form-control-lg validated input-field s12" itemprop="query-input" required="" id="search-nsr" autocomplete="off" type="search" name="q" value="" aria-invalid="true">
                <label for="search-input">Vad letar du efter?</label>
                <input type="hidden" id="post_type" value="">
            </div>

            <div class="searchBtnSubmit col s2 hidden-xs"><button class="searchSubmit btn btn-large waves-effect waves-light left" type="submit">SÖK</button></div>

        </form>
    </div>
</div>


<!-- Main menu -->
<div class="container hidden-print desk">
    <div class="grid">

        <div class="grid-md-5 text-center-xs text-center-sm" id="nsr-logo">
            <div class="desk-logo grid-xs-12 grid-sm-12 grid-md-12 text-center-sm text-center-xs">
                {!! municipio_get_logotype(get_field('header_logotype', 'option'), get_field('logotype_tooltip', 'option'), true, get_field('header_tagline_enable', 'option')) !!}
            </div>
        </div>

        <div class="grid-md-5 text-center-sm text-center-xs text-right hidden-sm hidden-xs sites-nav">
            <div class="text-left">
                <h6><?php _e('What customer are you today?', 'nsr') ?></h6>
                {!!
                    wp_nav_menu(
                        array(
                            'theme_location' => 'header-tabs-menu',
                            'container' => 'nav',
                            'container_class' => 'nav-wrapper center-align ',
                            'container_id' => '',
                            'menu_class' => 'nav ',
                            'menu_id' => 'help-menu-top',
                            'echo' => 'echo',
                            'before' => '',
                            'after' => '',
                            'link_before' => '',
                            'link_after' => '',
                            'items_wrap' => '<ul class="%2$s center">%3$s</ul>',
                            'depth' => 2,
                            'fallback_cb' => '__return_false'
                        )
                    );
                !!}

                @if ( (is_array(get_field('search_display', 'option')) && in_array('header', get_field('search_display', 'option'))) || (!is_front_page() && is_array(get_field('search_display', 'option')) && in_array('header_sub', get_field('search_display', 'option'))) )
                    @include('partials.search.top-search')
                @endif
            </div>

            {!!
                wp_nav_menu(array(
                    'theme_location' => 'help-menu',
                    'container' => 'nav',
                    'container_class' => 'menu-help',
                    'container_id' => '',
                    'menu_class' => 'nav nav-help nav-horizontal',
                    'menu_id' => 'help-menu-top',
                    'echo' => 'echo',
                    'before' => '',
                    'after' => '',
                    'link_before' => '',
                    'link_after' => '',
                    'items_wrap' => '<ul class="%2$s">%3$s</ul>',
                    'depth' => 1,
                    'fallback_cb' => '__return_false'
                ));
            !!}
        </div>
    </div>
</div>

@if (get_field('nav_primary_enable', 'option') === true)

    <nav class="navbar navbar-mainmenu hidden-xs hidden-sm hidden-print">
        <div class="container">
            <div class="grid">
                <div class="grid-sm-12">
                    {!! $navigation['mainMenu'] !!}
                </div>
            </div>
        </div>
    </nav>

    @if (strlen($navigation['mobileMenu']) > 0)
        <nav id="mobile-menu" class="nav-mobile-menu nav-toggle nav-toggle-expand {!! apply_filters('Municipio/mobile_menu_breakpoint','hidden-md hidden-lg'); !!} hidden-print">
            @include('partials.mobile-menu')
        </nav>
    @endif
@endif
