<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Revision Letter - {{ $submission->Study_Protocol_title }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 40px;
        }

        .header {
            border-bottom: 2px solid #8B0000;
            padding-bottom: 20px;
            margin-bottom: 30px;
            text-align: center;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #8B0000;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }

        .info-table th {
            text-align: left;
            padding: 8px;
            width: 25%;
            font-weight: bold;
            color: #555;
        }

        .info-table td {
            padding: 8px;
            border-bottom: 1px solid #f0f0f0;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section h3 {
            color: #8B0000;
            font-size: 16px;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
            margin-bottom: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .content {
            white-space: pre-wrap;
            font-size: 14px;
            background: #fafafa;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #8B0000;
            color: #444;
        }

        .footer {
            margin-top: 50px;
            font-size: 12px;
            color: #777;
            text-align: center;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo">Research Ethics Committee</div>
        <div class="subtitle">Official Revision Required Notice</div>
    </div>

    <table class="info-table">
        <tr>
            <th>Protocol ID:</th>
            <td>{{ $submission->protocol_code ?? 'REC-' . str_pad($submission->id, 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <th>Protocol Title:</th>
            <td><strong>{{ $submission->Study_Protocol_title }}</strong></td>
        </tr>
        <tr>
            <th>Primary Investigator:</th>
            <td>{{ $submission->researcher->user->first_name ?? '' }}
                {{ $submission->researcher->user->last_name ?? '' }}</td>
        </tr>
        <tr>
            <th>Date Issued:</th>
            <td>{{ now()->format('F j, Y') }}</td>
        </tr>
    </table>

    <p style="font-size: 14px; margin-bottom: 30px;">
        Dear {{ $submission->researcher->user->first_name ?? 'Researcher' }},<br><br>
        The Research Ethics Committee has reviewed your protocol submission. Before ethics clearance can be issued, the
        following modifications, clarifications, and requirements must be met. Please review the detailed assessment
        below:
    </p>

    <!-- Qualitative Feedback Sections -->
    <div class="section">
        <h3>1. Scientific Soundness & Methodology</h3>
        <div class="content">{{ $data['scientific_soundness'] ?? 'No specific issues noted.' }}</div>
    </div>

    <div class="section">
        <h3>2. Ethical Considerations</h3>
        <div class="content">{{ $data['ethical_issues'] ?? 'No specific issues noted.' }}</div>
    </div>

    <div class="section">
        <h3>3. Informed Consent Form (ICF)</h3>
        <div class="content">{{ $data['icf_issues'] ?? 'No specific issues noted.' }}</div>
    </div>

    <div class="section">
        <h3>4. Summary of Required Resolutions</h3>
        <div class="content">{{ $data['summary_of_issues'] ?? 'Please address the points above.' }}</div>
    </div>

    <div class="footer">
        <p>This is a system-generated document. Please submit your revised documents along with a cover letter
            addressing each point sequentially.</p>
    </div>
</body>

</html>