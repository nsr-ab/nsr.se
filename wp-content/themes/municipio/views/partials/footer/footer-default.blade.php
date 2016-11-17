<footer class="main-footer hidden-print">
    <div class="container">

        @if (get_field('footer_logotype_vertical_position', 'option') == 'bottom')
        <div class="grid">
            <div class="grid-sm-12">
                <nav>
                    <ul class="nav nav-help nav-horizontal">
                        {!!
                            wp_nav_menu(array(
                                'theme_location' => 'help-menu',
                                'container' => false,
                                'container_class' => 'menu-{menu-slug}-container',
                                'container_id' => '',
                                'menu_class' => '',
                                'menu_id' => 'help-menu-top',
                                'echo' => false,
                                'before' => '',
                                'after' => '',
                                'link_before' => '',
                                'link_after' => '',
                                'items_wrap' => '%3$s',
                                'depth' => 1,
                                'fallback_cb' => '__return_false'
                            ));
                        !!}
                    </ul>
                </nav>
            </div>
        </div>
        @endif

        <div class="grid">
            <div class="{{ get_field('footer_signature_show', 'option') ? 'grid-md-10' : 'grid-md-12' }}">

                {{-- ## Footer header befin ## --}}
                @if (get_field('footer_logotype_vertical_position', 'option') == 'top' || !get_field('footer_logotype_vertical_position', 'option'))
                <div class="grid">
                    <div class="grid-md-12">
                        @if (get_field('footer_logotype', 'option') != 'hide')
                        {!! municipio_get_logotype(get_field('footer_logotype', 'option')) !!}
                        @endif

                        <nav class="{{ !get_field('footer_signature_show', 'option') ? 'pull-right' : '' }}">
                            <ul class="nav nav-help nav-horizontal">
                                {!!
                                    wp_nav_menu(array(
                                        'theme_location' => 'help-menu',
                                        'container' => false,
                                        'container_class' => 'menu-{menu-slug}-container',
                                        'container_id' => '',
                                        'menu_class' => '',
                                        'menu_id' => 'help-menu-top',
                                        'echo' => false,
                                        'before' => '',
                                        'after' => '',
                                        'link_before' => '',
                                        'link_after' => '',
                                        'items_wrap' => '%3$s',
                                        'depth' => 1,
                                        'fallback_cb' => '__return_false'
                                    ));
                                !!}
                            </ul>
                        </nav>
                    </div>
                </div>
                @endif
                {{-- ## Footer header end ## --}}

                {{-- ## Footer widget area begin ## --}}
                <div class="grid sidebar-footer-area">
                    @if (is_active_sidebar('footer-area'))
                        <?php dynamic_sidebar('footer-area'); ?>
                    @endif
                </div>
                {{-- ## Footer widget area end ## --}}

                {{-- ## Footer header begin ## --}}
                @if (get_field('footer_logotype_vertical_position', 'option') == 'bottom' && get_field('footer_logotype', 'option') != 'hide')
                <div class="grid no-margin-top">
                    <div class="grid-md-6">
                        {!! municipio_get_logotype(get_field('footer_logotype', 'option')) !!}
                    </div>
                    <div class="grid-md-6">
                        @if(have_rows('footer_icons_repeater', 'option'))
                            <ul class="icons-list">
                                @foreach(get_field('footer_icons_repeater', 'option') as $link)
                                    <li>
                                        <a href="{{ $link['link_url'] }}" target="_blank" class="link-item-light">
                                            {!! $link['link_icon'] !!}

                                            @if (isset($link['link_title']))
                                            <span class="sr-only">{{ $link['link_title'] }}</span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
                @endif
                {{-- ## Footer header end ## --}}

                @if (get_field('footer_logotype_vertical_position', 'option') == 'top' && have_rows('footer_icons_repeater', 'option'))
                    <div class="grid no-margin-top">
                        <div class="grid-md-12">
                            <ul class="icons-list">
                                @foreach(get_field('footer_icons_repeater', 'option') as $link)
                                    <li>
                                        <a href="{{ $link['link_url'] }}" target="_blank" class="link-item-light">
                                            {!! $link['link_icon'] !!}

                                            @if (isset($link['link_title']))
                                            <span class="sr-only">{{ $link['link_title'] }}</span>
                                            @endif
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ## Footer signature ## --}}
            @if (get_field('footer_signature_show', 'option'))
                <div class="grid-md-2 text-right">
                    {!! apply_filters('Municipio/footer_signature', '<a href="http://www.helsingborg.se"><img src="' . get_template_directory_uri() . '/assets/dist/images/helsingborg.svg" alt="Helsingborg Stad" class="footer-signature"></a>') !!}
                </div>
            @endif
        </div>
    </div>
</footer>
