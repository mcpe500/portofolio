# Ivan Santoso Portfolio

A professional portfolio website with visitor tracking and admin panel built with vanilla PHP and Plates template engine.

## Features

- **Professional Portfolio**: Showcases projects, skills, and experience
- **Visitor Tracking**: Records IP, timestamp, page visits, user agent, and referrer
- **Contact Form**: Visitors can send messages that are stored securely
- **Admin Dashboard**: View analytics and contact messages
- **Responsive Design**: Works on all device sizes
- **Dark Mode**: Toggle between light and dark themes
- **No Framework Dependencies**: Pure PHP implementation

## Folder Structure

```
/public_html
├── index.php                 # Main controller
├── .htaccess                 # Clean URLs + security
├── /admin                    # Admin panel
│   ├── login.php            # Admin login
│   ├── dashboard.php        # Analytics dashboard
│   └── logout.php           # Logout
├── /lib
│   ├── Plates.php           # Template engine
│   ├── Tracker.php          # Visitor tracking
│   └── Auth.php             # Simple auth
├── /templates
│   ├── layout.php
│   ├── home.php
│   ├── projects.php
│   ├── about.php
│   └── contact.php          # Message form
├── /data
│   ├── profile.json
│   ├── projects.json
│   ├── skills.json
│   ├── visitors.json        # Visitor logs
│   └── messages.json        # Contact messages
└── /assets
    ├── style.css
    └── main.js
```

## Setup Instructions

1. **Update Admin Password**: Edit `/admin/login.php` and change `your-secure-password-here` to a strong password

2. **Update Profile Data**: Modify the JSON files in `/data/` with your information:
   - `profile.json` - Your personal information
   - `projects.json` - Your projects
   - `skills.json` - Your skills

3. **Set File Permissions** (on hosting):
   ```bash
   chmod 644 data/*.json
   chmod 755 admin/ lib/ templates/ assets/
   ```

4. **Access Admin Panel**: Visit `yoursite.com/admin` to log in

## Admin Panel Features

- Total visits count
- Unique visitors count
- Today's visits
- Most visited page tracking
- Recent visitor logs (IP, timestamp, page, user agent)
- Contact messages received

## Security Notes

- Data files are blocked from direct access via .htaccess
- Admin panel requires password authentication
- Input is sanitized before storage
- Form submissions are validated

## Deployment

Simply upload all files to your public_html directory on any PHP-enabled hosting. The portfolio requires PHP 7.0 or higher.