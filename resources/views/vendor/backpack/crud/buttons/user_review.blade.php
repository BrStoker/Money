@if ($crud->hasAccess('user_review'))
    <a target="_blank" href="{{'/admin/user/review?user_id=' . $entry->getKey()}}" 
    onclick={SetKey($entry.getKey())} class="btn btn-sm btn-link">
        <i class="la-poll-h"></i> {{ trans('main.review') }}
    </a>
@endif