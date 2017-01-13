<?php global $post; ?>
<article class="clearfix">
    @if ( has_excerpt( $post->ID ) )

        <h5 class="page-excertpt">{!! get_the_excerpt() !!}</h5>

    @endif



        {!! the_content() !!}



</article>
