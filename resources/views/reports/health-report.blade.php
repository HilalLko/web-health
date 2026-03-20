<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Website Health Report</title>
    <style>
        body {
            font-family: sans-serif;
            color: #333;
            font-size: 12px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #4F46E5;
            margin: 0 0 10px 0;
        }

        .card {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            page-break-inside: avoid;
        }

        .status-ok {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .status-warning {
            background-color: #fff3cd;
            color: #856404;
            border-color: #ffeaa7;
        }

        .status-error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .section-title {
            font-size: 1.2em;
            font-weight: bold;
            margin-bottom: 10px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }

        .metric {
            margin: 5px 0;
        }

        .label {
            font-weight: bold;
        }

        .grid {
            display: table;
            width: 100%;
        }

        .grid-item {
            display: table-cell;
            width: 50%;
            padding: 5px;
        }

        .security-header {
            padding: 8px;
            margin: 5px 0;
            border-radius: 3px;
            font-size: 11px;
        }

        .broken-link {
            padding: 5px;
            margin: 3px 0;
            background-color: #f8f9fa;
            border-left: 3px solid #dc3545;
            font-size: 10px;
        }

        .score-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Website Health Report</h1>
        <p><strong>URL:</strong> {{ $results['url'] }}</p>
        <p><strong>Date:</strong> {{ \Carbon\Carbon::parse($results['timestamp'])->toDayDateTimeString() }}</p>
    </div>

    <!-- HTTP Status -->
    <div class="card {{ $results['status'] >= 200 && $results['status'] < 300 ? 'status-ok' : 'status-error' }}">
        <div class="section-title">HTTP Check</div>
        <div class="metric"><span class="label">Status Code:</span> {{ $results['status'] }}</div>
        <div class="metric"><span class="label">Response Time:</span> {{ $results['responseTime'] }} ms</div>
        @if(isset($results['error']))
            <div class="metric"><span class="label">Error:</span> {{ $results['error'] }}</div>
        @endif
    </div>

    <!-- DNS -->
    <div
        class="card {{ isset($results['dnsError']) ? 'status-error' : ($results['dnsSlow'] ? 'status-warning' : 'status-ok') }}">
        <div class="section-title">DNS Resolution</div>
        <div class="metric"><span class="label">Resolution Time:</span> {{ $results['dnsTime'] }} ms
            @if($results['dnsSlow'])
                <span style="color: #856404;">(Slow - >100ms)</span>
            @endif
        </div>
        <div class="metric"><span class="label">Records Found:</span> {{ count($results['dnsRecords']) }}</div>
        @if(isset($results['dnsError']))
            <div class="metric"><span class="label">Error:</span> {{ $results['dnsError'] }}</div>
        @endif
    </div>

    <!-- SSL -->
    @if($results['sslValid'] !== null)
        <div class="card {{ $results['sslValid'] ? 'status-ok' : 'status-error' }}">
            <div class="section-title">SSL Certificate</div>
            <div class="metric"><span class="label">Valid:</span> {{ $results['sslValid'] ? 'Yes' : 'No' }}</div>
            @if($results['sslValid'])
                <div class="metric"><span class="label">Issuer:</span> {{ $results['issuer'] ?? 'N/A' }}</div>
                <div class="metric"><span class="label">Expires:</span>
                    {{ \Carbon\Carbon::parse($results['expiration'])->toFormattedDateString() }}</div>
            @else
                <div class="metric"><span class="label">Error:</span> {{ $results['sslError'] ?? 'Unknown' }}</div>
            @endif
        </div>
    @endif

    <!-- Security Headers -->
    @if(!empty($results['securityHeaders']))
        <div class="card">
            <div class="section-title">Security Headers Analysis</div>
            @foreach($results['securityHeaders'] as $headerName => $headerInfo)
                <div class="security-header {{ $headerInfo['status'] === 'pass' ? 'status-ok' : 'status-error' }}">
                    <strong>{{ $headerName }}:</strong> {{ $headerInfo['status'] === 'pass' ? 'Present' : 'Missing' }}
                    @if($headerInfo['status'] === 'fail')
                        <br><small>{{ $headerInfo['recommendation'] }}</small>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Lighthouse Scores -->
    @if($results['performanceScore'] !== null)
        <div class="card">
            <div class="section-title">Lighthouse Scores</div>
            <div class="grid">
                <div class="grid-item">
                    <div class="metric">
                        <span
                            class="score-badge {{ $results['performanceScore'] >= 90 ? 'status-ok' : ($results['performanceScore'] >= 50 ? 'status-warning' : 'status-error') }}">
                            {{ round($results['performanceScore']) }}
                        </span>
                        <span class="label">Performance</span>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="metric">
                        <span
                            class="score-badge {{ $results['accessibilityScore'] >= 90 ? 'status-ok' : ($results['accessibilityScore'] >= 50 ? 'status-warning' : 'status-error') }}">
                            {{ round($results['accessibilityScore']) }}
                        </span>
                        <span class="label">Accessibility</span>
                    </div>
                </div>
            </div>
            <div class="grid">
                <div class="grid-item">
                    <div class="metric">
                        <span
                            class="score-badge {{ $results['bestPracticesScore'] >= 90 ? 'status-ok' : ($results['bestPracticesScore'] >= 50 ? 'status-warning' : 'status-error') }}">
                            {{ round($results['bestPracticesScore']) }}
                        </span>
                        <span class="label">Best Practices</span>
                    </div>
                </div>
                <div class="grid-item">
                    <div class="metric">
                        <span
                            class="score-badge {{ $results['seoScore'] >= 90 ? 'status-ok' : ($results['seoScore'] >= 50 ? 'status-warning' : 'status-error') }}">
                            {{ round($results['seoScore']) }}
                        </span>
                        <span class="label">SEO</span>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Core Web Vitals -->
    @if(!empty(array_filter($results['metrics'])))
        <div class="card">
            <div class="section-title">Core Web Vitals & Metrics</div>
            @if($results['metrics']['lcp'])
                <div class="metric"><span class="label">LCP (Largest Contentful Paint):</span> {{ $results['metrics']['lcp'] }}
                    ms</div>
            @endif
            @if($results['metrics']['fcp'])
                <div class="metric"><span class="label">FCP (First Contentful Paint):</span> {{ $results['metrics']['fcp'] }} ms
                </div>
            @endif
            @if($results['metrics']['cls'])
                <div class="metric"><span class="label">CLS (Cumulative Layout Shift):</span> {{ $results['metrics']['cls'] }}
                </div>
            @endif
            @if($results['metrics']['tti'])
                <div class="metric"><span class="label">TTI (Time to Interactive):</span> {{ $results['metrics']['tti'] }} ms
                </div>
            @endif
            @if($results['metrics']['tbt'])
                <div class="metric"><span class="label">TBT (Total Blocking Time):</span> {{ $results['metrics']['tbt'] }} ms
                </div>
            @endif
        </div>
    @endif

    <!-- Broken Links -->
    @if(!empty($results['brokenLinks']))
        <div class="card status-warning">
            <div class="section-title">Broken Links Found ({{ count($results['brokenLinks']) }})</div>
            @foreach($results['brokenLinks'] as $link)
                <div class="broken-link">
                    <strong>{{ $link['url'] }}</strong><br>
                    Status: {{ $link['status'] ?? 'Failed' }}{{ isset($link['error']) ? ' - ' . $link['error'] : '' }}
                </div>
            @endforeach
        </div>
    @endif

</body>

</html>