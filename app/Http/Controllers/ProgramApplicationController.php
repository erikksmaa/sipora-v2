<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Services\Program\ProgramIntakeService;
use Illuminate\Http\Request;

final class ProgramApplicationController extends Controller
{
    public function store(Request $request, Program $program, ProgramIntakeService $intake)
    {
        $intake->apply($request->user(), $program);

        return to_route('programs.show', $program)->with('status', 'Pendaftaran Program berhasil disimpan.');
    }

    public function withdraw(Request $request, Program $program, ProgramIntakeService $intake)
    {
        $intake->withdraw($request->user(), $program);

        return back()->with('status', 'Pendaftaran Program ditarik kembali.');
    }
}
