# Testing Documentation - SMEStack

## Test Environment
- **Browser:** Chrome, Firefox, Safari
- **Devices:** Desktop, Mobile
- **PHP:** 8.0
- **MySQL:** 5.7

## Test Cases

### TC-01: User Registration - SME
- **Steps:** Go to register, select SME, fill form
- **Expected:** Registration successful
- **Status:** ✅ PASS

### TC-02: User Registration - Student
- **Steps:** Go to register, select Student, fill form
- **Expected:** Registration successful
- **Status:** ✅ PASS

### TC-03: User Registration - Admin
- **Steps:** Go to register, select Admin, fill form
- **Expected:** Registration successful
- **Status:** ✅ PASS

### TC-04: Login - Valid Credentials
- **Steps:** Enter correct email/password
- **Expected:** Redirect to dashboard
- **Status:** ✅ PASS

### TC-05: Login - Invalid Credentials
- **Steps:** Enter wrong password
- **Expected:** Error message
- **Status:** ✅ PASS

### TC-06: SME Post Project Request
- **Steps:** Login as SME, fill project form
- **Expected:** Project created
- **Status:** ✅ PASS

### TC-07: Student Browse Projects
- **Steps:** Login as student, go to browse
- **Expected:** List of open projects
- **Status:** ✅ PASS

### TC-08: Student Apply to Project
- **Steps:** Click apply, write message
- **Expected:** Application submitted
- **Status:** ✅ PASS

### TC-09: SME View Applications
- **Steps:** Login as SME, view applications
- **Expected:** List of applicants
- **Status:** ✅ PASS

### TC-10: SME Accept Application
- **Steps:** Click accept on application
- **Expected:** Status changes to accepted
- **Status:** ✅ PASS

### TC-11: Admin Assign Team
- **Steps:** Login as admin, assign team
- **Expected:** Project created
- **Status:** ✅ PASS

### TC-12: Progress Bar
- **Steps:** View active project
- **Expected:** Progress shows correct %
- **Status:** ✅ PASS

### TC-13: Logout
- **Steps:** Click logout
- **Expected:** Redirect to home
- **Status:** ✅ PASS

### TC-14: Mobile Responsive
- **Steps:** Open on mobile
- **Expected:** Layout adjusts
- **Status:** ✅ PASS

### TC-15: Activity Log
- **Steps:** Login as admin, view logs
- **Expected:** All actions recorded
- **Status:** ✅ PASS