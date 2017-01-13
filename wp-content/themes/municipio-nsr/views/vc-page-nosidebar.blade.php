@extends('templates.master')

@section('content')

<div class="container main-container">

    @include('partials.breadcrumbs')

    <div class="grid {{ implode(' ', apply_filters('Municipio/Page/MainGrid/Classes', wp_get_post_parent_id(get_the_id()) != 0 ? array('no-margin-top') : array())) }}">
        @include('partials.sidebar-left')

        <div class="grid-md-12 grid-lg-12 grid-print-12" id="readspeaker-read">

            @while(have_posts())
            {!! the_post() !!}

            @include('partials.article')
            @endwhile

        </div>
        
    </div>

    <div class="grid hidden-lg hidden-xl">
        <div class="grid-sm-12">
            @include('partials.page-footer')
        </div>
    </div>
</div>

@stop