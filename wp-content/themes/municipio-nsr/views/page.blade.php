@extends('templates.master')

@section('content')

    <div class="container main-container">

        @include('partials.breadcrumbs')

        <div class="grid {{ implode(' ', apply_filters('Municipio/Page/MainGrid/Classes', wp_get_post_parent_id(get_the_id()) != 0 ? array('no-margin-top') : array())) }}">
            @include('partials.sidebar-left')

            <!-- NSR -->
                <div class="page-title grid-md-12 grid-lg-12 grid-sm-12 grid-xs-12">
                    <h1>{{ the_title() }}</h1>
                    @include('partials.accessibility-menu')
                </div>
                <!-- /NSR -->

                <div class="{{ $contentGridSize }} grid-print-12" id="readspeaker-read">

                    @if (is_active_sidebar('content-area-top'))
                        <div class="grid sidebar-content-area sidebar-content-area-top">
                            <?php dynamic_sidebar('content-area-top'); ?>
                        </div>
                    @endif

                @while(have_posts())
                    {!! the_post() !!}

                    @include('partials.article')
                @endwhile

                @if (is_active_sidebar('content-area'))
                    <div class="grid sidebar-content-area sidebar-content-area-bottom">
                        <?php dynamic_sidebar('content-area'); ?>
                    </div>
                @endif

            </div>

        </div>

        <div class="grid hidden-lg hidden-xl">
            <div class="grid-sm-12">
                @include('partials.page-footer')
            </div>
        </div>
    </div>

@stop