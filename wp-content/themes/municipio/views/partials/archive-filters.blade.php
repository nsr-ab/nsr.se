@if (!empty($enabledHeaderFilters))
<section class="creamy creamy-border-bottom gutter-lg gutter-vertical sidebar-content-area archive-filters">
    <form method="get" action="{{ get_post_type_archive_link(get_post_type()) }}" class="container" id="archive-filter">
        <div class="grid">
            @if (in_array('text_search', $enabledHeaderFilters))
            <div class="grid-sm-12 grid-md-auto">
                <label for="filter-keyword" class="text-sm sr-only"><strong><?php _e('Search', 'municipio'); ?>:</strong></label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-search"></i></span>
                    <input type="text" name="s" id="filter-keyword" class="form-control" value="{{ !empty(get_search_query()) ? get_search_query() : '' }}" placeholder="<?php _e('Search', 'municipio'); ?>">
                </div>
            </div>
            @endif

            @if (in_array('date_range', $enabledHeaderFilters))
            <div class="grid-sm-12 grid-md-auto">
                <label for="filter-date-from" class="text-sm sr-only"><strong><?php _e('Date published', 'municipio'); ?>:</strong></label>
                <div class="input-group">
                    <span class="input-group-addon"><?php _e('From', 'municipio'); ?>:</span>
                    <input type="text" name="from" placeholder="<?php _e('From date', 'municipio'); ?>…" id="filter-date-from" class="form-control datepicker-range datepicker-range-from" value="{{ isset($_GET['from']) && !empty($_GET['from']) ? sanitize_text_field($_GET['from']) : '' }}" readonly>
                    <span class="input-group-addon"><?php _e('To', 'municipio'); ?>:</span>
                    <input type="text" name="to" placeholder="<?php _e('To date', 'municipio'); ?>" class="form-control datepicker-range datepicker-range-to" value="{{ isset($_GET['to']) && !empty($_GET['to']) ? sanitize_text_field($_GET['to']) : '' }}" readonly>
                </div>
            </div>
            @endif

            <div class="grid-sm-12 grid-md-fit-content">
                <input type="submit" value="<?php _e('Search', 'municipio'); ?>" class="btn btn-primary btn-block">
            </div>
        </div>

        @if (!empty($enabledTaxonomyFilters))
        <div class="gutter gutter-top" id="options" style="display: none;">
            <div class="grid" data-equal-container>
            @foreach ($enabledTaxonomyFilters as $taxKey => $taxonomy)
                <div class="grid-md-4">
                    <div class="box box-panel box-panel-secondary" data-equal-item>
                        <h4 class="box-title">{{ $taxonomy->label }}</h4>
                        <div class="box-content">
                            <ul>
                            @foreach ($taxonomy->values as $term)
                                <li>
                                    <label class="checkbox">
                                        <input type="checkbox" name="term[]" value="{{ $taxKey }}|{{ $term->slug }}" {{ checked(true, isset($_GET['term']) && is_array($_GET['term']) && in_array($taxKey . '|' . $term->slug, $_GET['term'])) }}> {{ $term->name }}
                                    </label>
                                </li>
                            @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        </div>
        @endif

        @if (!empty($enabledTaxonomyFilters))
        <div class="grid no-margin gutter gutter-top gutter-sm">
            <div class="grid-xs-12">
                <button type="button" data-toggle="#options" class="btn btn-plain pricon pricon-plus-o pricon-space-right" data-toggle-text="Visa färre sökalternativ…" data-toggle-class="btn btn-plain pricon pricon-minus-o pricon-space-right">Visa fler sökalternativ…</a>
            </div>
        </div>
        @endif
    </form>
</section>
@endif
