@if ($crud->hasAccess('user_platform'))
    <a target="_blank" href="{{'/admin/user/platforms?user_id=' . $entry->getKey()}}" 
    onclick={SetKey($entry.getKey())} class="btn btn-sm btn-link">
        <i class="la-poll-h"></i> {{ trans('main.platforms') }}
    </a>
@endif