<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\GovernmentDirectoryService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

final class GovernmentDirectoryController extends Controller
{
    public function index(Request $request, GovernmentDirectoryService $directory)
    {
        [$workspace, $kind, $title] = $this->context($request);
        $directory->authorize($request->user(), $workspace);
        $statuses = $kind === 'programs' ? ['planned', 'running', 'completed', 'cancelled'] : ['draft', 'pending_review', 'revision', 'rejected', 'approved'];
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'], 'district' => ['nullable', 'string', 'max:32'], 'category' => ['nullable', 'string', 'max:190'],
            'status' => ['nullable', Rule::in($statuses)], 'operational' => ['nullable', Rule::in(['active', 'inactive'])],
            'publication' => ['nullable', Rule::in(['published', 'unpublished', 'archived'])], 'profile' => ['nullable', Rule::in(['public', 'private'])],
            'identity' => [$workspace === 'admin' ? 'nullable' : 'prohibited', Rule::in(['unverified', 'pending', 'verified', 'revision', 'rejected'])],
        ]);

        return view('government.directory.index', array_merge($directory->listing($request->user(), $workspace, $kind, $filters), compact('workspace', 'kind', 'title', 'filters')));
    }

    public function show(Request $request, GovernmentDirectoryService $directory, string $record)
    {
        [$workspace, $kind, $title] = $this->context($request);
        if ($kind === 'youth') {
            $record = (new User)->resolveRouteBinding($record)?->getKey();
            abort_unless($record, 404);
        }

        return view('government.directory.show', array_merge($directory->detail($request->user(), $workspace, $kind, $record), compact('workspace', 'kind', 'title')));
    }

    private function context(Request $request): array
    {
        $workspace = $request->routeIs('admin.*') ? 'admin' : 'verifier';
        $kind = $request->route()->defaults['directory_kind'];
        $title = ['youth' => 'Pemuda Terdaftar', 'communities' => 'Community Terdaftar', 'activities' => 'Activity Terdaftar', 'programs' => 'Program Terdaftar'][$kind];

        return [$workspace, $kind, $title];
    }
}
