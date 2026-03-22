-- MyOMR live: IDAM internship + Digiclarity Payment Poster
-- Source: research-aggregator-vacancies-db-ready.csv (rows gated verified + no blockers)
--
-- ALREADY APPLIED: 2026-03-22 via CLI:
--   php dev-tools/jobs/insert_idam_digiclarity_research_jobs.php
-- Result: employers id 20 (idam.internship@myomr.in), 21 (recruitment@digiclarity.in);
--         job_postings id 19, 20.
--
-- Do NOT re-run blindly (duplicate employers/jobs). Use for documentation, DR, or a fresh DB
-- after adjusting emails or removing prior rows.
--
-- Charset: utf8mb4

START TRANSACTION;

-- Employer 1: IDAM (placeholder MyOMR email; update phone when known)
INSERT INTO employers (company_name, contact_person, email, phone, address, website, status, plan_type)
VALUES (
  'IDAM - The Art And Cultural Space',
  'HR',
  'idam.internship@myomr.in',
  '0000000000',
  'Chennai, Tamil Nadu, India',
  'https://www.idamthespace.com/',
  'verified',
  'free'
);
SET @emp_idam = LAST_INSERT_ID();

INSERT INTO job_postings (
  employer_id, title, category, job_type, work_segment, location, salary_range,
  description, requirements, benefits, application_deadline, status, featured
) VALUES (
  @emp_idam,
  'Creative Associate (Internship)',
  'sales-marketing',
  'Internship',
  'office',
  'Chennai',
  'Stipend ₹3,500–4,100/month',
  'In-office creative internship at an art and cultural venue in Chennai. Work includes content for social channels and the website, community engagement, light administration, and support for events (including photography and videography). Duration is about four months; stipend in the stated range. Apply via the official internship application on Internshala or the internship form linked from the employer website.',
  'Chennai-based and available for a full-time in-office internship; strong written English; content and creative skills; familiarity with Canva or similar tools is useful; comfortable supporting events and basic photo/video tasks. Women restarting their career may also apply (per public listing).',
  'Stipend (fixed and incentive components per employer). Certificate and letter of recommendation may be offered. Informal dress code per employer.',
  '2026-04-15',
  'approved',
  0
);

-- Employer 2: Digiclarity
INSERT INTO employers (company_name, contact_person, email, phone, address, website, status, plan_type)
VALUES (
  'Digiclarity Global Solutions Pvt Ltd',
  'Recruitment',
  'recruitment@digiclarity.in',
  '04424540003',
  'CeeDeeYes Tyche Tower, Block 1, 2nd Floor, No. 1 MGR Salai, Veeranam Bypass Road, Kodandarama Nagar, Perungudi, Chennai 600096',
  'https://www.digiclarity.in/',
  'verified',
  'free'
);
SET @emp_dig = LAST_INSERT_ID();

INSERT INTO job_postings (
  employer_id, title, category, job_type, work_segment, location, salary_range,
  description, requirements, benefits, application_deadline, status, featured
) VALUES (
  @emp_dig,
  'Payment Poster (Medical Billing)',
  'healthcare',
  'Full-time',
  'office',
  'Chennai',
  'Not Disclosed',
  'Medical billing role at a Chennai-based revenue cycle management company. Focus on accurate payment posting and related billing support. Shift pattern and client details are confirmed with HR.',
  'Prior experience in medical billing or payment posting is preferred; high accuracy with numbers; ability to follow defined processes and quality standards.',
  'Discuss benefits and salary with HR. Apply by email using the subject line format published on the company careers page.',
  DATE_ADD(CURDATE(), INTERVAL 45 DAY),
  'approved',
  0
);

COMMIT;
