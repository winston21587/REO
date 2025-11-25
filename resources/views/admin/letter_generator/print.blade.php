<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Result of Review - {{ $submission->Study_Protocol_title }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap');
        
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            line-height: 1.4;
            color: #000;
            background: #525659; /* Grey background for preview */
            margin: 0;
            padding: 20px;
        }

        .page {
            background: white;
            width: 210mm; /* A4 width */
            min-height: 297mm; /* A4 height */
            margin: 0 auto;
            padding: 20mm 25mm;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            position: relative;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .header img {
            height: 60px;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 14pt;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }

        .header p {
            margin: 0;
            font-size: 10pt;
        }

        .content-block {
            margin-bottom: 15px;
        }

        .title-box {
            text-align: center;
            font-weight: bold;
            font-style: italic;
            margin: 15px 0;
            padding: 10px;
            border-top: 1px solid #ccc;
            border-bottom: 1px solid #ccc;
        }

        .checklist {
            list-style: none;
            padding: 0;
            margin: 10px 0;
        }

        .checklist li {
            margin-bottom: 5px;
            padding-left: 25px;
            position: relative;
        }

        .checklist li::before {
            content: "☐";
            position: absolute;
            left: 0;
            font-size: 14pt;
            line-height: 1;
        }

        /* Checked state simulated with strict HTML */
        .checklist li.checked::before {
            content: "☑";
            font-weight: bold;
        }

        .section-title {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 15px;
            margin-bottom: 5px;
        }

        .signature {
            margin-top: 40px;
        }

        .signature p {
            margin: 0;
            font-weight: bold;
        }

        /* Print Controls */
        .no-print {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #8B0000;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-family: sans-serif;
            font-weight: bold;
            box-shadow: 0 4px 6px rgba(0,0,0,0.2);
            cursor: pointer;
            border: none;
        }

        .no-print:hover {
            background: #6d0000;
        }

        @media print {
            body {
                background: none;
                padding: 0;
            }
            .page {
                box-shadow: none;
                margin: 0;
                width: auto;
                height: auto;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="no-print">🖨️ Print / Save as PDF</button>

    <div class="page">
        <div class="header">
            <img src="{{ asset('images/wmsu-logo.png') }}" alt="WMSU Logo" style="height: 60px;"> 
            <p>Western Mindanao State University</p>
            <p>Research Extension Services and External Linkage</p>
            <h1>RESEARCH ETHICS OVERSIGHT COMMITTEE</h1>
            <p>Zamboanga City, Philippines</p>
        </div>

        <p style="text-align: right;">Date: <strong>{{ date('F j, Y') }}</strong></p>

        <p>Dear Researcher,</p>
        <p>Good day!</p>
        
        <p>Thank you for submitting your application for Research Ethics Clearance. Based on the <strong>INITIAL REVIEW</strong>, your paper entitled:</p>

        <div class="title-box">
            "{{ $submission->Study_Protocol_title }}"
        </div>

        <p>has been identified as <strong>{{ strtoupper($data['review_type']) }} REVIEW</strong>.</p>

        <p>As suggested by the Ethics Panel, please incorporate the following information as our basis for the recommendation of the Issuance of your Research Ethics Clearance:</p>

        @if(isset($data['protocol_issues']))
            <div class="section-title">In the Protocol/Proposal:</div>
            <ul class="checklist">
                @foreach($data['protocol_issues'] as $issue)
                    <li class="checked">{{ $issue }}</li>
                @endforeach
            </ul>
        @endif

        @if(isset($data['consent_issues']))
            <div class="section-title">In the Informed Consent:</div>
            <ul class="checklist">
                @foreach($data['consent_issues'] as $issue)
                    <li class="checked">{{ $issue }}</li>
                @endforeach
            </ul>
        @endif

        @if(!empty($data['remarks']))
            <div class="section-title">Additional Notes:</div>
            <p style="font-style: italic; border: 1px solid #eee; padding: 10px;">{{ $data['remarks'] }}</p>
        @endif

        <div style="margin-top: 30px;">
            <p>To facilitate the checking of the Panel's suggestions, please highlight the required information in your Protocol or Informed Consent.</p>
            <p>Please submit your revised copy placed in a long expanded envelope to the REOC office. Once the suggestions are incorporated, it will be forwarded to the Ethics Panel for review.</p>
        </div>

        @if(isset($data['recommended_actions']))
            <div class="section-title" style="margin-top: 20px;">Recommended Actions:</div>
            <ul class="checklist">
                @foreach($data['recommended_actions'] as $action)
                    <li class="checked">{{ $action }}</li>
                @endforeach
            </ul>
        @endif

        <div class="signature">
            <p>Thank you very much.</p>
            <br><br><br>
            <p>ANALYN D. SAAVEDRA, Ph. D.</p>
            <p style="font-weight: normal;">WMSU REOC Chair</p>
        </div>
    </div>

</body>
</html>