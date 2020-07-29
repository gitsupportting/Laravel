<?php

namespace App\Reports;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportFactory
{
    public static function fromDefinition(string $reportName, array $definition)
    {
        if (!array_key_exists($reportName, $definition)) {
            abort(404);
        }

        $report = resolve($definition[$reportName]['class']);

        foreach ($definition[$reportName]['definition'] as $key => $value) {
            $report->{'where'.ucfirst($key)}($value);
        }

        return $report;
    }
}
