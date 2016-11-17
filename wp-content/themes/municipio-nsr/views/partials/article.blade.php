<?php global $post; ?>
<article class="clearfix">
    @if ( has_excerpt( $post->ID ) )

        <h5 class="page-excertpt">{!! get_the_excerpt() !!}</h5>

    @endif

    @if (isset(get_extended($post->post_content)['main']) && strlen(get_extended($post->post_content)['main']) > 0 && isset(get_extended($post->post_content)['extended']) && strlen(get_extended($post->post_content)['extended']) > 0)

        {!! apply_filters('the_lead', get_extended($post->post_content)['main']) !!}
        {!! apply_filters('the_content', get_extended($post->post_content)['extended']) !!}

    @else

        {!! the_content() !!}

    @endif

</article>
