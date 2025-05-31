-- SQL Schema for Accreditation Management System

-- Users Table
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('superuser', 'regular') NOT NULL DEFAULT 'regular',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Accreditation Processes Table
-- Needs to be defined before `documents` if `documents` references it.
CREATE TABLE IF NOT EXISTS `accreditation_processes` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `description` TEXT NULL,
    `start_date` DATE NULL,
    `end_date` DATE NULL,
    `status` VARCHAR(50) NOT NULL DEFAULT 'pending', -- e.g., pending, active, completed, archived
    `created_by_user_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Document Templates Table
-- Defines the structure for fillable documents.
CREATE TABLE IF NOT EXISTS `document_templates` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL UNIQUE,
  `description` TEXT NULL,
  `fields_definition` JSON NOT NULL, -- Stores the form fields structure
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by_user_id` INT, -- User who created the template
  FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Documents Table (Stores filled instances of Document Templates)
-- Renamed conceptually from "documents" to "filled_documents" but table name remains `documents` for now to minimize code changes.
-- This table now stores instances of filled documents based on templates.
CREATE TABLE IF NOT EXISTS `documents` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `accreditation_process_id` INT NOT NULL,
  `document_template_id` INT NOT NULL,
  `user_id` INT NOT NULL, -- User who created/owns this filled document instance
  `name` VARCHAR(255) NOT NULL, -- This is the title of the filled document instance, given by user
  `form_data` JSON NOT NULL, -- Stores the actual filled data based on template's fields_definition
  `status` VARCHAR(50) NOT NULL DEFAULT 'draft', -- e.g., draft, submitted, under_review, approved, rejected
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`accreditation_process_id`) REFERENCES `accreditation_processes`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`document_template_id`) REFERENCES `document_templates`(`id`) ON DELETE RESTRICT, -- Prevent template deletion if instances exist
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE -- If user is deleted, their filled docs are deleted
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tasks Table
-- Tasks are now associated with specific filled documents (instances).
CREATE TABLE IF NOT EXISTS `tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `document_id` INT NOT NULL, -- This now refers to an ID in the `documents` (filled_documents) table
    `description` TEXT NOT NULL,
    `due_date` DATE NULL,
    `status` VARCHAR(50) NOT NULL DEFAULT 'pending', -- e.g., pending, in_progress, completed, overdue
    `created_by_user_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`document_id`) REFERENCES `documents`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Task Assignments Table
CREATE TABLE IF NOT EXISTS `task_assignments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `task_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `assigned_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `task_user_unique` (`task_id`, `user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Comments/Feedback Table
CREATE TABLE IF NOT EXISTS `comments_feedback` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `entity_type` ENUM('process', 'document', 'task') NOT NULL, -- 'document' now refers to a filled document instance
  `entity_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  `comment_text` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX `idx_comments_entity` (`entity_type`, `entity_id`), -- Renamed index for clarity
  CONSTRAINT `fk_comment_user`
    FOREIGN KEY (`user_id`)
    REFERENCES `users` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Indexes for common lookups
-- For users table
CREATE INDEX IF NOT EXISTS `idx_users_email` ON `users`(`email`);
CREATE INDEX IF NOT EXISTS `idx_users_role` ON `users`(`role`);

-- For accreditation_processes table
CREATE INDEX IF NOT EXISTS `idx_accreditation_processes_status` ON `accreditation_processes`(`status`);
CREATE INDEX IF NOT EXISTS `idx_ap_created_by_user_id` ON `accreditation_processes`(`created_by_user_id`);

-- For document_templates table
CREATE INDEX IF NOT EXISTS `idx_dt_created_by_user_id` ON `document_templates`(`created_by_user_id`);
CREATE INDEX IF NOT EXISTS `idx_dt_name` ON `document_templates`(`name`);

-- For documents (filled_documents) table
CREATE INDEX IF NOT EXISTS `idx_documents_accreditation_process_id` ON `documents`(`accreditation_process_id`);
CREATE INDEX IF NOT EXISTS `idx_documents_document_template_id` ON `documents`(`document_template_id`);
CREATE INDEX IF NOT EXISTS `idx_documents_user_id` ON `documents`(`user_id`);
CREATE INDEX IF NOT EXISTS `idx_documents_status` ON `documents`(`status`);

-- For tasks table
CREATE INDEX IF NOT EXISTS `idx_tasks_document_id` ON `tasks`(`document_id`);
CREATE INDEX IF NOT EXISTS `idx_tasks_status` ON `tasks`(`status`);
CREATE INDEX IF NOT EXISTS `idx_task_created_by_user_id` ON `tasks`(`created_by_user_id`);

-- For task_assignments table
CREATE INDEX IF NOT EXISTS `idx_task_assignments_task_id` ON `task_assignments`(`task_id`);
CREATE INDEX IF NOT EXISTS `idx_task_assignments_user_id` ON `task_assignments`(`user_id`);

-- For comments_feedback table
-- idx_comments_entity is created in table definition.
-- Index on user_id in comments_feedback is likely created by the FK constraint, but can be explicit:
CREATE INDEX IF NOT EXISTS `idx_comments_feedback_user_id` ON `comments_feedback`(`user_id`);


-- Example INSERT statements (updated for new structure where applicable)

-- Example User
-- INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
-- ('Super User', 'superuser@example.com', 'hashed_password_here', 'superuser');

-- Example Accreditation Process
-- INSERT INTO `accreditation_processes` (`title`, `description`, `created_by_user_id`) VALUES
-- ('ABET CS 2025', 'Accreditation for Computer Science Program 2025 cycle.', (SELECT id FROM users WHERE email = 'superuser@example.com'));

-- Example Document Template
-- INSERT INTO `document_templates` (`name`, `description`, `fields_definition`, `created_by_user_id`) VALUES
-- ('Course Syllabus Template', 'Standard template for all course syllabi.',
--  '{"fields": [{"name": "course_code", "label": "Course Code", "type": "text", "required": true}, {"name": "course_title", "label": "Course Title", "type": "text", "required": true}, {"name": "instructor_name", "label": "Instructor Name", "type": "text"}, {"name": "learning_outcomes", "label": "Learning Outcomes", "type": "textarea"}]}',
--  (SELECT id FROM users WHERE email = 'superuser@example.com'));

-- Example Filled Document (using the template above)
-- INSERT INTO `documents` (`accreditation_process_id`, `document_template_id`, `user_id`, `name`, `form_data`, `status`) VALUES
-- ((SELECT id FROM accreditation_processes WHERE title = 'ABET CS 2025'),
--  (SELECT id FROM document_templates WHERE name = 'Course Syllabus Template'),
--  (SELECT id FROM users WHERE email = 'superuser@example.com'),
--  'CS101 Syllabus Fall 2024',
--  '{"course_code": "CS101", "course_title": "Introduction to Computer Science", "instructor_name": "Dr. Ada Lovelace", "learning_outcomes": "Students will be able to..."}',
--  'draft');

-- Example Task for the CS101 Syllabus
-- INSERT INTO `tasks` (`document_id`, `description`, `created_by_user_id`, `due_date`, `status`) VALUES
-- ((SELECT id FROM documents WHERE name = 'CS101 Syllabus Fall 2024'),
--  'Review learning outcomes section for clarity and completeness.',
--  (SELECT id FROM users WHERE email = 'superuser@example.com'),
--  '2024-09-15',
--  'pending');

-- Notes on Schema Changes:
-- - `document_templates` table added to store reusable document structures.
-- - `documents` table is now for storing instances of these templates filled with data.
--   - Removed `onedrive_url`, `uploaded_by_user_id`.
--   - Added `document_template_id` (FK to `document_templates`).
--   - Added `user_id` (FK to `users`) for the creator/owner of the filled document.
--   - Added `form_data` (JSON) to store the filled-in data.
--   - `status` default changed to 'draft'.
-- - Foreign key `document_template_id` in `documents` uses `ON DELETE RESTRICT` to protect data integrity.
-- - Foreign key `user_id` (owner) in `documents` uses `ON DELETE CASCADE`.
-- - All tables now use `CREATE TABLE IF NOT EXISTS` and specify `ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci` for consistency.
-- - Indexes have been reviewed and added/updated for new columns and foreign keys.
-- - Example INSERT statements have been updated to reflect the new structure.
-- - Comments throughout the script have been updated to reflect these changes.
-- - The previous index `idx_doc_uploaded_by_user_id` is no longer relevant and effectively removed as the column is gone.

-- End of Schema
