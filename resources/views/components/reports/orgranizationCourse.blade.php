<div class="table-responsive">
    <table class="table organisation-table report-table " id="organisation-reports-table" role="grid">
        <thead>
        <tr>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @foreach($reports as $report)
            <tr>
                <td><b>{{ $report->name }}</b></td>
            </tr>
            @foreach($report->items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->description }}</td>
                </tr>
            @endforeach
        @endforeach
        </tbody>
    </table>
</div>

@if(request('print'))
<script>
    window.print();
    setTimeout(function () {
        window.close();
    }, 2000);
</script>

@endif
