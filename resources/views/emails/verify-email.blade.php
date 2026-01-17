<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Verify Your Email Address</title>
    <style type="text/css">
        /* Base Reset */
        body, td, div, p, a, input, button { font-family: 'Courier New', Courier, monospace; }
        body { margin: 0; padding: 0; background-color: #020617; color: #cbd5e1; }
        
        /* Client-specific resets */
        table { border-collapse: collapse; mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        
        /* Dark Mode Support */
        @media (prefers-color-scheme: dark) {
            body { background-color: #020617 !important; color: #cbd5e1 !important; }
        }

        /* Utilities */
        .wrapper { width: 100%; table-layout: fixed; background-color: #020617; padding-bottom: 40px; }
        .main-table { background-color: #0f172a; margin: 0 auto; width: 100%; max-width: 600px; border: 1px solid #1e293b; }
        .header { background-color: #0f172a; padding: 30px 20px; border-bottom: 1px solid #1e293b; text-align: center; }
        .content { padding: 40px 30px; }
        .footer { padding: 20px; text-align: center; font-size: 10px; color: #64748b; background-color: #020617; }
        
        /* Typography */
        h1 { color: #f1f5f9; font-size: 20px; margin-bottom: 20px; text-transform: uppercase; letter-spacing: 1px; }
        p { font-size: 14px; line-height: 1.6; margin-bottom: 20px; color: #94a3b8; }
        
        /* Elements */
        .btn { display: inline-block; padding: 12px 24px; background-color: #0891b2; color: #ffffff !important; text-decoration: none; font-weight: bold; font-size: 14px; border: 1px solid #06b6d4; text-transform: uppercase; letter-spacing: 1px; }
        .btn:hover { background-color: #06b6d4; }
        
        .code-block { background-color: #1e293b; padding: 15px; border-left: 3px solid #06b6d4; font-size: 12px; color: #e2e8f0; margin-bottom: 20px; word-break: break-all; }
        
        .accent-text { color: #06b6d4; }
        .status-badge { display: inline-block; padding: 4px 8px; border: 1px solid #06b6d4; color: #06b6d4; font-size: 10px; text-transform: uppercase; }
        
        .divider { border-top: 1px solid #1e293b; margin: 30px 0; }
        
        /* Cyberpunk corners */
        .corner-accent { color: #06b6d4; font-size: 10px; }
    </style>
</head>
<body>
    <table class="wrapper" role="presentation">
        <tr>
            <td align="center">
                <table class="main-table" role="presentation">
                    <!-- HEADER -->
                    <tr>
                        <td class="header">
                            <table width="100%">
                                <tr>
                                    <td align="left" width="50%">
                                        <span style="color: #f1f5f9; font-weight: bold; font-size: 16px;">MATA<span class="accent-text">JALAN</span>_OS</span>
                                    </td>
                                    <td align="right" width="50%">
                                        <span class="status-badge">SYSTEM_AUTH</span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                    <!-- CONTENT -->
                    <tr>
                        <td class="content">
                            <h1>Identify Verification Required</h1>
                            
                            <p>Greetings, User.</p>
                            
                            <p>A request to register a new operative account has been received by the Matajalan Surveillance Network. To access the system, you must verify your encrypted communication channel (email address).</p>
                            
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="{{ $url }}" class="btn">Verify Frequency</a>
                            </div>
                            
                            <p>If you did not initiate this protocol, disregard this transmission. No further action is required.</p>
                            
                            <div class="divider"></div>
                            
                            <p style="font-size: 12px; margin-bottom: 10px;">MANUAL_OVERRIDE_LINK:</p>
                            <div class="code-block">
                                {{ $url }}
                            </div>
                        </td>
                    </tr>
                    
                    <!-- FOOTER -->
                    <tr>
                        <td class="footer">
                            <p style="margin: 0;">MATAJALAN_OS // GOVERNMENT SURVEILLANCE INITIATIVE</p>
                            <p style="margin: 5px 0 0 0;">SECURE TRANSMISSION // TLS 1.3 ENCRYPTED</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
