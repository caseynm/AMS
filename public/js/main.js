document.addEventListener('DOMContentLoaded', function() {
    // Event Delegation for Delete Confirmations
    document.body.addEventListener('click', function(event) {
        const link = event.target.closest('.delete-confirm-link');
        if (link) {
            event.preventDefault();
            const deleteUrl = link.dataset.href;
            const message = link.dataset.message || 'Are you sure you want to delete this item? This action cannot be undone.'; // Default message

            Swal.fire({
                title: 'Confirm Deletion',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = deleteUrl;
                }
            });
        }
    });

    // Logic for Displaying Toasts from URL Parameters
    const params = new URLSearchParams(window.location.search);
    const successMessage = params.get('success');
    const errorMessage = params.get('error');

    if (successMessage) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: decodeURIComponent(successMessage.replace(/_/g, ' ')), // Replace underscores with spaces
            showConfirmButton: false,
            timer: 3500, // Slightly longer for success
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    }

    if (errorMessage) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: decodeURIComponent(errorMessage.replace(/_/g, ' ')), // Replace underscores with spaces
            showConfirmButton: false,
            timer: 5000, // Errors stay a bit longer
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer);
                toast.addEventListener('mouseleave', Swal.resumeTimer);
            }
        });
    }

    // Optional: Clean the URL parameters after displaying the toast
    if (successMessage || errorMessage) {
       if (window.history.replaceState) {
           let cleanURL = window.location.pathname;
           let searchParams = new URLSearchParams(window.location.search);
           searchParams.delete('success');
           searchParams.delete('error');
           if (searchParams.toString()) {
               cleanURL += '?' + searchParams.toString();
           }
           window.history.replaceState({ path: cleanURL }, '', cleanURL);
       }
    }

    // Initialize Select2 for user assignment if the element exists
    if (document.getElementById('select-users-assign')) {
        if (window.jQuery && window.jQuery.fn.select2) {
            $('#select-users-assign').select2({
                placeholder: 'Select users to assign',
                allowClear: true,
                width: '100%' // Ensure it takes available width
            });
            console.log("Select2 initialized for user assignment.");
        } else {
            console.error('jQuery or Select2 not loaded for task assignment select.');
        }
    }
    console.log("AMS SweetAlert JavaScript loaded and initialized.");

    // AJAX for Task Status Updates
    document.querySelectorAll('.ajax-update-task-status').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();

            const taskId = this.dataset.taskId;
            const newStatus = this.dataset.newStatus;
            const contextId = this.dataset.contextId;
            const redirectContext = this.dataset.redirectContext;
            // APP_BASE_URL is available globally in views due to BaseController::renderView,
            // but in JS, we need to ensure it's accessible, e.g. by setting it as a JS global variable in a script tag in the layout.
            // For now, let's assume APP_BASE_URL is set as a global JS var or construct relatively.
            // A robust way: get it from a data attribute on a main element or via a global JS var.
            // Let's assume a global var 'appBaseUrl' is set in a script tag in the main layout for JS to use.
            // If not, this part needs adjustment or the variable needs to be passed via data-attribute.
            // Fallback if not defined:
            const appBaseUrl = (typeof window.APP_BASE_URL !== 'undefined') ? window.APP_BASE_URL : '/';


            const ajaxUrl = appBaseUrl + `task/updateStatus/${taskId}/${newStatus}/${contextId}/${redirectContext}`;

            fetch(ajaxUrl, {
                method: 'GET', // Or POST, ensure controller matches
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    // Try to parse error response for a message, otherwise generic error
                    return response.json().then(errData => {
                        throw new Error(errData.message || `HTTP error! status: ${response.status}`);
                    }).catch(() => { // If no JSON error message, throw generic
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message || 'Status updated!',
                        showConfirmButton: false,
                        timer: 2000
                    });

                    // Update UI
                    const statusTextElement = document.getElementById(`task-status-${taskId}`);
                    if (statusTextElement) {
                        statusTextElement.textContent = data.new_status;
                    }

                    // This is a simplified toggle logic. More complex UI might need more.
                    // It assumes there are pairs of links or the same link needs its text and data attributes updated.
                    // For example, if we have separate "Mark Completed" and "Mark Pending" links, we'd hide one and show the other.
                    // For this example, let's update the clicked link itself if it's meant to be a toggle.
                    // This requires the link to have a unique ID, e.g., id="update-link-task-${taskId}-${currentStatus}"
                    // Or more simply, find all related status links for that task and update them.

                    // Example: If link text needs to change (e.g. from "Mark Completed" to "Mark Pending")
                    // This requires a more specific selector or way to identify the link.
                    // For now, we'll just update the status text. UI refresh on error or manual refresh might be needed for link text.
                    // A better way: have specific IDs for each action link and update them accordingly.
                    // Let's assume for now the page will be reloaded or UI updated by more specific means if complex changes are needed.
                    // Simple update for the current link if it's a toggle:
                    if (this.dataset.newStatus === 'completed') {
                        this.textContent = 'Mark Pending';
                        this.dataset.newStatus = 'pending';
                    } else if (this.dataset.newStatus === 'pending') {
                        this.textContent = 'Mark Completed';
                        this.dataset.newStatus = 'completed';
                    } else if (this.dataset.newStatus === 'in_progress') {
                        // Could change to "Mark Completed" or "Mark Pending"
                        // This part of UI update is complex and depends on specific view structure.
                        // For now, primary update is statusTextElement.
                    }


                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: data.message || 'Failed to update status.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            })
            .catch(error => {
                console.error('Error updating task status:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.message || 'Something went wrong with the request!'
                });
            });
        });
    });

    // AJAX for Comment Submission
    const commentForm = document.getElementById('ajax-comment-form');
    if (commentForm) {
        commentForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData(commentForm);
            // const commentText = formData.get('comment_text'); // Already part of formData
            const formActionUrl = commentForm.getAttribute('action');
            const appBaseUrl = (typeof window.APP_BASE_URL !== 'undefined') ? window.APP_BASE_URL : '/';

            fetch(formActionUrl, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                // Check if response is JSON, otherwise handle as error
                const contentType = response.headers.get("content-type");
                if (contentType && contentType.indexOf("application/json") !== -1) {
                    return response.json();
                } else {
                    return response.text().then(text => {
                        throw new Error("Server returned non-JSON response: " + text.substring(0, 200) + "...");
                    });
                }
            })
            .then(data => {
                if (data.success && data.comment) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message || 'Comment added!',
                        showConfirmButton: false,
                        timer: 2000
                    });

                    const commentsList = document.getElementById('comments-list-container'); // Ensure this ID is on the UL
                    if (commentsList) {
                        // Note: The delete link might need to be re-initialized for SweetAlert if not using event delegation for .delete-confirm-link
                        const newCommentHtml = `
                            <li>
                                <strong>${data.comment.user_name}</strong>
                                <em>(${data.comment.created_at})</em>:
                                <p>${data.comment.comment_text.replace(/\n/g, '<br>')}</p>
                                <a href="#" data-href="${appBaseUrl}comment/delete/${data.comment.id}/${data.comment.entity_type}/${data.comment.entity_id}"
                                   data-message="Are you sure you want to delete this comment?"
                                   class="delete-confirm-link">Delete Comment</a>
                            </li><br>`;
                        // Decide whether to prepend (newest first) or append
                        // If current view shows oldest first (ASC), then append. If newest first (DESC), then prepend.
                        // The current view for comments is ASC, so append.
                        commentsList.insertAdjacentHTML('beforeend', newCommentHtml);
                    }
                    commentForm.reset();
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: data.message || 'Failed to add comment.',
                        showConfirmButton: false,
                        timer: 3000
                    });
                }
            })
            .catch(error => {
                console.error('Error adding comment:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: error.message || 'Something went wrong with the request!'
                });
            });
        });
    }
});
