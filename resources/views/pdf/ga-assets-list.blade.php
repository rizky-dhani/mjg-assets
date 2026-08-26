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
            border: 3px solid #0E0E96;
            border-radius: 6px;
            margin: 8px auto;
            background: #fff;
            overflow: hidden;
        }
        .label-title {
            background: #0E0E96;
            color: #fff;
            font-weight: 700;
            font-size: 12px;
            text-align: left;
            padding: 6px 8px;
            word-break: break-all;
            border-bottom: 1px solid #0E0E96;
        }
        .label-body {
            display: flex;
            align-items: flex-start;
            padding: 6px 8px;
        }
        .qr-code img {
            width: 55px;
            height: auto;
            object-fit: contain;
            display: block;
        }
        .asset-details {
            flex: 1 1 0;
            min-width: 0;
            padding-left: 8px;
        }
        .asset-details .detail-line {
            font-size: 9px;
            line-height: 1.35;
            color: #111;
            text-align: left;
        }
        .asset-details .detail-label {
            font-weight: 600;
        }
        .medquest-logo img {
            max-width: 70px;
            width: 70px;
            height: auto;
            display: block;
            margin-bottom: 2px;
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
                            <div class="label-body">
                                <div class="qr-code">
                                    <img src="{{ asset('storage/' . $asset->barcode) }}" alt="QR Code">
                                </div>
                                <div class="asset-details">
                                    <div class="medquest-logo">
                                        <img src="{{ asset('assets/images/LOGO-MEDQUEST-HD.png') }}" alt="Medquest Jaya Global">
                                    </div>
                                    <div class="detail-line"><span class="detail-label">Date :</span> {{ $asset->asset_year_bought }}</div>
                                    <div class="detail-line"><span class="detail-label">Location :</span> {{ $asset->location->name ?? 'N/A' }}</div>
                                    <div class="detail-line"><span class="detail-label">Initial Name :</span> {{ $asset->user?->employee?->initial ?? 'N/A' }}</div>
                                </div>
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
