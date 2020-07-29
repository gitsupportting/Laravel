<?php

namespace App\Http\Controllers\GroupManager;

use App\Http\Controllers\Controller;
use App\Models\EmailReport;
use App\Models\Organization;
use App\Reports\ReportFactory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $reportName = $request->get('report', 'completed_courses');
        $report = ReportFactory::fromDefinition($reportName, EmailReport::managerReports())
            ->whereOrganizations(
                Organization::where('id', $request->input('organization'))->get()
            );

        if ($request->has('print')) {
            return $report->view();
        }

        return response()->download($report->toPdf()->stream());
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'command' => ['required', Rule::in(array_keys(EmailReport::managerReports()))],
            'email_to' => 'required|email',
            'weekday' => 'required|in:1,2,3,4,5,6,7',
            'time' => ['required', Rule::in(time_schedule())],
        ]);

        $data['execute_at'] = Carbon::createFromDate(1, 1, 1)
            ->weekday($data['weekday'])
            ->setTimeFromTimeString($data['time']);

        $report = new EmailReport($data);
        $report->group()->associate($request->user()->groups->first());
        $report->saveOrFail();

        flash('Report was successfully created')->success();

        return redirect()->back();
    }

    public function destroy(EmailReport $report, Request $request)
    {
        abort_if(!$request->user()->groups->contains('id', $report->group_id), Response::HTTP_UNAUTHORIZED);

        $report->forceDelete();

        flash('Report was successfully deleted')->success();

        return redirect()->back();
    }
}
