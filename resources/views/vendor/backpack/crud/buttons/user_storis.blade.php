@if ($crud->hasAccess('user_storis'))
    <a target="_blank" href="{{'/admin/user/storis?user_id=' . $entry->getKey()}}" 
    onclick={SetKey($entry.getKey())} class="btn btn-sm btn-link">
        <i class="la-poll-h"></i> {{ trans('main.storis') }}
    </a>
@endif