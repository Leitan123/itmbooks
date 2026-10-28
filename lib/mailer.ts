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
