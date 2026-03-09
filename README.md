# Block Concurrent Sessions Quiz Access Rule

A Moodle quiz access rule plugin that prevents students from continuing a quiz attempt across multiple sessions or devices, ensuring exam integrity by blocking concurrent connections.

## Requirements

*   **Moodle 5.0 or later**
*   Tested and confirmed working with Moodle 5.0 and 5.1

## Installation

1.  Copy the `oneconnection` folder into your Moodle's `mod/quiz/accessrule/` directory.
2.  Log in to your Moodle site as an administrator and visit **Site administration > Notifications** to complete the installation.
3.  (Optional) Configure default settings at **Site administration > Plugins > Activity modules > Quiz > Quiz access rule: Block concurrent connections**.

## Overview

This plugin prevents students from accessing the same quiz attempt from multiple devices, browsers, or locations simultaneously. When a student starts a quiz attempt, their session information (Moodle session, user-agent, IP address) is securely recorded. Any subsequent attempt to access that same quiz from a different connection will be blocked.

**Use cases:**
- **Digital exams:** Prevents ghostwriting and unauthorized assistance during online exams
- **Proctored assessments:** Ensures students remain on their original device throughout the test
- **High-stakes testing:** Provides an additional security layer for important examinations

## Features

### Core Functionality
- **Session binding:** Locks quiz attempts to the original device/browser session
- **Multi-factor detection:** Validates Moodle session, user-agent, and IP address
- **Secure hashing:** Uses SHA-256 with salt for session fingerprinting

### Management & Monitoring
- **Dedicated management page:** "Allow connection changes" page accessible from the Results tab
- **Bulk actions:** Allow connection changes for multiple students simultaneously
- **Advanced filtering:** Filter by enrolled users, attempt status, and user groups
- **Sorting & pagination:** Sort by any column, use initials bar for quick navigation
- **Comprehensive audit log:** All connection changes are permanently logged with timestamp and authorizing user
- **Export functionality:** Export table data as CSV or Excel for external analysis

### Access Control
- **Fine-grained capabilities:**
  - `quizaccess/oneconnection:allowchange` - Allow teachers to unlock attempts
  - `quizaccess/oneconnection:editenabled` - Control who can enable/disable the rule in quiz settings
- **Configurable defaults:** Set whether the rule is enabled by default for new quizzes

### Localization
- English and German translations included
- Full AMOS translation toolkit support

## Usage

### For Quiz Creators / Teachers

#### Enabling the Rule
1.  Edit your quiz settings
2.  Expand the **"Extra restrictions on attempts"** section
3.  Check the box for **"Block concurrent connections"**
4.  Save the quiz

**Note:** If you don't see this option, you may need the `quizaccess/oneconnection:editenabled` capability.

#### Managing Connection Changes During Exams

When students need to switch devices (e.g., technical issues, computer restart, exam room change):

1.  Navigate to the quiz
2.  Click the **"Results"** tab
3.  Select **"Allow connection changes"** from the dropdown menu

On the management page you can:

**Individual unlock:**
- Click the **"Allow change"** link next to a student's name

**Bulk unlock:**
- Check the boxes next to multiple students
- Click **"Allow change in connection for selected attempts"** at the bottom
- Useful for exam room technical issues affecting multiple students

**Filter and search:**
- Use the **"Attempts from"** filter to show only enrolled users or those with active attempts
- Use the **"Attempts that are"** filter to show specific attempt states (in progress, finished, etc.)
- Click initials to quickly jump to students by last name

**Audit and export:**
- View the **"Change allowed"** column to see who authorized changes and when
- Export the current view as CSV or Excel for record-keeping

**Important notes:**
- Connection changes can only be allowed for attempts in "In progress" state
- Changes can be granted proactively (before a student switches devices)
- All unlock actions are permanently logged for compliance and auditing
- When a change is allowed, the previous connection is blocked (only one active connection per attempt)

### For Students

When the rule is active and you try to continue your quiz from a different device/browser, you'll see a message indicating the attempt is blocked. Contact your exam invigilator or teacher to request a connection change.

## Configuration

### Site-Level Settings

Navigate to **Site administration > Plugins > Activity modules > Quiz > Quiz access rule: Block concurrent connections**

- **Default enabled:** Check this to enable "Block concurrent connections" by default for all new quizzes

### Capabilities

Grant these capabilities to control plugin functionality:

- **`quizaccess/oneconnection:allowchange`**  
  Default: Manager, Teacher, Editing Teacher  
  Allows users to unlock attempts and grant connection changes

- **`quizaccess/oneconnection:editenabled`**  
  Default: Manager, Editing Teacher  
  Allows users to enable/disable the "Block concurrent connections" setting in quiz configuration

## Privacy & Data

This plugin stores:
- **Session fingerprints** (hashed): Moodle session ID, user-agent, IP address
- **Audit log records**: User ID, quiz attempt ID, timestamp, and authorizing user ID for each connection change

Data is retained for the lifetime of the quiz attempt and is deleted when attempts are deleted. See the plugin's privacy provider for GDPR compliance details.

## Acknowledgments

### Original Author
- **Vadim Dvorovenko** (2016)  
  Created the original plugin concept and core functionality

### Current Maintainers
- **lern.link GmbH** (team@lernlink.de)
- **Adrian Sarmas**
- **Vadym Nersesov**

### ETH Zürich Development
Version 5.0+ features were developed based on specifications by **Marco Lehre**, ID Educational IT Services, ETH Zürich. ETH Zürich requested lern.link GmbH to maintain and extend this plugin with enhanced bulk management, comprehensive audit logging, role-based access control, and export functionality for digital examination scenarios.

## Links

*   **Plugin Page:** https://moodle.org/plugins/quizaccess_oneconnection
*   **Latest Code:** https://github.com/lernlink/moodle-quizaccess_oneconnection
*   **Issue Tracker:** https://github.com/lernlink/moodle-quizaccess_oneconnection/issues

## Changelog

### Version 5.1.1 (build 2026030200) - March 2026

**Bug Fixes:**
- Namespaced all CSS selectors to avoid global impact on quiz pages
- Migrated JavaScript to ES6 modules

### Version 5.1.0 (build 2025121600) - December 2025

**Major Features:**
- Added comprehensive "Allow connection changes" management page
- Bulk unlock actions for multiple students simultaneously
- Advanced filtering options (by enrollment status, attempt state, user groups)
- Persistent audit logging with full change history
- CSV and Excel export functionality
- Pagination, sorting, and initials bar for large courses

**Capabilities:**
- New capability `quizaccess/oneconnection:editenabled` to control access to quiz setting
- Enhanced `quizaccess/oneconnection:allowchange` for connection management

**Localization:**
- Full German translation
- AMOS translation toolkit support

**Privacy & Compliance:**
- Complete GDPR privacy provider implementation
- Audit trail for all connection changes
- Backup/Restore API support

**Technical:**
- Minimum requirement: Moodle 5.0
- Security: SHA-256 hashing with salt for session fingerprints
- Database: New tables for audit logging

**ETH Zürich Requirements Fulfilled:**
1. ✅ Bulk action support for connection changes
2. ✅ Visible change history for each attempt
3. ✅ All GUI texts in AMOS translation toolkit
4. ✅ Role-specific permission for editing quiz setting

## License

GNU GPL v3 or later

## Support

For issues, feature requests, or contributions, please use the GitHub issue tracker https://github.com/lernlink/moodle-quizaccess_oneconnection/issues.
