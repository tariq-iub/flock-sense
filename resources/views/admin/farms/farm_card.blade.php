<div class="card">
    <div class="card-header">
        <div class="card-title">{{ $farm->name }}</div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <ul class="nav nav-tabs flex-column vertical-tabs-2" role="tablist">
                    @foreach($farm->sheds as $index => $shed)
                        <li class="nav-item" role="presentation">
                            <a class="nav-link {{ $index == 0 ? 'active' : '' }}" data-bs-toggle="tab" role="tab"
                               href="#shed-{{ $shed->id }}" aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                                <p class="mb-1"><i class="feather-home"></i></p>
                                <p class="mb-0 text-break">{{ $shed->name }}</p>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="col-md-9">
                <div class="tab-content">
                    @foreach($farm->sheds as $index => $shed)
                        <div class="tab-pane text-muted {{ $index == 0 ? 'active show' : '' }}"
                             id="shed-{{ $shed->id }}" role="tabpanel">

                            @if($shed->description)
                                <ul class="mb-3">
                                    <li>{!! nl2br(e($shed->description)) !!}</li>
                                </ul>
                                @if($shed->latestFlocks->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-borderless custom-table">
                                            <thead class="thead-light">
                                            <tr>
                                                <th>Flock</th>
                                                <th>Breed</th>
                                                <th class="text-center">Start Date</th>
                                                <th class="text-center">Age</th>
                                                <th class="text-center">Start Count</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($shed->latestFlocks as $flock)
                                            <tr>
                                                <td class="fs-12">{{ $flock->name }}</td>
                                                <td class="fs-12">{{ $flock->breed->name }}</td>
                                                <td class="text-center fs-12">{{ $flock->start_date->format('d-m-Y') }}</td>
                                                <td class="text-center fs-12">
                                                    @if($flock->end_date)
                                                        {{ (int)$flock->start_date->diffInDays($flock->end_date) }}
                                                    @else
                                                        {{ (int)$flock->start_date->diffInDays(now()) }}
                                                    @endif

                                                </td>
                                                <td class="text-center fs-12">{{ $flock->chicken_count }}</td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <div class="text-danger">No flocks available for this shed.</div>
                                @endif
                            @else
                                <div class="text-muted">No data for this shed.</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
