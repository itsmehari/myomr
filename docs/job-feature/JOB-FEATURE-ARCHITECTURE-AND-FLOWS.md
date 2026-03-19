# Job Feature — Full Architecture and Flow Analysis

This document describes the MyOMR job feature: stakeholders, system connections, data layer, and flow diagrams. It also references the Employer Pack and the common Jobs hub entry page.

---

## 1. Stakeholders and systems (summary)

| Stakeholder      | Entry points                                                                                                                                 | Main actions                                                                                                           | Auth / session                                                                                                                                 |
| ---------------- | --------------------------------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------- |
| **Job seeker**   | omr-local-job-listings/index.php, Jobs hub (jobs-hub-omr.php), root landing pages (e.g. jobs-in-perungudi-omr.php), homepage job links        | Browse jobs, view detail, apply (resume upload), save job / job alert                                                  | Optional cookie (`applicant_email`) for "already applied"                                                                                      |
| **Employer**     | employer-login-omr.php, employer-register-omr.php, employer-pack-landing-omr.php, Jobs hub                                                | Post/edit job, view my-posted-jobs, dashboard, view/update applications, edit profile                                  | employer-auth.php: employer_id, employer_email, employer_company, employer_status                                                             |
| **Admin**        | core/admin-auth.php; job admin under omr-local-job-listings/admin/                                                                          | Manage jobs (approve/reject, Plan column, auto-featured), verify employers, package subscribers, view all applications | $_SESSION['admin_logged_in'], admin_csrf                                                                                                       |
| **Cron**         | omr-local-job-listings/cron/expire-jobs.php                                                                                                  | Close jobs past application_deadline; optional email to employer                                                     | CLI only                                                                                                                                       |
| **API consumer** | omr-local-job-listings/api/jobs.php                                                                                                          | GET jobs list (filters, pagination) — same filters as main listing                                                     | None                                                                                                                                           |

**Shared backend:** core/omr-connect.php (single DB); omr-local-job-listings/includes/job-functions-omr.php (getJobListings, getJobCount, getJobById, plan helpers, filters).

---

## 2. Data layer (tables and key pages)

Core tables used by the job feature:

- **employers** — id, company_name, contact_person, email, phone, address, website, status; plus Employer Pack: plan_type, plan_start_date, plan_end_date.
- **job_postings** — employer_id, title, category, job_type, work_segment, location, salary_range, description, requirements, benefits, application_deadline, status (pending/approved/rejected/closed), featured, views, created_at; optional work_segment.
- **job_applications** — job_id, applicant_name, applicant_email, applicant_phone, experience_years, cover_letter, resume_path, status, created_at.
- **job_categories** — slug, name (used in JOINs for display and filters).
- **admin_audit_log** — admin actions (job status, employer status).

Listing and detail use `job_postings` JOIN `employers` and `job_categories`; ordering is `featured DESC, created_at DESC`. Plan logic reads/writes `employers.plan_type` and (for cap) counts approved jobs in current month.

---

## 3. Flow diagrams

### Diagram A — Stakeholders and system connections

```mermaid
flowchart TB
    subgraph seekers [Job seeker]
        Browse[index.php and location landing pages]
        Detail[job-detail-omr.php]
        Apply[process-application-omr.php]
    end
    subgraph employers [Employer]
        Login[employer-login / register]
        Post[post-job-omr.php]
        ProcessJob[process-job-omr.php]
        Dashboard[employer-dashboard-omr.php]
        MyJobs[my-posted-jobs-omr.php]
        ViewApp[view-applications-omr.php]
        PackLanding[employer-pack-landing-omr.php]
    end
    subgraph admin [Admin]
        ManageJobs[manage-jobs-omr.php]
        VerifyEmp[verify-employers-omr.php]
        PkgSub[package-subscribers-omr.php]
        ViewAllApp[view-all-applications-omr.php]
    end
    subgraph backend [Backend]
        DB[(MySQL employers job_postings job_applications job_categories)]
        JobFn[job-functions-omr.php]
        Auth[employer-auth.php]
    end
    subgraph external [External]
        API[api/jobs.php]
        Cron[cron/expire-jobs.php]
    end
    Browse --> JobFn
    Detail --> JobFn
    Apply --> DB
    Login --> Auth
    Auth --> DB
    Post --> ProcessJob
    ProcessJob --> DB
    ProcessJob --> JobFn
    Dashboard --> DB
    MyJobs --> DB
    ViewApp --> DB
    ManageJobs --> DB
    ManageJobs --> JobFn
    VerifyEmp --> DB
    PkgSub --> DB
    PkgSub --> JobFn
    JobFn --> DB
    API --> JobFn
    Cron --> DB
```

### Diagram B — Job seeker journey (with Jobs hub)

Home and main nav "Jobs" link go to the **Jobs hub** first; hub then links to Browse. Direct links and location landings can still go to the listing index.

```mermaid
flowchart LR
    subgraph entry [Entry]
        Home[Homepage or nav Jobs link]
        Landing[Location landing e.g. jobs-in-perungudi-omr.php]
    end
    subgraph hub [Common entry]
        JobsHub[Jobs hub jobs-hub-omr.php]
    end
    subgraph browse [Browse]
        Index[omr-local-job-listings/index.php]
        Filters[Filters and pagination]
    end
    subgraph apply_flow [Apply]
        Detail[job-detail-omr.php]
        ProcessApp[process-application-omr.php]
        Success[application-submitted-omr.php]
    end
    Home --> JobsHub
    JobsHub --> Index
    Landing --> Index
    Index --> Filters
    Filters --> Detail
    Detail --> ProcessApp
    ProcessApp --> Success
```

### Diagram C — Employer journey (including Employer Pack)

```mermaid
flowchart TB
    subgraph auth_flow [Auth]
        Reg[employer-register-omr.php]
        Login[employer-login-omr.php]
        Session[employer_id in session]
    end
    subgraph post_flow [Post job]
        PostForm[post-job-omr.php]
        ProcessJob[process-job-omr.php]
        CapCheck[Cap check if active plan]
        SuccessPage[job-posted-success-omr.php]
    end
    subgraph manage [Manage]
        Dashboard[employer-dashboard-omr.php]
        MyJobs[my-posted-jobs-omr.php]
        EditJob[edit-job-omr.php]
        ViewApp[view-applications-omr.php]
        UpdateStatus[update-application-status-omr.php]
    end
    subgraph plan_ui [Employer Pack UI]
        PlanBlock[Plan block X/cap renews on dashboard and my-posted-jobs]
        UsagePost[Usage line on post-job form]
    end
    Reg --> Session
    Login --> Session
    Session --> PostForm
    PostForm --> ProcessJob
    ProcessJob --> CapCheck
    CapCheck --> SuccessPage
    Session --> Dashboard
    Session --> MyJobs
    Dashboard --> PlanBlock
    MyJobs --> PlanBlock
    PostForm --> UsagePost
    MyJobs --> EditJob
    MyJobs --> ViewApp
    ViewApp --> UpdateStatus
```

### Diagram D — Admin operations and Employer Pack

```mermaid
flowchart TB
    subgraph admin_entry [Admin entry]
        JobAdminIndex[omr-local-job-listings/admin/index.php]
    end
    subgraph job_ops [Job operations]
        ManageJobs[manage-jobs-omr.php]
        ApproveReject[Approve or Reject]
        PlanColumn[Plan column badge]
        AutoFeatured[Auto featured for package employer]
    end
    subgraph employer_ops [Employer operations]
        VerifyEmp[verify-employers-omr.php]
        Status[Verify / Suspend / Pending]
        PkgSub[package-subscribers-omr.php]
        ListActive[List active plan and usage]
    end
    subgraph data [Data]
        EmployersTable[employers with plan_type plan_end_date]
        JobPostings[job_postings featured status]
    end
    JobAdminIndex --> ManageJobs
    JobAdminIndex --> VerifyEmp
    JobAdminIndex --> PkgSub
    ManageJobs --> ApproveReject
    ManageJobs --> PlanColumn
    ApproveReject --> AutoFeatured
    AutoFeatured --> JobPostings
    VerifyEmp --> Status
    Status --> EmployersTable
    PkgSub --> ListActive
    ListActive --> EmployersTable
```

### Diagram E — Data flow (tables and main readers/writers)

```mermaid
flowchart LR
    subgraph tables [DB tables]
        E[employers]
        J[job_postings]
        A[job_applications]
        C[job_categories]
    end
    subgraph writers [Writers]
        ProcessJob[process-job-omr.php]
        ProcessApp[process-application-omr.php]
        ManageJobs[manage-jobs-omr.php]
        VerifyEmp[verify-employers-omr.php]
        ExpireCron[expire-jobs cron]
    end
    subgraph readers [Readers]
        Index[index.php]
        Detail[job-detail-omr.php]
        JobFn[job-functions-omr]
        API[api/jobs.php]
    end
    ProcessJob --> E
    ProcessJob --> J
    ProcessApp --> A
    ManageJobs --> J
    VerifyEmp --> E
    ExpireCron --> J
    Index --> JobFn
    Detail --> JobFn
    API --> JobFn
    JobFn --> J
    JobFn --> E
    JobFn --> C
```

---

## 4. Plans that apply to the job feature

- **Employer Pack (B2B):** Documented in docs/product/EMPLOYER-PACK-PRODUCT.md. Plan columns on `employers`; cap enforced in process-job-omr.php; auto-featured and Plan column in admin/manage-jobs-omr.php; plan/usage block on employer-dashboard-omr.php and my-posted-jobs-omr.php; usage line on post-job-omr.php; admin/package-subscribers-omr.php and employer-pack-landing-omr.php.
- **Jobs hub (common entry):** omr-local-job-listings/jobs-hub-omr.php is the recommended first touch for both job seekers and employers when they click "Jobs" from the homepage or main navigation. From the hub, "Browse jobs" goes to the listing index; "Post a job" / employer path goes to employer login or Employer Pack landing.
- **Root landing pages:** jobs-in-perungudi-omr.php, it-jobs-omr-chennai.php, fresher-jobs-omr-chennai.php, etc. use the same DB and either direct queries or landing-page-template.php; they are entry points for job seekers and link to the main listing or detail.
- **Homepage:** index.php at root fetches recent_jobs from job_postings and links to the Jobs hub; search form can submit to the job listing.
