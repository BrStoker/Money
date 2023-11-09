@if ($crud->hasAccess('user_rils'))
    <a target="_blank" href="{{'/admin/user/rils?user_id=' . $entry->getKey()}}" 
    onclick={SetKey($entry.getKey())} class="btn btn-sm btn-link">
        <i class="la-poll-h"></i> {{ trans('main.rils') }}
    </a>
@endif