@extends('templates.master')
<?php

    $the_page    = null;
    $errorpageid = get_option( '404pageid', 0 );
    if ($errorpageid !== 0) {
        $errorpageid = (int) $errorpageid;
        $the_page = get_page($errorpageid);
    }
?>


@section('content')

    <?php $cache = new Municipio\Helper\Cache('404','',600); if ($cache->start()) { ?>


    <div class="container main-container">

        <div class="grid {{ implode(' ', apply_filters('Municipio/Page/MainGrid/Classes', wp_get_post_parent_id(get_the_id()) != 0 ? array('no-margin-top') : array())) }}">

            <?php echo apply_filters( 'the_content', $the_page->post_content ); ?>

        </div>

    </div>

    <?php } ?>

@stop
