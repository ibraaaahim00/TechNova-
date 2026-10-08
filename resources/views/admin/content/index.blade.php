@extends('layouts.admin')
@section('title', __($config['label']))
@section('crumb', __($config['label']))
@section('content')
<div class="admin-page-heading"><div><span class="admin-kicker">{{ __('CONTENT MANAGEMENT') }}</span><h1>{{ __($config['label']) }}</h1><p>{{ $type === 'settings' ? __('These settings control the public footer, company details, contact information, and social links.') : ($type === 'navigation' ? __('These links appear in the public website header and footer.') : __('Manage the content that appears on your public website.')) }}</p></div>@if($type !== 'messages')<a class="button button-primary" href="{{ route('admin.content.create', $type) }}">＋ {{ __('Add') }} {{ __(str($config['label'])->singular()->lower()->toString()) }}</a>@endif</div>
<section class="admin-table-wrap"><div class="table-toolbar"><span>{{ $records->total() }} {{ __(str($config['label'])->lower()->toString()) }}</span><span>{{ __('Changes publish to the website immediately') }}</span></div>
    @if($records->isEmpty())<div class="admin-empty"><span>▦</span><h2>{{ __('Nothing here yet') }}</h2><p>{{ __('Add your first item to get started.') }}</p>@if($type !== 'messages')<a class="button button-primary" href="{{ route('admin.content.create', $type) }}">{{ __('Create item') }}</a>@endif</div>
    @else
        <div class="table-scroll"><table><thead><tr><th>{{ __('Content') }}</th><th>{{ __('Status') }}</th><th>{{ __('Updated') }}</th><th class="table-actions">{{ __('Actions') }}</th></tr></thead><tbody>
            @foreach($records as $record)
                <tr><td><div class="record-title">@if($record->image_path ?? $record->cover_path ?? $record->photo_path)<img src="{{ Storage::disk('public')->url($record->image_path ?? $record->cover_path ?? $record->photo_path) }}" alt="">@else<span class="record-icon">{{ $type === 'messages' ? '✉' : str($record->title ?? $record->name ?? $record->key ?? $record->label ?? $record->person_name ?? 'T')->substr(0, 1) }}</span>@endif<div><b>{{ $type === 'messages' ? $record->name : ($record->title ?? $record->name ?? $record->key ?? $record->label ?? $record->person_name ?? __('Untitled')) }}</b><small>{{ $type === 'messages' ? $record->subject.' · '.$record->email : ($record->summary ?? $record->email ?? $record->role ?? $record->value ?? $record->url ?? $record->subject ?? '') }}</small></div></div></td>
                    <td><span class="status-pill {{ ($record->is_published ?? $record->is_active ?? $record->is_visible ?? $record->is_approved ?? ($record->read_at ?? false) ? 'published' : '') }}">{{ $type === 'messages' ? __($record->archived_at ? 'Archived' : ($record->read_at ? 'Read' : 'Unread')) : __((($record->is_published ?? $record->is_active ?? $record->is_visible ?? $record->is_approved ?? false) ? 'Active' : 'Draft / hidden')) }}</span>@if($record->is_featured ?? false)<span class="status-pill featured">{{ __('Featured') }}</span>@endif</td><td>{{ $record->updated_at?->diffForHumans() }}</td>
                    <td class="table-actions">
                        @if($type === 'messages')<div class="row-action-group"><form method="post" action="{{ route('admin.content.update', [$type, $record->id]) }}">@csrf @method('PUT')<input type="hidden" name="action" value="{{ $record->read_at ? 'unread' : 'read' }}"><button class="small-action" type="submit">{{ __($record->read_at ? 'Mark unread' : 'Mark read') }}</button></form><form method="post" action="{{ route('admin.content.update', [$type, $record->id]) }}">@csrf @method('PUT')<input type="hidden" name="action" value="archive"><button class="small-action" type="submit">{{ __('Archive') }}</button></form></div><details class="message-details"><summary>{{ __('Read message') }}</summary><p>{{ $record->message }}</p></details>
                        @else<a class="small-action" href="{{ route('admin.content.edit', [$type, $record->id]) }}">{{ __('Edit') }}</a>@endif
                        <form method="post" action="{{ route('admin.content.destroy', [$type, $record->id]) }}" data-confirm="{{ __('Delete this item permanently?') }}">@csrf @method('DELETE')<button class="small-action danger-action" type="submit">{{ __('Delete') }}</button></form>
                    </td>
                </tr>
            @endforeach
        </tbody></table></div><div class="pagination-wrap">{{ $records->links() }}</div>
    @endif
</section>
@endsection
