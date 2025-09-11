<div>
    <div class="mb-4">
        @auth
            <a href="{{ route('filament.admin.resources.it-assets.view', $asset->assetId) }}" class="btn btn-primary mb-2">
                <i class="fa-solid fa-arrow-left"></i> {{ __('Back') }}
            </a>
        @endauth
        <div class="d-flex justify-content-center">
            <img src="{{ asset('storage/' . $asset->barcode) }}" alt="Barcode" class="img-fluid" style="height: 128px;">
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped mb-0">
            <tbody>
                <tr>
                    <th class="text-start">Asset Name</th>
                    <td>{{ $asset->asset_name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="text-start">Asset Code</th>
                    <td>{{ $asset->asset_code ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="text-start">Asset Year</th>
                    <td>{{ $asset->asset_year_bought ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="text-start">Serial Number</th>
                    <td>{{ $asset->asset_serial_number ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="text-start">Condition</th>
                    <td>{{ $asset->asset_condition ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="text-start">Notes</th>
                    <td>{{ $asset->asset_notes ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="text-start">Location</th>
                    <td>{{ $asset->location->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="text-start">User</th>
                    <td>{{ $asset->employee->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th class="text-start">Entry By</th>
                    <td>
                        @if($asset->user && $asset->user->employee)
                            {{ $asset->user->employee->initial . ' ' . strtoupper($asset->created_at->format('d M Y')) }}
                        @else
                            N/A
                        @endif
                    </td>
                </tr>
                <tr>
                    <th class="text-start">Remarks</th>
                    <td>{{ $asset->asset_remarks ?? 'N/A' }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
