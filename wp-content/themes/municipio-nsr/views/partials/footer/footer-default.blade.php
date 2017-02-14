<footer class="main-footer hidden-print">
    <div class="container">

        <div class="grid">
            <div class="{{ get_field('footer_signature_show', 'option') ? 'grid-md-10' : 'grid-md-12' }}">



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