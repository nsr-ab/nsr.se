<!-- Mobile -->
<!-- Quick hack for ie11 -->

<div class="mob grid-xs-12 grid-sm-12">
    <div class="mobile-logo grid-xs-12 grid-sm-12 grid-md-12 hide">
        {!! municipio_get_logotype(get_field('header_logotype', 'option'), get_field('logotype_tooltip', 'option'), true, get_field('header_tagline_enable', 'option')) !!}
    </div>


    <div class="sites-nav ">


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
                    'before' => '<span>',
                    'after' => '</span>',
                    'link_before' => '',
                    'link_after' => '',
                    'items_wrap' => '<ul class="%2$s center">%3$s</ul>',
                    'depth' => 2,
                    'fallback_cb' => '__return_false'
                )
            );
        !!}

    </div>


</div>

<div class="all-device">
<ul class="acc-helper-menu">
    <li class="sort-mob sortguide-topmenu">
        <a aria-label="sorteringsguiden" href="/sorteringsguiden/">SORTERING</a>
    </li>
    <li class="sort-desk sortguide-topmenu">
        <a aria-label="sorteringsguiden" href="/sorteringsguiden/">Sorteringsguiden</a>
    </li>
    <li>
        <a href="#translate" aria-label="translate"><i
                    class="material-icons translate-icon-btn">language</i></a>
    </li>
    <li>
        <a class="waves-effect waves-light" href="/sok/"><i translate="no" class="notranslate left material-icons search quickSearch"></i></a>
    </li>
    <!-- <li>
        <a class="waves-effect waves-light" href="#searchModal">
            <i translate="no"
               class="notranslate left material-icons search quickSearch">&#xE8B6;</i></a>
    </li> -->
</ul>
</div>

<!-- Modal Structure -->
<div id="searchModal" class="modal bottom-sheet">
    <i class="material-icons modal-action modal-close waves-effect waves-green right notranslate">cancel</i>
    <div class="modal-content">
        <form itemprop="potentialAction" class="row" itemscope=""
              action=/sok/ itemtype="http://schema.org/SearchAction">
            <div class="searchForm input-field col s10">
                <i class="material-icons prefix notranslate">search</i>
                <input class="form-control form-control-lg validated input-field s12" itemprop="query-input" required=""
                       id="search-nsr" autocomplete="off" type="search" name="q" value="" aria-invalid="true">
                <label for="search-input">Vad letar du efter?</label>
                <input type="hidden" id="post_type" value="">
            </div>

            <div class="searchBtnSubmit col s2 hidden-xs">
                <button class="searchSubmit btn btn-large waves-effect waves-light left notranslate" type="submit">SÖK
                </button>
            </div>

        </form>
    </div>
</div>


<!-- Main menu -->
<div class="container hidden-print desk">
    <div class="grid">


        <div class="grid-auto text-center-xs text-center-sm desk-position">
            <div class="grid grid-table grid-va-middle no-padding">
                <div class="grid-xs-8 grid-sm-8 grid-md-3 text-left-sm text-left-xs ie-fix">

                    <div id="nsr-logo">
                        <div class="desk-logo">
                            {!! municipio_get_logotype(get_field('header_logotype', 'option'), get_field('logotype_tooltip', 'option'), true, get_field('header_tagline_enable', 'option')) !!}
                        </div>
                    </div>

                </div>


                <div class="hidden-sm hidden-xs sites-nav grid-sm-6 grid-md-6 ">


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
                <nav id="mobile-menu"
                     class="nav-mobile-menu nav-toggle nav-toggle-expand {!! apply_filters('Municipio/mobile_menu_breakpoint','hidden-md hidden-lg'); !!} hidden-print">
                    @include('partials.mobile-menu')
                </nav>

            @endif
        @endif
