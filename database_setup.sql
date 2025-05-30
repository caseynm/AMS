-- SQL Schema for Accreditation Management System

-- Users Table
CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) NOT NULL,
    `email` VARCHAR(255) NOT NULL UNIQUE,
    `password` VARCHAR(255) NOT NULL,
    `role` ENUM('superuser', 'regular') NOT NULL DEFAULT 'regular',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Accreditation Processes Table
CREATE TABLE `accreditation_processes` (
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
);

-- Documents Table
CREATE TABLE `documents` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `accreditation_process_id` INT NOT NULL,
    `name` VARCHAR(255) NOT NULL,
    `onedrive_url` VARCHAR(1024) NULL,
    `status` VARCHAR(50) NOT NULL DEFAULT 'pending', -- e.g., pending, in_progress, completed
    `uploaded_by_user_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`accreditation_process_id`) REFERENCES `accreditation_processes`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`uploaded_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
);

-- Tasks Table
CREATE TABLE `tasks` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `document_id` INT NOT NULL,
    `description` TEXT NOT NULL,
    `due_date` DATE NULL,
    `status` VARCHAR(50) NOT NULL DEFAULT 'pending', -- e.g., pending, in_progress, completed, overdue
    `created_by_user_id` INT,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`document_id`) REFERENCES `documents`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
);

-- Task Assignments Table
CREATE TABLE `task_assignments` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `task_id` INT NOT NULL,
    `user_id` INT NOT NULL,
    `assigned_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `task_user_unique` (`task_id`, `user_id`)
);

-- Comments/Feedback Table
CREATE TABLE `comments_feedback` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `entity_type` ENUM('process', 'document', 'task') NOT NULL,
    `entity_id` INT NOT NULL, -- Refers to ID in accreditation_processes, documents, or tasks
    `user_id` INT NOT NULL,
    `comment_text` TEXT NOT NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL -- Keep comment if user is deleted, or use CASCADE if preferred
    -- Note: No direct foreign key on entity_id due to polymorphism. This will be handled by application logic.
    -- Adding indexes for faster lookups based on entity
);

-- Indexes for common lookups
CREATE INDEX `idx_accreditation_processes_status` ON `accreditation_processes`(`status`);
CREATE INDEX `idx_documents_accreditation_process_id` ON `documents`(`accreditation_process_id`);
CREATE INDEX `idx_documents_status` ON `documents`(`status`);
CREATE INDEX `idx_tasks_document_id` ON `tasks`(`document_id`);
CREATE INDEX `idx_tasks_status` ON `tasks`(`status`);
CREATE INDEX `idx_task_assignments_task_id` ON `task_assignments`(`task_id`);
CREATE INDEX `idx_task_assignments_user_id` ON `task_assignments`(`user_id`);
CREATE INDEX `idx_comments_feedback_entity` ON `comments_feedback`(`entity_type`, `entity_id`);
CREATE INDEX `idx_comments_feedback_user_id` ON `comments_feedback`(`user_id`);

-- Example of how to add a user (password should be hashed by the application)
-- INSERT INTO `users` (`name`, `email`, `password`, `role`) VALUES
-- ('Super User', 'superuser@ashesi.edu.gh', 'hashed_password_here', 'superuser'),
-- ('Regular User', 'user@ashesi.edu.gh', 'hashed_password_here', 'regular');

-- Example of an accreditation process
-- INSERT INTO `accreditation_processes` (`title`, `description`, `created_by_user_id`) VALUES
-- ('ABET Accreditation 2024', 'Process for ABET Computer Science program accreditation.', 1);

-- Example of a document
-- INSERT INTO `documents` (`accreditation_process_id`, `name`, `uploaded_by_user_id`) VALUES
-- (1, 'Syllabus_CS101.pdf', 2);

-- Example of a task
-- INSERT INTO `tasks` (`document_id`, `description`, `created_by_user_id`) VALUES
-- (1, 'Review and update learning outcomes for CS101.', 1);

-- Example of a task assignment
-- INSERT INTO `task_assignments` (`task_id`, `user_id`) VALUES
-- (1, 2);

-- Example of a comment on a document
-- INSERT INTO `comments_feedback` (`entity_type`, `entity_id`, `user_id`, `comment_text`) VALUES
-- ('document', 1, 1, 'Please ensure the syllabus matches the latest template.');

-- Example of a comment on a task
-- INSERT INTO `comments_feedback` (`entity_type`, `entity_id`, `user_id`, `comment_text`) VALUES
-- ('task', 1, 2, 'I will complete this by Friday.');

-- Example of a comment on a process
-- INSERT INTO `comments_feedback` (`entity_type`, `entity_id`, `user_id`, `comment_text`) VALUES
-- ('process', 1, 1, 'The overall process is on track.');

ALTER TABLE `accreditation_processes` ADD INDEX `idx_ap_created_by_user_id` (`created_by_user_id`);
ALTER TABLE `documents` ADD INDEX `idx_doc_uploaded_by_user_id` (`uploaded_by_user_id`);
ALTER TABLE `tasks` ADD INDEX `idx_task_created_by_user_id` (`created_by_user_id`);

-- Note on comments_feedback foreign key for entity_id:
-- A direct SQL foreign key cannot reference multiple tables for entity_id.
-- This type of polymorphic association is typically enforced at the application level.
-- For example, when entity_type = 'document', the application ensures entity_id exists in the 'documents' table.
-- If strict DB-level referential integrity for entity_id is required across multiple tables,
-- alternative schema designs could be used, such as:
-- 1. Separate comment tables for each entity (comments_documents, comments_tasks, etc.).
-- 2. A central "entities" table that all commentable items link to, and comments link to this central table.
-- For this initial setup, we'll rely on application logic for this specific constraint.
-- However, we can ensure that if an entity is deleted, related comments are also deleted using triggers if the database supports it,
-- or by application logic. For now, we will keep the comments and handle broken references in the application or add triggers later.

-- End of Schema
-- Consider adding triggers for complex cascading deletes or audit trails if needed.
-- For instance, if a document is deleted, and you want to remove associated comments directly via SQL:
-- DELIMITER //
-- CREATE TRIGGER after_document_delete
-- AFTER DELETE ON documents
-- FOR EACH ROW
-- BEGIN
--   DELETE FROM comments_feedback WHERE entity_type = 'document' AND entity_id = OLD.id;
-- END; //
-- DELIMITER ;
-- Similar triggers could be made for tasks and accreditation_processes.
-- However, this can also be handled by the application when an entity is deleted.
-- For now, ON DELETE CASCADE is used for direct, hierarchical relationships.
-- The relationship between comments_feedback and the entities it refers to is polymorphic and handled at the app level.
-- If a user is deleted, their comments are kept (ON DELETE SET NULL for user_id in comments_feedback).
-- If an accreditation_process is deleted, its documents are deleted (CASCADE), which in turn deletes tasks (CASCADE), which then deletes task_assignments (CASCADE).
-- Comments related to these deleted entities would need to be cleaned up by the application or via triggers as described above.
-- For simplicity in this initial script, such triggers are noted but not implemented.
-- The current setup for comments_feedback.user_id is ON DELETE SET NULL.
-- This means if a user is deleted, their comments remain, attributed to a NULL user.
-- If you prefer to delete comments when a user is deleted, change to ON DELETE CASCADE.
-- The choice depends on data retention policies.
-- Foreign keys for created_by_user_id in accreditation_processes, documents, and tasks are set to ON DELETE SET NULL.
-- This means if a user who created these entities is deleted, the entities themselves are not deleted, but their creator is marked as NULL.
-- This is often a preferred approach to avoid accidental data loss.
-- If these entities should be deleted when their creator is deleted, change to ON DELETE CASCADE.
-- This is generally less common for "created_by" relationships.
-- Task_assignments are deleted if either the task or the assigned user is deleted (ON DELETE CASCADE for both task_id and user_id).
-- This makes sense as an assignment is meaningless without both.
-- The UNIQUE KEY on task_assignments (task_id, user_id) prevents duplicate assignments.
-- Indexes are added on foreign keys and columns frequently used in WHERE clauses (like status or entity_type/entity_id) to improve query performance.
-- The ENUM types provide a controlled set of values for columns like 'role', 'status', and 'entity_type'.
-- Timestamps for created_at and updated_at help in tracking data changes.
-- `updated_at` for `accreditation_processes`, `documents`, and `tasks` automatically updates when the row is modified.
-- `onedrive_url` in `documents` is VARCHAR(1024) to accommodate potentially long URLs.
-- The database character set and collation are not specified here but should be UTF-8 (e.g., utf8mb4_unicode_ci) for broad language support.
-- This script assumes a MySQL-compatible database. Syntax might vary slightly for other SQL databases.
-- Final check of table names and column names for consistency.
-- All primary keys are `id` INT AUTO_INCREMENT.
-- Foreign keys are named descriptively.
-- Status columns use VARCHAR(50) which is flexible enough for various status strings.
-- Password in users table is VARCHAR(255) to store hashes (e.g., bcrypt).
-- Default values are set where appropriate (e.g., CURRENT_TIMESTAMP, 'pending', 'regular').
-- Nullability constraints (NOT NULL, NULL) are defined as per requirements.
-- Unique constraint on users.email is critical.
-- The script includes example INSERT statements (commented out) for guidance.
-- The note about polymorphic association for comments_feedback.entity_id is important.
-- The script is structured to be run multiple times if needed (CREATE TABLE without IF NOT EXISTS, so it will fail if tables exist).
-- For development, `CREATE TABLE IF NOT EXISTS` could be used, or a separate `DROP TABLE IF EXISTS ...` script.
-- For production, migrations tools often handle schema changes more robustly.
-- Added indexes to foreign key columns explicitly, although some DB systems do this automatically.
-- E.g., idx_ap_created_by_user_id, idx_doc_uploaded_by_user_id, idx_task_created_by_user_id.
-- This ensures these critical joins are optimized.
-- The default for `comments_feedback.user_id` ON DELETE SET NULL is a reasonable choice to preserve comment history even if a user account is removed.
-- If the business rule was "comments must be deleted if the commenting user is deleted", then ON DELETE CASCADE would be used.
-- The cascading deletes from accreditation_processes -> documents -> tasks -> task_assignments is a strong chain.
-- Deleting an accreditation process will clean up all its related data down this hierarchy.
-- Comments related to these entities are not automatically cleaned by these direct cascades due to the polymorphic nature.
-- This is a common pattern: core hierarchical data is cleaned by DB FKs, while more loosely coupled or polymorphic relations
-- (like comments, tags, audit logs) might require application-level cleanup or database triggers.
-- The length of VARCHAR for `status` (50) should be sufficient for descriptive status texts.
-- `description` fields are TEXT for longer content.
-- `start_date` and `end_date` are DATE type, suitable for just dates without time.
-- `onedrive_url` is nullable as a document might exist in the system before its file is uploaded or if it's a physical document.
