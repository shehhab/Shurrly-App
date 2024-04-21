<?php

namespace App\Http\Controllers\Api\core\Report;

use App\Models\Report;
use App\Models\Seeker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\core\Report\ReportRequest;

class ReportController extends Controller
{
    protected $isSeeker;

    public function __construct()
    {
        $this->isSeeker = request()->header('isSeeker');
    }
    public function __invoke(ReportRequest $request)
    {
        $validatedData = $request->validated();

        // Check if the report is being submitted for the current user
        if ($validatedData['report_to'] == auth()->user()->id) {
            return response()->json(['error' => 'You cannot report yourself.'], 400);
        }

        if ($this->isSeeker) {

        // Check if the user exists and has the required role
        $userHasRole = DB::table('model_has_roles')
                ->where('model_id', $validatedData['report_to'])
                ->where('model_type', 'App\Models\Advisor')
                ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                ->where('roles.name', 'advisor')
                ->exists();

        if (!$userHasRole) {
            return $this->handleResponse(message : 'Report to should be advisors.',status: false ,code :  403);
        }

    }

        // Check if there is an existing report for the same user
        $existingReport = Report::where('report_to', $validatedData['report_to'])
                                 ->where('report_from', auth()->user()->id)
                                 ->first();

        if ($existingReport) {
            // Update the existing report
            $existingReport->update([
                'report' => $validatedData['report'],
                'isSeeker' => $this->isSeeker,
            ]);

            return $this->handleResponse(data: $existingReport);
        }

        // Create a new report if no existing report found
        $report = Report::create([
            'report' => $validatedData['report'],
            'report_to' => $validatedData['report_to'],
            'isSeeker' => $this->isSeeker,
            'report_from' => auth()->user()->id,
        ]);

        return $this->handleResponse(data: $report);
    }

}
