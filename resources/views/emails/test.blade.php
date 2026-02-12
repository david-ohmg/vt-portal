<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ $heading ?? 'OHMG VT Portal' }}</title>
    <style>
        /* Reset styles */
        body, table, td, a {
            -webkit-text-size-adjust: 100%;
            -ms-text-size-adjust: 100%;
        }
        table, td {
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            height: auto;
            line-height: 100%;
            outline: none;
            text-decoration: none;
        }

        /* Body styles */
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
        }

        /* Container */
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
        }

        /* Header */
        .email-header {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            padding: 40px 30px;
            text-align: center;
        }

        .email-header h1 {
            margin: 0;
            color: #ffffff;
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        /* Content */
        .email-content {
            padding: 40px 30px;
            line-height: 1.6;
        }

        .email-content h2 {
            color: #1f2937;
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 20px 0;
        }

        .email-content p {
            color: #4b5563;
            font-size: 16px;
            margin: 0 0 16px 0;
        }

        .email-content ul {
            color: #4b5563;
            font-size: 16px;
            padding-left: 20px;
            margin: 0 0 16px 0;
        }

        .email-content li {
            margin-bottom: 8px;
        }

        /* Message box */
        .message-box {
            background-color: #f9fafb;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }

        /* Footer */
        .email-footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .email-footer p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
            line-height: 1.6;
        }

        .email-footer a {
            color: #3b82f6;
            text-decoration: none;
        }

        /* Button */
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background-color: #3b82f6;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 0;
        }

        .btn:hover {
            background-color: #2563eb;
        }

        /* Responsive */
        @media only screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
            }
            .email-header, .email-content, .email-footer {
                padding: 20px !important;
            }
            .email-header h1 {
                font-size: 24px !important;
            }
            .email-content h2 {
                font-size: 20px !important;
            }
        }
    </style>
</head>
<body>
<table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0; padding: 20px 0; background-color: #f3f4f6;">
    <tr>
        <td align="center">
            <!-- Email Container -->
            <table role="presentation" cellspacing="0" cellpadding="0" border="0" class="email-container" style="max-width: 600px; background-color: #ffffff; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 8px; overflow: hidden;">

                <!-- Header -->
                <tr>
                    <td class="email-header">
                        <h1>OHMG Voice Talent Portal</h1>
                    </td>
                </tr>

                <!-- Content -->
                <tr>
                    <td class="email-content">
                        @if(isset($heading))
                            <h2>{{ $heading }}</h2>
                        @endif

                        @if(isset($test_message))
                            <div class="message-box">
                                {!! $test_message !!}
                            </div>
                        @endif

                        @if(isset($emailMessage))
                            {!! $emailMessage !!}
                        @endif

                        @if(isset($actionUrl) && isset($actionText))
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <a href="{{ $actionUrl }}" class="btn">{{ $actionText }}</a>
                                    </td>
                                </tr>
                            </table>
                        @endif
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td class="email-footer">
                        <p>
                            <strong>On Hold Media Group</strong><br>
                            Voice Talent Portal<br>
                            <a href="mailto:support@onholdwizard.com">support@onholdwizard.com</a>
                        </p>
                        <p style="margin-top: 15px;">
                            &copy; {{ date('Y') }} On Hold Media Group. All rights reserved.
                        </p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>
