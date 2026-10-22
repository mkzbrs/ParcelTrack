# Security test cases

One row per test. Fill in **Actual result** after you run it. Every test must end as **Pass**:
if it fails, fix the bug and test again. Aim for more than 5 different kinds of input per module.
Save a screenshot for each test in `tests/screenshots/` and write the file name in the last column.

| Column | Meaning |
|---|---|
| Input kind | The type of bad or tricky input |
| Input used | The exact thing you typed or changed |
| Expected | What the system should do |
| Actual | What it really did |

---

## Module 1: Login and Users (owner: Khuzaimi)

| ID | Input kind | Input used | Expected | Actual | Pass/Fail | Screenshot |
|---|---|---|---|---|---|---|
| M1-01 | SQL injection in email | `' OR '1'='1' -- ` | Login refused, simple message | | | |
| M1-02 | Weak or short password | `abc123` at sign-up | Refused: at least 10 characters | | | |
| M1-03 | Repeated wrong passwords | 6 wrong tries in a row | Account locked for a while | | | |
| M1-04 | Script in name field | `<script>alert(1)</script>` at sign-up | Shown as plain text, no popup | | | |
| M1-05 | Seller opens admin page | Seller logged in, open `/admin/dashboard.php` | Access denied (403) | | | |
| M1-06 | Session after logout | Log out, press Back, open a seller page | Sent to login page | | | |
| M1-07 | Choose own role at sign-up | Add `role=admin` to the sign-up form | Role stays seller | | | |
| M1-08 | Blocked account | Admin blocks a rider, rider tries to log in | Login refused | | | |

## Module 2: Parcels and Tracking (owner: Tan Tong Chong)

| ID | Input kind | Input used | Expected | Actual | Pass/Fail | Screenshot |
|---|---|---|---|---|---|---|
| M2-01 | SQL injection in search | `' OR 1=1 --` in My parcels search | No extra rows, no error shown | | | |
| M2-02 | Script in address | `<script>alert(1)</script>` | Shown as plain text, no popup | | | |
| M2-03 | Invalid phone | `abc` | Refused with a simple message | | | |
| M2-04 | Negative weight | `-5` | Refused | | | |
| M2-05 | Change parcel id in the URL | Open another seller's parcel id | Not found / access denied | | | |
| M2-06 | Guessed tracking number | `PT0000000000000001` and nearby numbers | "Could not find a parcel" | | | |
| M2-07 | Many fast lookups | 30 lookups in 10 seconds | Slowed down or blocked | | | |
| M2-08 | Private details on public page | Look up a real tracking number | Only status, date, time shown | | | |

## Module 3: Delivery and Dashboard (owner: Adam)

| ID | Input kind | Input used | Expected | Actual | Pass/Fail | Screenshot |
|---|---|---|---|---|---|---|
| M3-01 | PHP file upload | `shell.php` as the photo | Refused | | | |
| M3-02 | Double extension | `shell.php.jpg` | Refused (real contents checked) | | | |
| M3-03 | Oversized image | Photo bigger than the limit | Refused | | | |
| M3-04 | Fake image | A text file renamed to `.jpg` | Refused | | | |
| M3-05 | Invalid status | `status=Hacked` sent to the update page | Refused | | | |
| M3-06 | Parcel not assigned to rider | Rider A updates Rider B's parcel id | Access denied | | | |
| M3-07 | Dashboard without admin | Rider or seller opens the dashboard | Access denied (403) | | | |
| M3-08 | Change is logged | Rider updates a status | Row in status history + activity log with who and when | | | |
