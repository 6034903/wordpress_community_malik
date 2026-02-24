/**
 * Contact Submissions Public JavaScript
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Initialize status checker
        initStatusChecker();
    });

    /**
     * Initialize submission status checker
     */
    function initStatusChecker() {
        var $form = $('#submission-status-form');
        var $results = $('#status-results');
        var $formContainer = $('#status-checker-form');

        if (!$form.length) {
            return;
        }

        $form.on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            formData.append('action', 'check_submission_status');
            formData.append('nonce', contactSubmissionsPublic.nonce);

            // Show loading state
            $form.find('button[type="submit"]').prop('disabled', true).text('Checking...');

            $.ajax({
                url: contactSubmissionsPublic.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    console.log('Status check response:', response);

                    if (response.success) {
                        displayResults(response.data);
                        $formContainer.hide();
                    } else {
                        showError(response.data || 'An error occurred while checking status.');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Status check AJAX error:', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText,
                        error: error
                    });
                    showError('Error checking status. Please try again.');
                },
                complete: function() {
                    $form.find('button[type="submit"]').prop('disabled', false).text('Check Status');
                }
            });
        });

        // Handle back to form button
        $(document).on('click', '.back-to-form', function(e) {
            e.preventDefault();
            $results.hide();
            $formContainer.show();
            $results.empty();
        });
    }

    /**
     * Display submission results
     */
    function displayResults(data) {
        var $results = $('#status-results');
        var html = '';

        html += '<h4>' + (data.count === 1 ? 'Your Submission' : 'Your Submissions (' + data.count + ')') + '</h4>';

        data.submissions.forEach(function(submission) {
            var statusClass = 'status-' + submission.status;
            var statusIcon = getStatusIcon(submission.status);

            html += '<div class="submission-item ' + statusClass + '">';
            html += '<div class="submission-header">';
            html += '<h5>#' + submission.id + ' - ' + submission.subject + '</h5>';
            html += '<div class="submission-meta">';
            html += '<span class="status-indicator ' + statusClass + '">' + statusIcon + ' ' + submission.status_text + '</span>';
            html += '<span class="submission-date">' + submission.submitted_at + '</span>';
            html += '</div>';
            html += '</div>';

            html += '<div class="submission-details">';
            html += '<p><strong>Name:</strong> ' + submission.name + '</p>';
            html += '<p><strong>Subject:</strong> ' + submission.subject + '</p>';
            html += '<p><strong>Status:</strong> <span class="' + statusClass + '">' + submission.status_text + '</span></p>';
            html += '<p><strong>Submitted:</strong> ' + submission.submitted_at + '</p>';

            if (submission.replied_at) {
                html += '<p><strong>Replied:</strong> ' + submission.replied_at + '</p>';
                html += '<div class="reply-notice">';
                html += '<p><strong>✅ Admin has replied to your submission!</strong></p>';
                html += '<p>You should have received an email with the admin\'s response. If you haven\'t received it, please check your spam folder.</p>';
                html += '</div>';
            } else {
                html += '<div class="no-reply-notice">';
                html += '<p><strong>⏳ Admin has not yet replied to your submission.</strong></p>';
                html += '<p>Please check back later or contact us directly if you need immediate assistance.</p>';
                html += '</div>';
            }

            html += '</div>';
            html += '</div>';
        });

        html += '<div class="results-actions">';
        html += '<button class="back-to-form button">Check Another Submission</button>';
        html += '</div>';

        $results.html(html).show();
    }

    /**
     * Show error message
     */
    function showError(message) {
        var $results = $('#status-results');
        var html = '<div class="status-error">';
        html += '<h4>Error</h4>';
        html += '<p>' + message + '</p>';
        html += '<button class="back-to-form button">Try Again</button>';
        html += '</div>';

        $results.html(html).show();
    }

    /**
     * Get status icon
     */
    function getStatusIcon(status) {
        var icons = {
            'unread': '📧',
            'read': '👁️',
            'replied': '✅'
        };
        return icons[status] || '❓';
    }

})(jQuery);
