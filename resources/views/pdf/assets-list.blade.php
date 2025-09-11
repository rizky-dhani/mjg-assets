<!DOCTYPE html>
<html>
<head>
    <title>Assets List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .asset-label {
            max-width: 240px;
            min-width: 240px;
            border: 4px double #000;
            margin: 8px auto;
            background: #fff;
            display: flex;
            align-items: center;
        }
        .qr-code img {
            width: 65px;
            height: auto;
            object-fit: contain;
            display: block;
            padding: 3px;
        }
        .asset-details {
            flex: 1 1 0;
            min-width: 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: center;
            padding-left: 6px;
        }
        .medquest-logo {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .medquest-logo img {
            max-width: 100px;
            width: 90px;
            height: auto;
            display: block;
            margin-bottom: 2px;
        }
        .asset-code {
            font-size: 12px;
            font-weight: 600;
            word-break: break-all;
            color: #000;
        }
        .asset-code h6 {
            margin: 0;
            padding: 0;
            font-size: 12px;
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
                        <div class="asset-label">
                            <div class="qr-code border-end border-1 border-dark">
                                <img src="{{ asset('storage/' . $asset->barcode) }}" alt="QR Code">
                            </div>
                            <div class="asset-details ps-0">
                                <div class="row m-0">
                                    <div class="col-12 d-flex justify-content-center medquest-logo border-bottom border-1 border-dark">
                                        <img src="{{ asset('assets/images/LOGO-MEDQUEST-HD.png') }}" alt="Medquest Jaya Global" class="pb-1">
                                    </div>
                                    <div class="col-12 asset-code text-center">
                                        <h6 class="pb-1 mt-1">{{ $asset->asset_code }}</h6>
                                    </div>
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
