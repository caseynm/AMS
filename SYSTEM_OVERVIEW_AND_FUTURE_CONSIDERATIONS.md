# Accreditation Management System (AMS) - System Overview & Future Considerations

## I. System Overview & Main URL Routes (Conceptual API Endpoints)

This section outlines the primary URL patterns used in the AMS and the controller actions they map to. The base URL structure is assumed to be `http://yourdomain/index.php?url=CONTROLLER/ACTION/PARAMS` or `http://yourdomain/CONTROLLER/ACTION/PARAMS` with effective URL rewriting via `.htaccess`.

### User Management

*   `GET /user/showRegistrationForm` -> `UserController@showRegistrationForm`
    *   **Description:** Displays the user registration form.
*   `POST /user/register` -> `UserController@register`
    *   **Description:** Handles the submission of the registration form and creates a new user.
*   `GET /user/showLoginForm` -> `UserController@showLoginForm`
    *   **Description:** Displays the user login form.
*   `POST /user/login` -> `UserController@login`
    *   **Description:** Handles the submission of the login form and establishes a user session.
*   `GET /user/logout` -> `UserController@logout`
    *   **Description:** Logs out the current user and destroys the session.
*   `GET /user/profile` -> `UserController@profile`
    *   **Description:** Displays the profile information for the currently logged-in user.
*   `GET /user/listUsers` -> `UserController@listUsers`
    *   **Description:** (Superuser only) Displays a list of all users in the system.

### Home/Dashboard

*   `GET /` or `GET /home/index` -> `HomeController@index`
    *   **Description:** Displays the main dashboard, typically showing a list of accreditation processes or other relevant overview information for the logged-in user.

### Accreditation Process Management

*   `GET /accreditation/index` -> `AccreditationController@index`
    *   **Description:** Lists all accreditation processes, usually in a table format.
*   `GET /accreditation/showCreateForm` -> `AccreditationController@showCreateForm`
    *   **Description:** (Superuser only) Displays the form to create a new accreditation process.
*   `POST /accreditation/create` -> `AccreditationController@create`
    *   **Description:** (Superuser only) Handles the submission of the new accreditation process form.
*   `GET /accreditation/show/{id}` -> `AccreditationController@show`
    *   **Description:** Displays detailed information about a specific accreditation process (identified by `{id}`), including a list of its associated documents.
*   `GET /accreditation/showEditForm/{id}` -> `AccreditationController@showEditForm`
    *   **Description:** (Superuser only) Displays the form to edit an existing accreditation process.
*   `POST /accreditation/update/{id}` -> `AccreditationController@update`
    *   **Description:** (Superuser only) Handles the submission of the form to update an accreditation process.
*   `GET /accreditation/delete/{id}` -> `AccreditationController@delete`
    *   **Description:** (Superuser only) Handles the deletion of an accreditation process and its related data (documents, tasks via cascading deletes).

### Document Management

*   `GET /document/showCreateForm/{process_id}` -> `DocumentController@showCreateForm`
    *   **Description:** (Superuser only) Displays the form to add a new document to a specific accreditation process (identified by `{process_id}`).
*   `POST /document/create/{process_id}` -> `DocumentController@create`
    *   **Description:** (Superuser only) Handles the submission of the new document form.
*   `GET /document/showEditForm/{id}` -> `DocumentController@showEditForm`
    *   **Description:** (Superuser only) Displays the form to edit an existing document (identified by `{id}`).
*   `POST /document/update/{id}` -> `DocumentController@update`
    *   **Description:** (Superuser only) Handles the submission of the document update form.
*   `GET /document/delete/{id}/{process_id_for_redirect}` -> `DocumentController@delete`
    *   **Description:** (Superuser only) Handles the deletion of a document. Redirects back to the parent process page.
    *   *(Note: Documents are primarily listed within the accreditation process view: `GET /accreditation/show/{id}`)*

### Task Management

*   `GET /task/listByDocument/{document_id}` -> `TaskController@listByDocument`
    *   **Description:** Lists all tasks associated with a specific document (identified by `{document_id}`).
*   `GET /task/showCreateForm/{document_id}` -> `TaskController@showCreateForm`
    *   **Description:** (Superuser only) Displays the form to create a new task for a specific document.
*   `POST /task/create/{document_id}` -> `TaskController@create`
    *   **Description:** (Superuser only) Handles the submission of the new task form.
*   `GET /task/showEditForm/{task_id}` -> `TaskController@showEditForm`
    *   **Description:** (Superuser only) Displays the form to edit an existing task (identified by `{task_id}`).
*   `POST /task/update/{task_id}` -> `TaskController@update`
    *   **Description:** (Superuser only) Handles the submission of the task update form.
*   `GET /task/showAssignForm/{task_id}` -> `TaskController@showAssignForm`
    *   **Description:** (Superuser only) Displays the form to assign users to a specific task.
*   `POST /task/assign/{task_id}` -> `TaskController@assign`
    *   **Description:** (Superuser only) Handles the assignment of selected users to a task.
*   `GET /task/updateStatus/{task_id}/{new_status}/{context_id}/{redirect_context}` -> `TaskController@updateStatus`
    *   **Description:** (Superuser or Assigned User) Updates the status of a task (e.g., to 'completed', 'pending').
*   `GET /task/myTasks` -> `TaskController@myTasks`
    *   **Description:** Displays a list of tasks currently assigned to the logged-in user.
*   `GET /task/delete/{task_id}/{document_id_for_redirect}` -> `TaskController@delete`
    *   **Description:** (Superuser only) Handles the deletion of a task. Redirects back to the task list for the parent document.

### Comment/Feedback Management

*   `GET /comment/showByEntity/{entity_type}/{entity_id}` -> `CommentController@showByEntity`
    *   **Description:** Displays comments for a specific entity (`process`, `document`, or `task` identified by `{entity_type}` and `{entity_id}`) and a form to add new comments.
*   `POST /comment/create/{entity_type}/{entity_id}` -> `CommentController@create`
    *   **Description:** Handles the submission of a new comment for the specified entity.
*   `GET /comment/delete/{comment_id}/{entity_type_redirect}/{entity_id_redirect}` -> `CommentController@delete`
    *   **Description:** (Comment Owner or Superuser) Handles the deletion of a specific comment. Redirects back to the entity's comment view.

## II. Refinements & Future Considerations

This section lists potential improvements and future features for the Accreditation Management System, drawing from the original project report and standard web application development practices.

### Enhanced UI/UX
*   **CSS Framework:** Fully integrate a CSS framework like Bootstrap (as originally planned) for a responsive design, consistent styling, and access to pre-built UI components (modals, carousels, advanced form elements).
*   **JavaScript Interactivity:**
    *   Implement AJAX for form submissions (e.g., comments, status updates) to avoid full page reloads and provide a smoother experience.
    *   Use JavaScript for client-side validation to give immediate feedback to users, complementing server-side validation.
    *   Employ tools like SweetAlert for more engaging confirmation dialogs and notifications.
    *   Utilize libraries like Select2.js for improved user selection dropdowns, especially in task assignment.
*   **Improved Navigation:** Consider breadcrumbs for easier navigation within nested entities (Process -> Document -> Task).
*   **Rich Text Editing:** For descriptions and comments, a simple WYSIWYG editor could be beneficial.

### Advanced File Management
*   **Direct OneDrive Integration:** Move beyond storing URLs by using the Microsoft Graph API to:
    *   Browse and select files/folders from the user's OneDrive account directly within the AMS.
    *   Upload new document versions directly to OneDrive via the AMS interface.
    *   Potentially embed previews for common document types (Word, PDF, Excel) if the API supports it.
*   **Local File Storage (Optional):** As an alternative or fallback, allow direct file uploads to the server, if OneDrive integration is not always available or desired.
*   **Document Versioning:** If not inherently handled by OneDrive in a way that's accessible/manageable via API, consider a simple version history for uploaded documents.

### Notifications & Alerts
*   **Email Notifications:** Implement a robust email notification system (e.g., using PHPMailer or a transactional email service) for:
    *   New task assignments or mentions in comments.
    *   Updates to tasks the user is assigned to or has created.
    *   Reminders for approaching task deadlines.
    *   Notifications for new comments on processes, documents, or tasks a user is involved with.
*   **In-App Notifications:** A simple notification bell or panel within the AMS UI to alert users to recent relevant activities.

### Workflow & Status Automation
*   **Defined Workflows:** Introduce more structured, configurable workflows for accreditation processes, potentially with stages like 'Draft', 'Submitted for Review', 'Feedback Received', 'Approved', 'Archived'.
*   **Automated Status Updates:**
    *   Automatically mark processes as 'Overdue' if end date passes and status is not 'Completed'.
    *   Change task status to 'Overdue' if due date passes and not 'Completed'.
    *   Potentially, update document status based on the collective status of its tasks.

### Reporting & Analytics
*   **Process Progress Reports:** Generate reports showing the overall status of accreditation processes, percentage completion (based on tasks), and bottlenecks.
*   **Task Management Reports:** Track task completion rates, overdue tasks, and user workloads.
*   **Audit Trails:** Log significant actions (e.g., process creation, status changes, document deletion) for auditing and history tracking.
*   **Visual Dashboards:** Enhance the dashboard with charts and key performance indicators (KPIs).

### Security Enhancements
*   **CSRF Protection:** Implement CSRF tokens on all state-changing forms (POST, PUT, DELETE requests) to prevent cross-site request forgery.
*   **Input Validation & Output Encoding:** While `htmlspecialchars` is used, adopt a more systematic approach. Use prepared statements for all DB queries (already done with PDO). Validate all incoming data (types, lengths, formats) rigorously on the server-side. Ensure consistent output encoding.
*   **Content Security Policy (CSP):** Implement CSP headers to mitigate XSS and data injection attacks.
*   **HTTP Security Headers:** Add other security headers like HSTS, X-Frame-Options, X-Content-Type-Options.
*   **Regular Security Audits:** Conduct periodic security reviews and consider using automated scanning tools.
*   **Two-Factor Authentication (2FA):** Offer 2FA for enhanced user account security.
*   **Session Management:** Review session security settings (e.g., cookie flags: HttpOnly, Secure; session timeout policies).

### Database & Performance
*   **Database Migrations:** Use a dedicated migration tool (e.g., Phinx, Doctrine Migrations) to manage database schema changes versionally, instead of relying on manual SQL script execution. This is crucial for team development and deployment.
*   **Query Optimization:** Analyze and optimize database queries, especially for frequently accessed lists and reports. Ensure proper indexing is in place for all foreign keys and commonly queried columns.
*   **Caching:** Implement server-side caching (e.g., APCu, Memcached, Redis) for frequently accessed but rarely changing data (like user roles, list of users for assignment).
*   **Logging:** More structured logging (e.g., using a library like Monolog) for different log levels and destinations.

### Code Quality & Maintainability
*   **Templating Engine:** Introduce a dedicated templating engine (e.g., Twig) to better separate presentation (HTML) from application logic (PHP), making views cleaner and more secure.
*   **Dependency Management:** Fully utilize Composer for managing PHP dependencies and for class autoloading (PSR-4 standard).
*   **Coding Standards:** Enforce PSR coding standards (e.g., PSR-12) using tools like PHP CodeSniffer.
*   **Testing:**
    *   Expand unit tests to cover all model methods and critical controller logic.
    *   Introduce integration tests to verify interactions between components.
    *   Consider browser/acceptance tests using tools like Selenium or Puppeteer for UAT automation.
*   **Configuration Management:** Centralize configuration (database credentials, email settings, API keys) and allow for environment-specific overrides (e.g., using `.env` files with a library like `vlucas/phpdotenv`).

### External Accreditation Body Integration
*   **GTEC Portal Features:** Explore requirements for integrating or providing data to external bodies like GTEC, as mentioned in the original project's future scope. This could involve specific report formats, data export capabilities, or secure API endpoints.

### User Experience Details (from original report)
*   **Modals:** Implement jQuery-based modals for actions like quick edits or confirmations, as suggested by Fig 4.18 in the original report.
*   **User Selection:** Use Select2.js for a better user experience when assigning tasks to multiple users, as suggested by Figs 4.19-4.23.
*   **AJAX for UI Updates:** Utilize AJAX more broadly to update parts of a page without full reloads (e.g., submitting comments, updating task status, assigning users).

### Data Integrity for Comments
*   **Orphaned Comments:** Develop a strategy for managing comments linked to deleted entities:
    *   **Soft Deletes:** Implement soft deletes for parent entities (processes, documents, tasks) so comments remain linked to "archived" items.
    *   **Cleanup Mechanism:** Create an administrative task or scheduled job to periodically remove comments whose `entity_id` no longer exists in the parent tables.
    *   **Deletion Prevention:** Prevent deletion of parent entities if they have associated comments, or provide an option to delete comments along with the entity.

### Accessibility
*   **WCAG Compliance:** Conduct a thorough review and make necessary adjustments to ensure the application meets Web Content Accessibility Guidelines (WCAG) standards for users with disabilities. This includes proper ARIA attributes, keyboard navigation, color contrast, etc.

### API Development
*   **RESTful API:** For future integrations or a decoupled frontend, develop a proper RESTful or GraphQL API for all functionalities.

This list provides a roadmap for evolving the AMS into a more feature-rich, robust, and user-friendly application.
