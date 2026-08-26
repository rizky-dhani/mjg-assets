<!DOCTYPE html>
<html>
<head>
    <title>GA Assets List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #fff; }
        .ga-label {
            max-width: 240px;
            min-width: 240px;
            background: #0E0E96;
            border-radius: 6px;
            margin: 8px auto;
            overflow: hidden;
        }
        .label-title {
            color: #fff;
            font-weight: 700;
            font-size: 13px;
            text-align: left;
            padding: 8px 10px;
            word-break: break-all;
            border-bottom: 1px solid rgba(255, 255, 255, 0.25);
        }
        .asset-details {
            padding: 8px 10px;
        }
        .asset-details .detail-line {
            font-size: 10px;
            line-height: 1.5;
            color: #fff;
            text-align: left;
        }
        .asset-details .detail-label {
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-white">
    @php
$chunks = $assets->chunk(3);
    @endphp
    <div class="container-fluid my-4">
        @foreach($chunks as $chunk)
            <div class="row mb-3 justify-content-start mx-2">
                @foreach($chunk as $asset)
                    <div class="col-4 d-flex justify-content-center">
                        <div class="ga-label">
                            <div class="label-title">{{ $asset->asset_code }}</div>
                            <div class="asset-details">
                                <div class="detail-line"><span class="detail-label">Date :</span> {{ $asset->asset_year_bought }}</div>
                                <div class="detail-line"><span class="detail-label">Location :</span> {{ $asset->location->name ?? 'N/A' }}</div>
                                <div class="detail-line"><span class="detail-label">Initial Name :</span> {{ $asset->user?->employee?->initial ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </div>
                @endforeach
                @for($i = $chunk->count(); $i < 3; $i++)
                    <div class="col-4"></div>
                @endfor
            </div>
        @endforeach
    </div>
</body>
</html>
