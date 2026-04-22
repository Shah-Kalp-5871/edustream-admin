@extends('layouts.app', ['title' => 'Account Deletion Requests'])

@section('subtitle', 'Manage user requests for account and data deletion.')

@section('content')
<div class="card animate-scale-in" style="padding: 0;">
    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Requested At</th>
                    <th style="text-align:right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $req)
                <tr>
                    <td>
                        <span style="font-weight:600; font-size:13.5px;">{{ $req->email }}</span>
                    </td>
                    <td style="max-width: 300px;">
                        <p style="font-size: 13px; color: var(--text-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $req->reason }}">
                            {{ $req->reason ?? 'No reason provided' }}
                        </p>
                    </td>
                    <td>
                        <span class="badge {{ $req->status === 'pending' ? 'badge-warning' : ($req->status === 'processed' ? 'badge-success' : 'badge-danger') }}">
                            {{ ucfirst($req->status) }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted); font-size:13px;">{{ $req->created_at->format('M d, Y H:i') }}</td>
                    <td style="text-align:right;">
                        <div style="display:flex; gap:6px; justify-content:flex-end;">
                            @if($req->status === 'pending')
                            <form action="{{ route('admin.deletion-requests.update-status', $req->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="processed">
                                <button type="submit" class="btn btn-success btn-sm" title="Mark as Processed">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.deletion-requests.update-status', $req->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="status" value="cancelled">
                                <button type="submit" class="btn btn-danger btn-sm" title="Cancel">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                            @else
                            <span class="text-muted" style="font-size: 12px italic;">Done</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex-between" style="padding:14px 20px; border-top:1px solid var(--border);">
        <p style="font-size:13px; color:var(--text-muted);">Showing {{ $requests->firstItem() ?? 0 }}–{{ $requests->lastItem() ?? 0 }} of {{ $requests->total() }} requests</p>
        <div style="display:flex; gap:6px;">
            {{ $requests->links('pagination::bootstrap-4') }}
        </div>
    </div>
</div>
@endsection
