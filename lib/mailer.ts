import nodemailer from 'nodemailer';

export const sendAdminNotification = async (
  adminEmail: string,
  userEmail: string,
  userName: string,
  userCompany: string,
  userPassword: string
) => {
  // Check if SMTP is configured
  if (!process.env.SMTP_USER || !process.env.SMTP_PASS) {
    console.warn("SMTP credentials not configured in .env. Skipping email sending.");
    return false;
  }

  const transporter = nodemailer.createTransport({
    host: process.env.SMTP_HOST || 'smtp.gmail.com',
    port: parseInt(process.env.SMTP_PORT || '587'),
    secure: process.env.SMTP_SECURE === 'true',
    auth: {
      user: process.env.SMTP_USER,
      pass: process.env.SMTP_PASS,
    },
  });

  const mailOptions = {
    from: process.env.SMTP_FROM_EMAIL || '"ITM Books System" <no-reply@itmbooks.com>',
    to: adminEmail,
    subject: `New Registration: ${userCompany}`,
    html: `
      <div style="font-family: Arial, sans-serif; max-width: 600px; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;">
        <h2 style="color: #0b3c5d;">New Registration Alert</h2>
        <p>A new user has registered for an ITM Books trial/subscription.</p>
        <div style="background-color: #f8fafc; padding: 15px; border-radius: 6px; margin: 20px 0;">
          <p><strong>Name:</strong> ${userName}</p>
          <p><strong>Company:</strong> ${userCompany}</p>
          <p><strong>Email (Login ID):</strong> ${userEmail}</p>
          <p><strong>Password:</strong> ${userPassword}</p>
        </div>
        <br>
        <p style="font-size: 12px; color: #64748b;">
          <em>This is an automated notification from the ITM Books registration system. Please ensure the user is followed up with.</em>
        </p>
      </div>
    `,
  };

  try {
    const info = await transporter.sendMail(mailOptions);
    console.log("Admin notification email sent: %s", info.messageId);
    return true;
  } catch (error) {
    console.error("Error sending admin notification email:", error);
    return false;
  }
};

export const sendLoginNotification = async (
  userEmail: string,
  userName?: string,
  loginMethod: string = 'Standard'
) => {
  if (!process.env.SMTP_USER || !process.env.SMTP_PASS) {
    console.warn("[Mailer] SMTP credentials not configured (SMTP_USER/SMTP_PASS). Skipping login email.");
    return false;
  }

  const transporter = nodemailer.createTransport({
    host: process.env.SMTP_HOST || 'smtp.gmail.com',
    port: parseInt(process.env.SMTP_PORT || '587'),
    secure: process.env.SMTP_SECURE === 'true',
    auth: {
      user: process.env.SMTP_USER,
      pass: process.env.SMTP_PASS,
    },
  });

  const now = new Date();
  const formattedTime = now.toUTCString();
  const greetingName = userName && userName.trim() ? userName.trim() : 'Valued Customer';

  const mailOptions = {
    from: process.env.SMTP_FROM_EMAIL || '"ITM Books Security" <no-reply@itmbooks.com>',
    to: userEmail,
    subject: 'Security Alert: Successful Login to Your ITM Books Account',
    html: `
      <!DOCTYPE html>
      <html>
      <head>
        <meta charset="utf-8" />
        <title>Login Successful</title>
      </head>
      <body style="margin: 0; padding: 24px 16px; background-color: #f1f5f9; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; color: #1e293b;">
        <div style="max-width: 580px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08); border: 1px solid #e2e8f0;">
          <div style="background: linear-gradient(135deg, #00539B 0%, #168FCE 100%); padding: 28px 32px; color: #ffffff;">
            <div style="display: inline-block; padding: 6px 14px; background-color: rgba(255, 255, 255, 0.18); border-radius: 20px; font-size: 12px; font-weight: 700; letter-spacing: 0.5px; text-transform: uppercase; margin-bottom: 12px;">
              Security Notification
            </div>
            <h1 style="margin: 0; font-size: 22px; font-weight: 800; letter-spacing: -0.3px;">Successful Sign-In Detected</h1>
            <p style="margin: 6px 0 0 0; font-size: 14px; color: rgba(255, 255, 255, 0.9);">ITM Books Cloud Accounting</p>
          </div>
          <div style="padding: 32px;">
            <p style="font-size: 15px; margin: 0 0 16px 0; color: #334155; line-height: 1.6;">
              Hello <strong>${greetingName}</strong>,
            </p>
            <p style="font-size: 14px; margin: 0 0 24px 0; color: #475569; line-height: 1.6;">
              Your ITM Books account was successfully accessed. Here are the sign-in details:
            </p>
            <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 18px 20px; margin-bottom: 24px;">
              <table style="width: 100%; border-collapse: collapse; font-size: 13.5px;">
                <tr>
                  <td style="padding: 6px 0; color: #64748b; width: 140px;">Account Email:</td>
                  <td style="padding: 6px 0; color: #0f172a; font-weight: 600;">${userEmail}</td>
                </tr>
                <tr>
                  <td style="padding: 6px 0; color: #64748b;">Sign-in Method:</td>
                  <td style="padding: 6px 0; color: #0f172a; font-weight: 600;">${loginMethod}</td>
                </tr>
                <tr>
                  <td style="padding: 6px 0; color: #64748b;">Timestamp (UTC):</td>
                  <td style="padding: 6px 0; color: #0f172a; font-weight: 600;">${formattedTime}</td>
                </tr>
                <tr>
                  <td style="padding: 6px 0; color: #64748b;">Status:</td>
                  <td style="padding: 6px 0; color: #16a34a; font-weight: 700;">✓ Authorized / Successful</td>
                </tr>
              </table>
            </div>
            <div style="background-color: #eff6ff; border-left: 4px solid #168FCE; padding: 14px 16px; border-radius: 4px; margin-bottom: 26px;">
              <p style="margin: 0; font-size: 13px; color: #1e40af; line-height: 1.5;">
                <strong>Did you just sign in?</strong> If this was you, you can safely disregard this message. No further action is required.
              </p>
            </div>
            <p style="font-size: 13px; color: #dc2626; margin: 0 0 20px 0; line-height: 1.5;">
              <strong>Didn't sign in?</strong> If you did not perform this login, please change your password immediately or contact our support team.
            </p>
            <div style="text-align: center; margin-top: 28px; padding-top: 20px; border-top: 1px solid #e2e8f0;">
              <a href="http://localhost:3000/itmbooks.com/dashboard" 
                 style="display: inline-block; background-color: #168FCE; color: #ffffff; text-decoration: none; font-weight: 600; font-size: 14px; padding: 11px 24px; border-radius: 6px;">
                Open ITM Books Dashboard
              </a>
            </div>
          </div>
          <div style="background-color: #f8fafc; padding: 18px 32px; border-top: 1px solid #e2e8f0; text-align: center; font-size: 12px; color: #94a3b8;">
            © ${now.getFullYear()} ITM Books. All rights reserved. Automated security notification.
          </div>
        </div>
      </body>
      </html>
    `,
  };

  try {
    const info = await transporter.sendMail(mailOptions);
    console.log(`[Mailer] Login success notification sent to ${userEmail}: ${info.messageId}`);
    return true;
  } catch (error) {
    console.error("[Mailer] Failed to send login notification email:", error);
    return false;
  }
};
