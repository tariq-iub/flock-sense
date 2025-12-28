<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Labels - FlockSense</title>
    <style>
        @media print {
            @page {
                margin: 0.5cm;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none !important;
            }
            .qr-label {
                page-break-inside: avoid;
                border: 2px solid #000 !important;
                margin: 5px !important;
            }
        }

        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .qr-label {
            background: white;
            border: 2px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            align-items: flex-start;
            gap: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .qr-label.small {
            max-width: 350px;
            padding: 10px;
            gap: 10px;
        }

        .qr-label.medium {
            max-width: 450px;
            padding: 15px;
            gap: 15px;
        }

        .qr-label.large {
            max-width: 550px;
            padding: 20px;
            gap: 20px;
        }

        .qr-code-container {
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
        }

        .qr-code-container img {
            display: block;
        }

        .qr-serial-number {
            font-weight: bold;
            font-size: 1.1em;
            color: #333;
            text-align: center;
        }

        .qr-label.small .qr-serial-number {
            font-size: 0.9em;
        }

        .qr-label.large .qr-serial-number {
            font-size: 1.3em;
        }

        .qr-label.small .qr-code-container img {
            width: 80px;
            height: 80px;
        }

        .qr-label.medium .qr-code-container img {
            width: 120px;
            height: 120px;
        }

        .qr-label.large .qr-code-container img {
            width: 160px;
            height: 160px;
        }

        .qr-details {
            flex-grow: 1;
        }

        .qr-details h3 {
            margin: 0 0 10px 0;
            font-size: 1.5em;
            color: #333;
        }

        .qr-label.small .qr-details h3 {
            font-size: 1.2em;
            margin-bottom: 5px;
        }

        .qr-label.medium .qr-details h3 {
            font-size: 1.4em;
        }

        .qr-label.large .qr-details h3 {
            font-size: 1.8em;
        }

        .qr-detail-row {
            display: flex;
            margin: 4px 0;
            font-size: 0.9em;
        }

        .qr-label.small .qr-detail-row {
            font-size: 0.8em;
        }

        .qr-label.large .qr-detail-row {
            font-size: 1em;
        }

        .qr-detail-label {
            font-weight: bold;
            min-width: 100px;
            color: #666;
        }

        .qr-label.small .qr-detail-label {
            min-width: 70px;
        }

        .qr-label.large .qr-detail-label {
            min-width: 120px;
        }

        .qr-detail-value {
            color: #333;
        }

        .print-controls {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .print-btn {
            background-color: #0d6efd;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }

        .print-btn:hover {
            background-color: #0b5ed7;
        }

        .back-btn {
            background-color: #6c757d;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            display: inline-block;
        }

        .back-btn:hover {
            background-color: #5a6268;
        }

        .container {
            max-width: 1400px;
            margin: 20px auto;
            padding: 0 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding: 20px;
            background: white;
            border-radius: 8px;
        }

        .header h1 {
            margin: 0;
            color: #333;
        }

        .header p {
            margin: 5px 0 0;
            color: #666;
        }

        .badges {
            display: flex;
            flex-wrap: wrap;
            gap: 5px;
            margin-top: 5px;
        }

        .badge {
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 0.75em;
            background: #e9ecef;
        }

        .qr-label.small .badge {
            font-size: 0.65em;
            padding: 1px 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Print Controls -->
        <div class="print-controls no-print">
            <div class="header">
                <h1>QR Code Labels</h1>
                <p>{{ count($devices) }} device(s) selected - {{ ucfirst($labelSize) }} labels</p>
            </div>
            <div style="text-align: center;">
                <button class="print-btn" onclick="window.print()">
                    <i style="margin-right: 8px;">🖨️</i> Print Labels
                </button>
                <a href="{{ route('qr-code.index') }}" class="back-btn">
                    ← Back to Selection
                </a>
            </div>
        </div>

        <!-- QR Code Labels -->
        @foreach($devices as $device)
            <div class="qr-label {{ $labelSize }}">
                <div class="qr-code-container">
                    <img src="{{ route('qr-code.download', $device) }}" alt="QR Code for {{ $device->serial_no }}">
                    <div class="qr-serial-number">{{ $device->serial_no }}</div>
                </div>

                @if($includeDetails)
                    <div class="qr-details">
                        @if($device->model_number)
                            <div class="qr-detail-row">
                                <span class="qr-detail-label">Model: </span>
                                <span class="qr-detail-value"> {{ $device->model_number }}</span>
                            </div>
                        @endif

                        @if($device->manufacturer)
                            <div class="qr-detail-row">
                                <span class="qr-detail-label">Manufacturer: </span>
                                <span class="qr-detail-value"> {{ $device->manufacturer }}</span>
                            </div>
                        @endif

                        @if($device->firmware_version)
                            <div class="qr-detail-row">
                                <span class="qr-detail-label">Firmware: </span>
                                <span class="qr-detail-value"> {{ $device->firmware_version }}</span>
                            </div>
                        @endif

                        <div class="qr-detail-row">
                            <span class="qr-detail-label">Connectivity: </span>
                            <span class="qr-detail-value"> {{ $device->connectivity_type }}</span>
                        </div>

                        @if($device->capabilities->isNotEmpty())
                            <div class="qr-detail-row">
                                <span class="qr-detail-label">Capabilities: </span>
                                <div class="qr-detail-value badges">
                                    @foreach($device->capabilities as $cap)
                                        <span class="badge"> {{ ucfirst($cap->name) }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach

        <!-- Print Controls (Bottom) -->
        <div class="print-controls no-print" style="margin-top: 20px;">
            <div style="text-align: center;">
                <button class="print-btn" onclick="window.print()">
                    <i style="margin-right: 8px;">🖨️</i> Print Labels
                </button>
                <a href="{{ route('qr-code.index') }}" class="back-btn">
                    ← Back to Selection
                </a>
            </div>
        </div>
    </div>
</body>
</html>
