<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f3f5; margin: 0; padding: 0; }
        .email-container { max-width: 600px; margin: 20px auto; background: #ffffff; border-top: 4px solid #0ab39c; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .header { background-color: #f7f9fa; padding: 20px; text-align: center; border-bottom: 1px solid #e9ecef; }
        .content { padding: 30px; color: #495057; line-height: 1.6; }
        .footer { background-color: #f7f9fa; padding: 15px; text-align: center; font-size: 12px; color: #878a99; }
        .btn { display: inline-block; padding: 10px 20px; background-color: #0ab39c; color: #ffffff !important; text-decoration: none; border-radius: 4px; font-weight: 500; margin-top: 15px; }
        .btn:hover { background-color: #099885; }
        .credentials-box { background-color: #f0f2f5; padding: 15px; border-radius: 4px; margin: 20px 0; border-left: 3px solid #0ab39c; }
        h2 { color: #343a40; margin-top: 0; }
        .label { font-weight: 600; color: #343a40; }
        .value { color: #0ab39c; font-family: 'Courier New', monospace; font-weight: bold; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2 style="margin:0;">SUBEB Portal</h2>
        </div>
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            <p>Your account for the <strong>SUBEB Yobe Portal</strong> has been successfully created. You have been assigned the role of <strong>{{ ucfirst($user->role) }}</strong>.</p>
            
            <p>Here are your login credentials:</p>
            
            <div class="credentials-box">
                <p style="margin: 5px 0;"><span class="label">Email:</span> {{ $user->email }}</p>
                <p style="margin: 5px 0;"><span class="label">Password:</span> <span class="value">{{ $rawPassword }}</span></p>
            </div>

            <p>Please log in immediately and change your password to something secure.</p>

            <div style="text-align: center;">
                <a href="{{ $loginUrl }}" class="btn">Login to Portal</a>
            </div>

            <p style="margin-top: 20px; font-size: 0.9em;">
                If the button above doesn't work, copy and paste this link into your browser:<br>
                <a href="{{ $loginUrl }}" style="color: #0ab39c;">{{ $loginUrl }}</a>
            </p>

            <p>For assistance, please contact the ICT Unit.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SUBEB Yobe State. All rights reserved.
        </div>
    </div>
</body>
</html>
