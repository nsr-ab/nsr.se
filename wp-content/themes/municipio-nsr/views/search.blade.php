@extends('templates.master')

@section('content')

    <?php global $post; ?>
    <article class="clearfix" id="nsr-article">


        @if (isset(get_extended($post->post_content)['main']) && strlen(get_extended($post->post_content)['main']) > 0 && isset(get_extended($post->post_content)['extended']) && strlen(get_extended($post->post_content)['extended']) > 0)

            {!! apply_filters('the_lead', get_extended($post->post_content)['main']) !!}
            {!! apply_filters('the_content', get_extended($post->post_content)['extended']) !!}

        @else

            {!! the_content() !!}

        @endif

    </article>

@stop