<header id="site-header" class="site-header">


    @include('partials.header.' . $headerLayout['template'])

    <div class="print-only container">
        <div class="grid">
            <div class="grid-sm-12">
                {!! municipio_get_logotype('standard') !!}
            </div>
        </div>
    </div>

</header>

@include('partials.hero')
