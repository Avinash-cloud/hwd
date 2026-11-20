<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Haridwar Bliss - Sacred Gangajal & Vedic Offerings' }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #F8FAFC;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1E293B;
            line-height: 1.6;
        }
        .container {
            max-width: 620px;
            margin: 30px auto;
            background-color: #FFFFFF;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid #E2E8F0;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }
        .header {
            background: linear-gradient(135deg, #FFFDF7 0%, #FEF3C7 100%);
            padding: 32px 28px;
            text-align: center;
            border-bottom: 2px solid #F59E0B;
        }
        .om-symbol {
            font-size: 32px;
            color: #D97706;
            margin-bottom: 8px;
            display: inline-block;
        }
        .brand-title {
            font-size: 24px;
            font-weight: 800;
            color: #78350F;
            letter-spacing: 0.5px;
            margin: 0;
            font-family: Georgia, serif;
        }
        .brand-subtitle {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #B45309;
            margin-top: 4px;
            font-weight: 700;
        }
        .content {
            padding: 32px 28px;
        }
        .greeting {
            font-size: 18px;
            font-weight: 700;
            color: #0F172A;
            margin-bottom: 12px;
        }
        .blessing-badge {
            background-color: #FEF3C7;
            border-left: 4px solid #D97706;
            padding: 12px 16px;
            border-radius: 6px;
            margin: 18px 0;
            font-size: 13px;
            color: #92400E;
            font-style: italic;
        }
        .card {
            background-color: #F8FAFC;
            border: 1px solid #E2E8F0;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }
        .btn {
            display: inline-block;
            background-color: #D97706;
            color: #FFFFFF !important;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            text-align: center;
            box-shadow: 0 2px 6px rgba(217, 119, 6, 0.3);
            margin: 15px 0;
        }
        .btn-outline {
            display: inline-block;
            background-color: #FFFFFF;
            color: #78350F !important;
            border: 1px solid #D97706;
            text-decoration: none;
            padding: 10px 22px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 13px;
            text-align: center;
            margin: 10px 5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .items-table th {
            text-align: left;
            padding: 10px 8px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748B;
            border-bottom: 2px solid #E2E8F0;
        }
        .items-table td {
            padding: 12px 8px;
            font-size: 13px;
            border-bottom: 1px solid #F1F5F9;
            color: #334155;
        }
        .total-row td {
            border-top: 2px solid #CBD5E1;
            font-weight: 800;
            color: #0F172A;
            font-size: 15px;
        }
        .footer {
            background-color: #FFFDF7;
            padding: 28px;
            text-align: center;
            border-top: 1px solid #E2E8F0;
            font-size: 12px;
            color: #64748B;
        }
        .footer a {
            color: #D97706;
            text-decoration: none;
        }
        .footer-blessing {
            font-family: Georgia, serif;
            font-size: 14px;
            color: #B45309;
            margin-bottom: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Sacred Header -->
        <div class="header">
            <div class="om-symbol">🕉️</div>
            <h1 class="brand-title">Haridwar Bliss</h1>
            <div class="brand-subtitle">Consecrated Ganges Water & Vedic Offerings • Har Ki Pauri</div>
        </div>

        <!-- Main Body -->
        <div class="content">
            @yield('content')
        </div>

        <!-- Sacred Footer -->
        <div class="footer">
            <div class="footer-blessing">ॐ नमो भगवते वासुदेवाय • हर हर गङ्गे 🙏</div>
            <p style="margin: 6px 0;">
                Sacred fulfillment direct from Haridwar ghats to your home sanctuary.
            </p>
            <p style="margin: 6px 0; font-size: 11px;">
                Questions about your sacred order? Reach our devotee support at
                <a href="mailto:support@haridwarbliss.com">support@haridwarbliss.com</a> or visit
                <a href="{{ url('/') }}">{{ parse_url(config('app.url', 'http://localhost'), PHP_URL_HOST) }}</a>
            </p>
            <p style="margin-top: 12px; font-size: 10px; color: #94A3B8;">
                © {{ date('Y') }} Haridwar Bliss. All rights reserved. Har Ki Pauri Ghat Road, Haridwar, Uttarakhand - 249401.
            </p>
        </div>
    </div>
</body>
</html>
