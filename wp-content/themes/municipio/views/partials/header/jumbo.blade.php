<div class="search-top {!! apply_filters('Municipio/desktop_menu_breakpoint','hidden-sm'); !!} hidden-print" id="search">
    <div class="container">
        <div class="grid">
            <div class="grid-sm-12">
                {{ get_search_form() }}
            </div>
        </div>
    </div>
</div>

<nav class="navbar navbar-mainmenu hidden-print">
    <div class="container">
        <div class="grid">
            <div class="grid-xs-12 {!! apply_filters('Municipio/header_grid_size','grid-md-12'); !!}">
                <div class="grid">
                    <div class="grid-sm-12 grid-md-4">
                        {!! municipio_get_logotype(get_field('header_logotype', 'option'), get_field('logotype_tooltip', 'option')) !!}
                        <a href="#mobile-menu" data-target="#mobile-menu" class="{!! apply_filters('Municipio/mobile_menu_breakpoint','hidden-md hidden-lg'); !!} menu-trigger"><span class="menu-icon"></span></a>
                    </div>
                    <div class="grid-md-8 text-right {!! apply_filters('Municipio/desktop_menu_breakpoint','hidden-xs hidden-sm'); !!}">
                        <nav class="nav-group-overflow" data-btn-width="100">
                            {!! $navigation['mainMenu'] !!}
                            <span class="dropdown">
                                <span class="btn btn-primary dropdown-toggle hidden"><?php _e('More', 'municipio'); ?></span>
                                <ul class="dropdown-menu nav-grouped-overflow hidden"></ul>
                            </span>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>

@if (strlen($navigation['mobileMenu']) > 0)
    <nav id="mobile-menu" class="nav-mobile-menu nav-toggle-expand nav-toggle {!! apply_filters('Municipio/mobile_menu_breakpoint','hidden-md hidden-lg'); !!} hidden-print">
        @include('partials.mobile-menu')
    </nav>
@endif
