<div class="responsive-table">
    <table class="table table-responsive-sm">
        <thead>
        @if($version == 'en')
            <tr>
                <th>Parameter</th>
                <th class="text-end">Value</th>
            </tr>
        @else
            <tr>
                <th class="text-center">Value</th>
                <th class="text-end">Parameter</th>
            </tr>
        @endif
        </thead>
        <tbody>
        @foreach($payload as $key => $value)
            @if($version == 'en')
                <tr>
                    <td class="fw-bold">{{ ($key == 'fcr' || $key == 'cv' || $key == 'pef') ? Str::upper($key) : Str::title(Str::replace('_', ' ', $key)) }}</td>
                    <td class="text-end">{{ $value }}</td>
                </tr>
            @else
                <tr>
                    <td class="text-center urdu_normal">{{ $value }}</td>
                    <td class="urdu_normal fw-bold">{{ $key }}</td>
                </tr>
            @endif
        @endforeach
        </tbody>
    </table>
</div>
