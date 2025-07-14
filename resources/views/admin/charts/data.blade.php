<div class="table-responsive">
    <table class="table datatable">
        <thead class="thead-light">
        <tr>
            <th>Type</th>
            <th class="text-center">Day</th>
            <th class="text-center">Weight</th>
            <th class="text-center">Daily Gain</th>
            <th class="text-center">Avg Daily Gain</th>
            <th class="text-center">Daily Intake</th>
            <th class="text-center">Cum Intake</th>
            <th class="text-center">FCR</th>
        </tr>
        </thead>
        <tbody>
        @foreach($chart->data as $row)
            <tr>
                <td>{{ $row->type }}</td>
                <td class="text-center">{{ $row->day }}</td>
                <td class="text-center">{{ $row->weight . ' ' . $chart->unit }}</td>
                <td class="text-center">{{ $row->daily_gain ? $row->daily_gain . ' ' . $chart->unit : '' }}</td>
                <td class="text-center">{{ $row->avg_daily_gain ? $row->avg_daily_gain . ' ' . $chart->unit : '' }}</td>
                <td class="text-center">{{ $row->daily_intake ? $row->daily_intake . ' ' . $chart->unit : '' }}</td>
                <td class="text-center">{{ $row->cum_intake ? $row->cum_intake . ' ' . $chart->unit : '' }}</td>
                <td class="text-center">{{ $row->fcr ?? '' }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
