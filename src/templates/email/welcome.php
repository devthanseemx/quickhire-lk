<?php
function welcomeTemplate($fullName)
{
    return '
    <div style="font-family: inter, sans-serif; max-width:600px;width:100%;margin:0 auto;background:#fff;border-radius:16px;box-shadow:0 2px 8px rgba(0,0,0,0.08);overflow:hidden;">
        <div style="background:#4f46e5;padding:24px;text-align:center;color:#fff;">
            <h1 style="font-size:2rem;font-weight:bold;letter-spacing:2px;">QUICKHIRE LK</h1>
            <p style="margin-top:8px;font-size:1rem;">Welcome To Sri Lanka\'s Premier Job Portal</p>
        </div>
        <div style="padding:32px;">
            <div style="background-color: #c6ebd9; width: 70px; height: 70px; border-radius: 50%; margin: 0 auto 24px auto; text-align: center; line-height: 70px;">
                <span style="font-size: 3rem; color: #22c55e; vertical-align: middle;">&#10003;</span>
            </div>
            <h2 style="font-size:1.5rem;font-weight:bold;text-align:center;color:#1f2937;margin-bottom:8px;">Account Successfully Created!</h2>
            <p style="text-align:center;color:#4b5563;margin-bottom:32px;font-size:0.95rem;">
                Hi ' . htmlspecialchars($fullName) . ', Welcome To Sri Lanka\'s Premier Job Platform! Your Account Has Been Successfully Registered.
            </p>
            <p style="color:#374151;font-size:0.95rem;margin-bottom:16px;">
                Thank You For Joining QuickHire LK! You Now Have Access To Thousands Of Job Opportunities Across Sri Lanka. Start Exploring Positions That Match Your Skills And Career Goals.
            </p>
            <p style="color:#374151;font-size:0.95rem;margin-bottom:32px;">
                You Can Now Log In To Your Account And Begin Your Job Search Journey With Us.
            </p>
            <div style="margin-bottom:32px;">
                <h3 style="font-size:1.2rem;font-weight:bold;color:#1f2937;margin-bottom:16px;">What\'s Waiting For You:</h3>
                <ul style="font-size:0.95rem;padding-left:0;list-style:none;">
                    <li style="margin-bottom:8px;">
                        <span style="color:#4f46e5;font-weight:bold;margin-right:8px;">&#10003;</span>
                        <span style="color:#374151;">Access To Thousands Of Job Opportunities</span>
                    </li>
                    <li style="margin-bottom:8px;">
                        <span style="color:#4f46e5;font-weight:bold;margin-right:8px;">&#10003;</span>
                        <span style="color:#374151;">Personalized Job Recommendations</span>
                    </li>
                    <li style="margin-bottom:8px;">
                        <span style="color:#4f46e5;font-weight:bold;margin-right:8px;">&#10003;</span>
                        <span style="color:#374151;">Direct Contact With Top Employers</span>
                    </li>
                    <li>
                        <span style="color:#4f46e5;font-weight:bold;margin-right:8px;">&#10003;</span>
                        <span style="color:#374151;">Career Guidance And Resources</span>
                    </li>
                </ul>
            </div>
            <hr style="border:none;border-top:1px solid #e5e7eb;margin:32px 0;">
            <div style="text-align:center;color:#4b5563;">
            <p style="margin-top:32px;font-size:0.8rem;color:#6b7280;">
                You Received This Email Because You Successfully Created An Account On QUICKHIRE LK. Welcome To Our Job
                Platform Community!
            </p>
            <p style="margin-top:12px;font-size:0.8rem;color:#6b7280;">
                &copy; 2025 Quick Hire LK. All Rights Reserved.
            </p>
        </div>
    </div>
    ';
}
