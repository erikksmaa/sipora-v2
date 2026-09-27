SIPORA v2 — Product Requirements Document
Product Name: SIPORA v2
Full Name: Sistem Informasi Program Olahraga dan Kepemudaan
Organization: Dindikpora Kabupaten Pemalang
Product Type: Youth Ecosystem Platform
Primary Platform: Web Application
Target MVP: 28 Oktober 2026
Project Status: Pre-Implementation / Architecture Locked
Legacy Reference: mandorin/
New Application Directory: sipora-v2/
1. Purpose of This Document
Dokumen ini merupakan source of truth utama untuk pengembangan SIPORA v2.
Seluruh implementasi harus mengikuti keputusan produk, business rule, arsitektur, scope, dan prinsip yang ditentukan di dokumen ini.
AI coding agent tidak boleh mengubah konsep inti hanya karena struktur di project lama berbeda.
Apabila terdapat konflik antara:
1. project legacy mandorin,
2. asumsi framework,
3. kebiasaan implementasi umum,
4. dan PRD ini,
maka PRD ini memiliki prioritas tertinggi.
Jika terdapat hal yang belum dijelaskan dalam PRD, jangan membuat keputusan arsitektur besar secara sepihak. Laporkan asumsi atau kebutuhan keputusan terlebih dahulu.
2. Project Context
SIPORA sebelumnya dikembangkan sebagai sistem administrasi program kepemudaan untuk Dindikpora.
Versi lama terutama berfokus pada:
- organisasi kepemudaan,
- proposal program,
- logbook,
- absensi,
- E-LPJ,
- verifikasi Dindikpora,
- monitoring program.
Project lama berada pada folder:
mandorin/

Folder tersebut hanya digunakan sebagai:
- referensi fitur lama,
- referensi business logic yang masih relevan,
- referensi alur proposal/logbook/E-LPJ,
- referensi struktur data,
- referensi UI tertentu jika diperlukan.
Mandorin bukan codebase yang harus diteruskan.
SIPORA v2 merupakan fresh architecture dan dikembangkan di:
sipora-v2/

Jangan melakukan copy keseluruhan source code Mandorin ke SIPORA v2.
Reuse hanya boleh dilakukan setelah logic tersebut dibandingkan dengan PRD ini dan masih dianggap relevan.
3. Product Transformation
SIPORA v1 berorientasi pada:
administrasi organisasi dan program.

SIPORA v2 berorientasi pada:
ekosistem kepemudaan.

Definisi utama SIPORA v2:
SIPORA adalah platform ekosistem kepemudaan Kabupaten Pemalang yang menghubungkan pemuda, komunitas/organisasi, kegiatan, peluang pengembangan diri, dan pemerintah dalam satu sistem digital terintegrasi.

Product category:
Youth Ecosystem Platform

SIPORA v2 bukan hanya sistem administrasi pemerintah.
Sistem harus memberikan value langsung kepada pemuda.
4. Product Vision
SIPORA ditargetkan menjadi pusat digital aktivitas dan pengembangan pemuda Kabupaten Pemalang.
Sistem menghubungkan:
Community / Organization
        ↓
creates Activity
        ↓
Youth participates
        ↓
Participation becomes data
        ↓
Youth builds experience
        ↓
Government receives insight
        ↓
Better youth programs

SIPORA harus menciptakan siklus:
Program
→ Participation
→ Data
→ Evaluation
→ Better Program

5. Product Principles
Seluruh desain dan implementasi harus mengikuti prinsip:
Youth-centered
Community-driven
Government-supported
Data-informed

Pengalaman sistem harus berbeda berdasarkan kebutuhan aktor:
PUBLIC
Discover

YOUTH
Participate + Grow

COMMUNITY MANAGER
Organize + Operate

VERIFIER
Review + Validate

ADMIN
Manage + Analyze

6. Main Value Proposition
Youth
Temukan peluang. Ikuti kegiatan. Bangun pengalaman.

Value:
- discovery,
- participation,
- personal development,
- verified experience,
- Youth Portfolio.
Community / Organization
Bangun komunitas. Kelola kegiatan. Jangkau lebih banyak pemuda.

Value:
- visibility,
- member management,
- participant management,
- activity management,
- administration.
Government / Dindikpora
Kelola program. Pantau partisipasi. Pahami potensi pemuda.

Value:
- verification,
- monitoring,
- analytics,
- evaluation,
- program planning.
Public
Value:
- information,
- transparency,
- discovery.
7. Product Boundaries
SIPORA v2 bukan:
- full social media platform,
- job portal penuh,
- LMS,
- chat platform,
- marketplace,
- recruitment platform,
- full professional network seperti LinkedIn.
SIPORA dapat mempunyai beberapa fitur yang mirip fungsi tersebut, tetapi tidak boleh berkembang menjadi platform yang terlalu luas untuk MVP.
8. Target Users
Core youth segment mengikuti definisi pemuda Indonesia:
16–30 tahun

Tetapi sistem tetap dapat mempunyai user di luar rentang tersebut untuk peran seperti:
- mentor,
- pembina,
- government staff,
- narasumber.
Primary users:
- Youth
- Community / Organization Manager
Supporting users:
- Organization Member
- Verifier Dindikpora
- Administrator
- Guest/Public
Future users:
- sponsor,
- perusahaan,
- universitas,
- lembaga pelatihan,
- external partners.
9. Identity Principle
Prinsip penting:
Satu orang, satu identitas pemuda, banyak kemungkinan peran.

Contoh:
Erik

System Role:
Youth

Organization A:
Manager

Organization B:
Member

Activity X:
Organizer

Activity Y:
Participant

Jangan membuat akun berbeda berdasarkan organisasi.
User adalah individu.
Organization adalah entity.
10. Authorization Model
Authorization memiliki tiga layer.
Spatie Laravel Permission
↓
System-level role & permission

Laravel Policies
↓
Resource-level authorization

Organization Membership
↓
Contextual organization role

Global roles:
youth
verifier
admin

Jangan membuat global roles:
manager
leader
member

Manager/Leader/Member adalah role di dalam relasi organisasi.
Contoh:
organization_memberships

user_id
organization_id
role
status

Possible organization roles:
leader
manager
member

11. Authentication
SIPORA menggunakan hybrid authentication.
Method A
Email + password.
Flow:
Register
↓
reCAPTCHA v3
↓
Email Verification
↓
Account Active

Method B
Google OAuth.
Flow:
Continue with Google
↓
Google OAuth
↓
Create or Link SIPORA User
↓
Account Active

Technology:
Laravel Socialite

Google OAuth bukan Identity Verification.
12. Account Verification vs Identity Verification
Harus dibedakan secara jelas.
Email Verification
Membuktikan:
user memiliki alamat email.

Google OAuth
Membantu:
authentication dan verified provider email.

SIPORA Identity Verification
Membuktikan:
identitas individual.

Possible documents:
Usia >= 17:
- KTP / NIK.
Usia 16:
- KIA,
- atau kartu pelajar + data identitas sesuai kebutuhan.
Identity verification dilakukan oleh Admin.
Google account tidak otomatis menjadikan user:
Verified Youth.

13. Verification Levels
Konsep verification:
Basic Account
↓
Email Verified
↓
Profile Completed
↓
Identity Verified
↓
Program-specific Eligibility

Identity Verification tidak wajib untuk menggunakan seluruh platform.
User belum verified tetap dapat:
- melihat Activity,
- bergabung Community,
- membuat Profile,
- bookmark,
- menjelajahi Opportunity.
Activity tertentu dapat menentukan:
Requires Verified Identity

14. Main Product Ecosystem
SIPORA memiliki lima domain utama.
Youth Hub
Community Hub
Activity Hub
Opportunity Hub
Government Hub

15. Youth Hub
Youth Hub mencakup:
- akun,
- profile,
- bio,
- foto,
- domisili,
- interests,
- skills,
- education,
- organization experience,
- achievements,
- Community membership,
- Activity participation,
- Activity Passport,
- certificates,
- bookmarks,
- Youth Portfolio,
- notifications.
16. Youth Portfolio
Youth Portfolio adalah presentation layer.
Jangan membuat satu tabel besar:
youth_portfolios

Portfolio dihasilkan dari domain data.
Sources:
user_profiles
user_interests
user_skills
user_educations
organization_experiences
organization_memberships
activity_participations
certificates
achievements

Portfolio harus membedakan:
Self-reported

dengan:
SIPORA Verified

Contoh verified information:
- Verified Membership
- Verified Participation
- Verified Certificate
17. Portfolio Privacy
Default:
Public Portfolio

Tetapi user memiliki visibility control per section.
Sensitive data tidak pernah masuk public portfolio.
Tidak boleh public:
- NIK,
- KTP/KIA,
- identity document,
- full date of birth,
- exact address,
- email,
- phone.
18. Activity Passport
Activity Passport merupakan riwayat Activity terverifikasi.
Tidak perlu tabel duplikasi khusus jika data dapat dihitung dari participation.
Source:
activity_participations

Entry muncul jika:
registration_status = accepted

AND

completion_status = completed

Activity Passport dapat menampilkan role:
- Participant
- Volunteer
- Committee
- Organizer
- Speaker
19. Community Hub
Community merupakan organisasi atau komunitas kepemudaan.
Community data:
- name,
- slug,
- category,
- description,
- logo,
- address,
- location,
- contact,
- verification,
- operational status,
- members,
- managers,
- activities,
- programs,
- gallery,
- statistics.
20. Community Creation
User tidak langsung membuat Community aktif.
Flow:
Youth
↓
Create Community Application
↓
Draft
↓
Submit
↓
Verifier Review
↓
Approved / Revision / Rejected

Jika Approved:
Community becomes active

Applicant becomes first Leader / Manager

Community verification dilakukan oleh:
Verifier

bukan Admin.
21. Community Status
Gunakan dua status dimension.
Review:
draft
pending_review
revision
rejected
approved

Operational:
active
suspended
archived

22. Community Membership
Youth dapat bergabung melalui:
Join Request

MVP tidak menggunakan invitation.
Flow:
Youth
↓
Join Community
↓
Pending
↓
Manager Review
↓
Accepted / Rejected

Youth dapat menjadi member banyak Community.
Mengikuti Activity tidak otomatis menjadi Community member.
Community membership bukan syarat Activity kecuali Activity memang menentukan:
member-only

23. Community Management
Manager dapat:
- update organization profile,
- manage members,
- review join requests,
- assign organization role,
- create Activity,
- manage participants,
- attendance,
- create Program,
- proposal,
- logbook,
- E-LPJ.
Satu Community dapat mempunyai beberapa manager.
24. Activity Hub
Activity adalah event/aktivitas nyata yang diikuti Youth.
Contoh:
- workshop,
- seminar,
- olahraga,
- volunteer,
- pelatihan,
- kompetisi,
- meetup.
Activity bisa:
Standalone

atau:
Part of Program

25. Program vs Activity
Harus dibedakan.
Program
Administrative umbrella.
Contoh:
Program Pemuda Digital 2026

Activity
Kegiatan nyata.
Contoh:
Workshop Fundamental Web Development
Workshop Laravel
Seminar Karier Digital

Relation:
Program 1 : N Activity

Activity memiliki:
program_id nullable

Activity dapat berdiri sendiri.
26. Activity Creation
Hanya Organization Manager yang dapat membuat public Activity.
Flow:
Manager
↓
Create Draft
↓
Configure Activity
↓
Submit for Review
↓
Verifier
↓
Approved / Revision / Rejected
↓
Manager Publish

Activity harus melalui Verifier sebelum dapat dipublikasikan.
27. Activity Status
Activity menggunakan tiga status dimension.
Review:
draft
pending_review
revision
rejected
approved

Publication:
unpublished
published
archived

Execution:
scheduled
ongoing
completed
cancelled

Backend harus menjaga valid combinations.
Contoh tidak valid:
rejected + published

28. Activity Fields
Activity minimal memiliki:
- title,
- slug,
- category,
- organization,
- optional program,
- description,
- poster,
- location,
- registration deadline,
- quota,
- registration mode,
- eligibility,
- certificate availability,
- status,
- sessions.
29. Activity Registration Modes
Mode:
open

atau:
approval_required

Open:
Register
→ accepted

Approval required:
Register
→ pending
→ Manager Accept / Reject

30. Participation State
Jangan menggunakan satu status besar.
Registration status:
pending
accepted
rejected
cancelled

Completion status:
pending
completed
no_show

Contoh:
accepted + completed

atau:
accepted + no_show

31. Activity Sessions
Activity dapat mempunyai satu atau lebih Session.
Simple Activity:
Activity
└── Session 1

Multi-session Activity:
Bootcamp
├── Session 1
├── Session 2
└── Session 3

Contoh struktur:
Program Pemuda Digital
│
├── Workshop Web
│   └── Session 1
│
├── Workshop Laravel
│   └── Session 1
│
└── Bootcamp UI/UX
    ├── Session 1
    ├── Session 2
    └── Session 3

32. Attendance
Attendance dicatat:
per session

bukan per Program.
Core relation:
Activity
↓
Activity Session
↓
Activity Participation
↓
Attendance

Recommended uniqueness:
UNIQUE(activity_session_id, participation_id)

Attendance statuses:
present
absent

Attendance adalah evidence.
Activity completion tidak otomatis dihitung hanya berdasarkan attendance percentage pada MVP.
Organizer menentukan completion sesuai business rule.
33. Certificate
Certificate hanya diberikan jika:
Participation Completed
AND
Activity Certificate Enabled

Certificate harus memiliki:
- unique identifier,
- verification code,
- activity reference,
- participant,
- issued date.
QR verification dapat ditambahkan.
Certificate status harus dapat diverifikasi publik.
34. Opportunity Hub
Opportunity merupakan peluang eksternal.
Examples:
- internship,
- scholarship,
- competition,
- volunteering,
- training.
MVP:
Opportunity dibuat/kurasi oleh Admin.
Application dilakukan melalui:
External Link

SIPORA bukan internal recruitment system.
Community tidak membuat Opportunity pada MVP.
35. Government Hub
Government Hub mencakup:
- Community Verification,
- Activity Verification,
- Program Verification,
- Proposal Verification,
- Logbook Monitoring,
- E-LPJ Review,
- Final Program Evaluation,
- Identity Verification,
- Analytics,
- Master Data,
- Moderation,
- Audit.
36. Verifier Responsibilities
Verifier menangani:
- Community application,
- Activity review,
- Program/Proposal,
- Logbook,
- E-LPJ,
- Final Program Evaluation.
Verifier tidak menangani:
Personal Identity Verification

37. Admin Responsibilities
Admin menangani:
- user management,
- personal identity verification,
- Opportunity,
- master data,
- government user,
- moderation,
- analytics,
- audit log,
- system settings.
38. Program
Program merupakan administrative umbrella.
Program dapat dibuat sebagai Draft tanpa Activity.
Tetapi Program tidak dapat submit jika:
activities_count < 1

Untuk MVP:
Semua Activity yang terhubung ke Program dianggap official Program Activity.
Tidak ada optional Activity.
39. Program Workflow
Baseline:
Draft
↓
Add Activity Plan
↓
Submit Proposal
↓
Verifier Review
↓
Approved
↓
Program Running
↓
Activities
↓
Logbook
↓
E-LPJ
↓
Final Evaluation
↓
Completed

40. Proposal
Proposal terhubung ke Program.
Proposal review:
pending_review
approved
revision
rejected

Revision harus memiliki reason.
Reject harus memiliki reason.
41. Logbook
Logbook mencatat perkembangan program.
Logbook dapat berisi:
- date,
- progress,
- activity description,
- obstacle,
- solution,
- documentation.
Progress bukan nilai bebas tanpa rule.
Business logic harus ditangani Laravel service.
42. E-LPJ
E-LPJ merupakan laporan akhir program.
Termasuk:
- financial report,
- financial items,
- realization,
- receipts,
- evidence,
- summary.
Verifier dapat:
Approve
Request Revision
Reject

43. Program Completion
Final completion harus melalui business rule.
Baseline compatibility dengan SIPORA sebelumnya:
Draft             0%
Proposal Submit   10%
Proposal Approved 20%
Running           30%
Logbook           30–94%
E-LPJ             95%
Final Complete    100%

Program hanya dapat 100% ketika requirement final terpenuhi.
Implementation detail harus ditempatkan dalam:
ProgramProgressService

bukan database trigger.
44. Public Experience
Public dapat mengakses:
- homepage,
- Activity listing,
- Activity detail,
- Community listing,
- Community detail,
- Opportunity,
- Program,
- public Youth Portfolio,
- certificate verification,
- gallery,
- information.
Public tidak dapat:
- register Activity,
- join Community,
- bookmark,
- manage profile
tanpa login.
45. Youth Experience
Youth area:
/youth/home
/youth/explore
/youth/activities
/youth/communities
/youth/opportunities
/youth/portfolio
/youth/profile
/youth/bookmarks
/youth/identity-verification
/youth/notifications
/youth/settings

Youth dashboard bukan admin dashboard.
Tidak menggunakan permanent admin sidebar.
46. Manager Workspace
Base route:
/manage/{organization}

Example:
/manage/komunitas-programmer-pemalang

Manager pages:
dashboard

community/profile
members
join-requests

activities
activities/create
activities/{activity}
participants
sessions
attendance

programs
proposal
logbook
financial-report

gallery
notifications
settings

Organization context harus berasal dari route.
47. Verifier Workspace
Base:
/verifier

Modules:
- dashboard,
- Community,
- Activity,
- Proposal,
- Program,
- Logbook,
- Financial Report,
- Final Evaluation,
- history,
- notification.
Verifier dashboard harus:
queue-oriented

bukan chart-heavy.
48. Admin Workspace
Base:
/admin

Modules:
- Youth Management,
- Identity Verification,
- Community Management,
- Opportunity,
- Master Data,
- Analytics,
- Moderation,
- Government Users,
- Audit Log,
- Settings.
Admin dashboard:
data-oriented

49. Notifications
MVP menggunakan:
Laravel Database Notifications

Notify untuk event penting:
- Activity Registration Accepted,
- Activity Registration Rejected,
- Community Join Accepted,
- Community Join Rejected,
- Activity Revision Requested,
- Activity Approved,
- Proposal Revision,
- E-LPJ Result,
- Identity Verification Result.
Jangan membuat notification untuk setiap aksi kecil.
50. Search and Discovery
Search objects:
- Activity,
- Community,
- Opportunity,
- Program.
Recommendation MVP tidak menggunakan AI.
Rule-based recommendation berdasarkan:
interests
category
activity history

Jika Laravel Scout dibutuhkan nanti, dapat digunakan.
51. Analytics
Important metrics:
Youth
- registered youth,
- verified youth,
- active youth.
Example definition:
Active Youth = participated in relevant activity within last 90 days

Participation
Bedakan:
Total Participation

dengan:
Unique Participants

Analytics categories
- interests,
- age,
- area,
- Activity category,
- Community,
- attendance,
- completion,
- Program.
Analytics bukan ranking manusia.
52. Privacy & Security
SIPORA akan menyimpan data sensitif.
Principle:
Need-to-see, not nice-to-see.

Sensitive data tidak ditampilkan di list/table tanpa kebutuhan.
Identity documents harus:
private storage

dan tidak boleh tersimpan di public disk.
Access harus melalui:
authorization

53. Security Stack
Security baseline:
Laravel Session Authentication
Email Verification
Password Hashing
CSRF Protection
Rate Limiting
Google reCAPTCHA v3
Google OAuth
Spatie Permission
Laravel Policies
Private File Storage
Audit Logging
Server-side Validation

54. reCAPTCHA v3
Gunakan pada:
- manual registration,
- login,
- forgot password.
Jangan digunakan pada internal authenticated workflow.
Verification backend harus memeriksa:
- success,
- score,
- expected action,
- hostname jika applicable.
Default threshold:
0.5

configurable.
55. System Permission
Spatie permission minimal.
Admin:
manage users
verify identities
manage opportunities
manage master data
manage government users
moderate users
moderate communities
view analytics
view audit logs

Verifier:
review communities
review activities
review proposals
review logbooks
review financial reports
complete programs

Jangan membuat terlalu banyak permission Youth.
56. Audit
Gunakan:
spatie/laravel-activitylog

Audit event penting:
- identity verification,
- role changes,
- moderation,
- approval,
- rejection,
- sensitive administrative action.
Jangan log setiap page view.
57. Media
Recommended:
spatie/laravel-medialibrary

Use cases:
- profile photo,
- Community logo,
- Activity poster,
- Activity gallery,
- achievement attachment,
- certificate assets.
Sensitive identity document tetap membutuhkan private storage policy yang ketat.
58. Technology Stack
Backend:
Laravel 13
PHP 8.4.15

Database:
MySQL 8.0.30
InnoDB
utf8mb4

Frontend:
Blade
Tailwind CSS
Alpine.js
Vite

Authentication:
Laravel Session Auth
Laravel Socialite
Google OAuth

Authorization:
spatie/laravel-permission
Laravel Policies

Security:
Google reCAPTCHA v3
Rate Limiting
CSRF

Audit:
spatie/laravel-activitylog

Potential later packages:
spatie/laravel-medialibrary
Laravel Scout
DOMPDF
Laravel Excel
QR Code library
Laravel Telescope
Laravel Pulse
Redis
Laravel Horizon
Larastan/PHPStan

Jangan install semua package dari awal tanpa kebutuhan.
59. Frontend Constraints
Jangan gunakan:
React
Vue
Inertia
Livewire
Flux

kecuali keputusan produk kemudian berubah.
Target:
Server rendered Blade
+
Tailwind
+
Alpine

Javascript hanya untuk:
- modal,
- dropdown,
- tabs,
- dynamic session form,
- upload preview,
- confirmation,
- filter UX,
- mobile navigation.
60. UI Design Direction
Google Stitch prototype telah dibuat dan menjadi visual reference.
General design:
Modern
Youth-centered
Government trustworthy
Clean
Professional
Active
Accessible

Brand colors baseline:
Primary Navy     #243378
Deep Navy        #172554
Interactive Blue #3346A8
Orange           #F97316
Success Green    #16A34A
Warning Amber    #D97706
Danger Red       #DC2626

Background       #F8FAFC
Surface          #FFFFFF
Text             #0F172A
Secondary        #475569
Muted            #64748B
Border           #E2E8F0

Typography:
Plus Jakarta Sans → headings
Inter → body/data

61. UI Character by Workspace
Public:
visual + discovery-oriented

Youth:
personal + activity-oriented

Manager:
operational + task-oriented

Verifier:
queue-oriented

Admin:
analytics + system-oriented

62. Database Principles
Database responsibility:
- data integrity,
- PK,
- FK,
- UNIQUE,
- NOT NULL,
- CHECK,
- indexes,
- referential actions.
Laravel responsibility:
- approvals,
- workflow,
- status transition,
- progress calculation,
- eligibility,
- notifications,
- certificate logic,
- Activity Passport,
- analytics logic,
- audit.
Do not use database triggers for business workflow.
Do not use SQL views as core application abstraction.
63. Identifier Strategy
Target:
UUIDv7

generated application-side.
Storage:
BINARY(16)

Primary domain entities should not rely on exposed sequential IDs.
Spatie:
roles.id       BIGINT
permissions.id BIGINT

may remain integer.
Spatie model pivot must remain compatible with User UUID storage.
If there is a technical conflict, report it before changing identifier architecture.
64. Time Handling
Database timestamps:
DATETIME(6)

Store canonical time consistently.
Application display timezone:
Asia/Jakarta

Avoid mixing timezone assumptions in business logic.
65. Soft Delete / Archive
Historical business records should not be carelessly hard deleted.
Use:
SoftDeletes

or archive status where appropriate.
Examples:
- Organization,
- Activity,
- Program,
- Opportunity.
Master data in use should be:
inactive / archived

rather than hard deleted.
66. Backend Architecture
Avoid fat controllers.
Recommended:
app/
├── Models
├── Actions
├── Services
├── Policies
├── Enums
├── Notifications
├── Support
└── Http
    ├── Controllers
    ├── Requests
    └── Middleware

Controllers:
- validate/authorize,
- call Action/Service,
- return response.
67. Actions
Possible examples:
SubmitCommunityForReview
ApproveCommunity
RequestCommunityRevision

SubmitActivityForReview
ApproveActivity
RejectActivity

RegisterForActivity
AcceptParticipant
RejectParticipant
CompleteParticipation

SubmitProgram
ApproveProposal
SubmitLogbook
SubmitFinancialReport
CompleteProgram

68. Services
Examples:
ActivityEligibilityService
ProgramProgressService
PortfolioService
CertificateService
RecaptchaService
AnalyticsService

69. Status Enums
Status values should use PHP Enums where appropriate.
Do not scatter string literals across application.
Examples:
ReviewStatus
PublicationStatus
ExecutionStatus
RegistrationStatus
CompletionStatus
AttendanceStatus
VerificationStatus
OperationalStatus

70. Routing Structure
Recommended:
routes/
├── web.php
├── public.php
├── youth.php
├── manager.php
├── verifier.php
└── admin.php

Do not create one giant web.php.
71. Testing Requirements
Feature tests are priority.
Critical test domains:
Authentication
Authorization
Youth Profile
Identity Verification

Community Application
Community Membership

Activity Creation
Activity Verification
Activity Registration
Participant Approval
Attendance
Completion

Program
Proposal
Logbook
E-LPJ

Portfolio Visibility
Certificate Verification

Tests must verify cross-user access restrictions.
Example:
User A must not manage Organization B

even if route URL is known.
72. Performance Principles
Avoid:
- N+1 queries,
- loading full collections unnecessarily,
- repeated aggregate queries,
- huge table queries without pagination.
Use:
- eager loading,
- indexes,
- pagination,
- caching only where justified.
Do not prematurely introduce Redis architecture.
73. Accessibility
Target WCAG AA where practical.
Required:
- keyboard usable controls,
- focus indicator,
- text + color status,
- accessible forms,
- alt image,
- touch targets ~44px,
- adequate contrast.
74. MVP Scope
Must Have:
- Authentication
- Google OAuth
- Youth Profile
- Interest
- Skills
- Community
- Membership
- Community Verification
- Activity
- Sessions
- Registration
- Participant Management
- Attendance
- Activity Passport
- Youth Portfolio
- Program
- Proposal
- Logbook
- E-LPJ
- Verification
- Opportunity
- Notifications
- Search/filter
- Admin
- basic Analytics
75. Should Have
If timeline allows:
- Certificate Generator
- QR certificate verification
- QR attendance
- Bookmark
- Activity Gallery
- recommendation by interest
- portfolio sharing
- CSV/XLSX export
- simple badges.
76. Future Scope
Not MVP:
- messaging,
- forum,
- social feed,
- followers,
- AI recommendation engine,
- AI career advisor,
- mentoring,
- skill endorsement,
- gamification ranking,
- marketplace,
- internal recruitment,
- LMS,
- native app,
- SSO government,
- partner portal,
- sponsorship marketplace.
77. Primary Golden Path — Youth
Landing
↓
Register / Google Login
↓
Email Verified
↓
Basic Profile
↓
Choose Interests
↓
Youth Home
↓
Discover Activity
↓
Activity Detail
↓
Register
↓
Accepted
↓
Attend Sessions
↓
Organizer Completes Participation
↓
Activity Passport
↓
Certificate
↓
Youth Portfolio

78. Primary Golden Path — Community
Youth
↓
Submit Community
↓
Verifier Approves
↓
Community Active
↓
Create Activity
↓
Submit Review
↓
Approved
↓
Publish
↓
Participants
↓
Attendance
↓
Complete

79. Primary Golden Path — Program
Create Program
↓
Add Activity
↓
Submit Proposal
↓
Verifier Review
↓
Approved
↓
Run Activities
↓
Logbooks
↓
E-LPJ
↓
Final Evaluation
↓
Completed

80. Primary Golden Path — Identity Verification
Youth
↓
Upload Verification Document
↓
Admin Queue
↓
Review
↓
Verified / Revision / Rejected
↓
Youth receives Notification

81. Implementation Order
Implementation harus dilakukan bertahap.
Recommended phases:
Phase 1
Project Initialization & Foundation

Phase 2
Youth Account, Profile & Identity Foundation

Phase 3
Interests, Skills & Profile Enrichment

Phase 4
Community

Phase 5
Community Membership

Phase 6
Activity

Phase 7
Activity Registration & Participation

Phase 8
Sessions & Attendance

Phase 9
Activity Completion & Activity Passport

Phase 10
Youth Portfolio

Phase 11
Program

Phase 12
Proposal & Verification

Phase 13
Logbook

Phase 14
E-LPJ

Phase 15
Opportunity

Phase 16
Identity Verification Admin Workflow

Phase 17
Analytics

Phase 18
Notifications

Phase 19
Public Pages

Phase 20
Google Stitch UI Integration / Polish

Phase 21
Security, Testing & Finalization

Agent tidak boleh otomatis melanjutkan ke phase berikutnya tanpa review.
82. Definition of Done
Sebuah feature belum dianggap selesai hanya karena halaman muncul.
Feature dianggap selesai jika:
database migration correct
model relation correct
authorization implemented
validation implemented
business rule implemented
happy path works
error state works
empty state handled
responsive enough
security considered
tests written
tests passing

83. Legacy Mandorin Rules
Folder:
mandorin/

boleh digunakan untuk:
- membaca business logic lama,
- memahami Program workflow,
- memahami Proposal,
- Logbook,
- E-LPJ,
- melihat naming lama,
- melihat UX lama.
Tidak boleh:
- copy architecture tanpa evaluasi,
- mempertahankan legacy naming hanya karena existing,
- mempertahankan database design jika bertentangan dengan PRD,
- menjadikan legacy code sebagai source of truth.
SIPORA v2 harus tetap fresh implementation.
84. Coding Agent Rules
AI agent harus:
1. Membaca PRD terlebih dahulu sebelum perubahan besar.
2. Membaca relevant legacy files sebelum mereimplementasi feature lama.
3. Tidak membuat feature di luar phase.
4. Tidak mengganti stack tanpa approval.
5. Tidak menambah package tanpa alasan.
6. Tidak mengubah database architecture diam-diam.
7. Tidak membuat role Manager sebagai global Spatie role.
8. Tidak menganggap Google OAuth sebagai Identity Verification.
9. Tidak memasukkan sensitive document ke public storage.
10. Tidak membuat business logic database trigger.
11. Tidak membuat fat controller.
12. Tidak melakukan hard delete historical data tanpa alasan.
13. Tidak menampilkan database status mentah langsung ke UI.
14. Tidak membuat analytics sebagai ranking manusia.
15. Tidak over-engineer MVP.
16. Tidak melanjutkan phase otomatis.
85. Required Agent Report After Each Phase
Setelah setiap phase, agent harus berhenti dan melaporkan:
PHASE:
STATUS:

IMPLEMENTED:

FILES CREATED:

FILES MODIFIED:

DATABASE CHANGES:

ROUTES:

AUTHORIZATION:

VALIDATION:

BUSINESS RULES:

TESTS:
Passed:
Failed:

COMMANDS VERIFIED:

SECURITY NOTES:

KNOWN ISSUES:

DEVIATIONS FROM PRD:

NEXT PHASE READINESS:

Jika terdapat deviation, harus dijelaskan.
86. Project Success Criteria
SIPORA v2 dianggap berhasil jika:
Pemuda dapat:
discover
participate
build verified experience

Community dapat:
grow
organize
manage activities

Dindikpora dapat:
verify
monitor
measure
understand youth participation

dan semuanya berada dalam:
satu ekosistem digital yang konsisten.