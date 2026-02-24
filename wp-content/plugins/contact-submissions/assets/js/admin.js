/**
 * Contact Submissions Admin JavaScript
 */
(function($) {
    'use strict';

    $(document).ready(function() {
        // Initialize
        initReplyForm();
        initBulkActions();
        initDeleteActions();

        /**
         * Initialize reply form functionality
         */
        function initReplyForm() {
            var $replyButton = $('#reply-button');
            var $replyForm = $('#reply-form');
            var $cancelButton = $('#cancel-reply');
            var $replyFormElement = $('#reply-form-element');

            // Show reply form
            $replyButton.on('click', function() {
                $replyForm.slideDown(300);
                $('#reply_message').focus();
            });

            // Hide reply form
            $cancelButton.on('click', function() {
                $replyForm.slideUp(300);
                $replyFormElement[0].reset();
            });

            // Handle reply form submission
            $replyFormElement.on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                formData.append('action', 'reply_to_submission');
                formData.append('nonce', contactSubmissionsAjax.nonce);

                // Show loading state
                $replyFormElement.addClass('submission-loading');
                var $submitButton = $('#submit-reply');
                var originalText = $submitButton.val();
                $submitButton.val(contactSubmissionsAjax.loading_text || 'Sending...').prop('disabled', true);

                $.ajax({
                    url: contactSubmissionsAjax.ajax_url,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        console.log('Reply AJAX response:', response);
                        if (response.success) {
                            // Show success message
                            showNotice(response.data, 'success');

                            // Hide form and reload page
                            $replyForm.slideUp(300);
                            setTimeout(function() {
                                location.reload();
                            }, 1500);
                        } else {
                            console.error('Reply failed:', response.data);
                            showNotice(response.data || 'Error sending reply.', 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Reply AJAX error:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            error: error
                        });
                        var errorMessage = 'Error sending reply. ';
                        if (xhr.status === 403) {
                            errorMessage += 'Permission denied. Please refresh the page and try again.';
                        } else if (xhr.status === 500) {
                            errorMessage += 'Server error occurred. Check the error logs.';
                        } else {
                            errorMessage += 'Please check the browser console for details.';
                        }
                        showNotice(errorMessage, 'error');
                    },
                    complete: function() {
                        // Remove loading state
                        $replyFormElement.removeClass('submission-loading');
                        $submitButton.val(originalText).prop('disabled', false);
                    }
                });
            });
        }

        /**
         * Initialize bulk actions
         */
        function initBulkActions() {
            // Handle bulk action form - exclude reply form
            $('form').not('#reply-form-element').on('submit', function(e) {
                if ($(this).find('select[name="bulk_action"]').val() === '-1') {
                    return true;
                }

                var checkedBoxes = $('input[name="submission_ids[]"]:checked');
                if (checkedBoxes.length === 0) {
                    alert('Please select items to perform this action on.');
                    e.preventDefault();
                    return false;
                }

                if ($(this).find('select[name="bulk_action"]').val() === 'delete') {
                    if (!confirm('Are you sure you want to delete the selected submissions? This action cannot be undone.')) {
                        e.preventDefault();
                        return false;
                    }
                }
            });

            // Select all checkbox
            $('#cb-select-all-1').on('change', function() {
                $('input[name="submission_ids[]"]').prop('checked', this.checked);
            });
        }

        /**
         * Initialize delete actions
         */
        function initDeleteActions() {
            $('#delete-button').on('click', function() {
                if (!confirm('Are you sure you want to delete this submission? This action cannot be undone.')) {
                    return;
                }

                var submissionId = $(this).data('id');

                $.ajax({
                    url: contactSubmissionsAjax.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'delete_submission',
                        nonce: contactSubmissionsAjax.nonce,
                        id: submissionId
                    },
                    success: function(response) {
                        if (response.success) {
                            showNotice('Submission deleted successfully.', 'success');
                            setTimeout(function() {
                                window.location.href = removeQueryArg('action', removeQueryArg('id', window.location.href));
                            }, 1000);
                        } else {
                            showNotice('Error deleting submission.', 'error');
                        }
                    },
                    error: function() {
                        showNotice('Error deleting submission.', 'error');
                    }
                });
            });
        }

        /**
         * Show admin notice
         */
        function showNotice(message, type) {
            // Remove existing notices
            $('.contact-notice').remove();

            var noticeClass = type === 'success' ? 'notice-success' : 'notice-error';

            var notice = $('<div class="notice ' + noticeClass + ' is-dismissible contact-notice"><p>' + message + '</p></div>');

            $('.wrap h1').after(notice);

            // Auto dismiss after 5 seconds
            setTimeout(function() {
                notice.fadeOut();
            }, 5000);
        }

        /**
         * Remove query argument from URL
         */
        function removeQueryArg(key, url) {
            var urlParts = url.split('?');
            if (urlParts.length >= 2) {
                var prefix = encodeURIComponent(key) + '=';
                var parts = urlParts[1].split(/[&;]/g);

                for (var i = parts.length; i-- > 0; ) {
                    if (parts[i].lastIndexOf(prefix, 0) !== -1) {
                        parts.splice(i, 1);
                    }
                }

                url = urlParts[0] + (parts.length > 0 ? '?' + parts.join('&') : '');
            }
            return url;
        }
    });

})(jQuery);
