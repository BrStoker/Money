@if ($crud->hasAccess('user_post'))
    <a target="_blank" href="{{'/admin/user/post?user_id=' . $entry->getKey()}}" 
    onclick={SetKey($entry.getKey())} class="btn btn-sm btn-link">
        <i class="la-poll-h"></i> {{ trans('main.post') }}
    </a>
@endif