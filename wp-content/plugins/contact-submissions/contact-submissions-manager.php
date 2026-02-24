<?php
/**
 * Plugin Name: Contact Submissions Manager
 * Plugin URI: https://softwaresyndicate.com
 * Description: A custom plugin to manage contact form submissions in the admin dashboard. View, reply, and manage all contact form submissions.
 * Version: 1.0.0
 * Author: The Software Syndicate
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: contact-submissions
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('CONTACT_SUBMISSIONS_VERSION', '1.0.0');
define('CONTACT_SUBMISSIONS_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('CONTACT_SUBMISSIONS_PLUGIN_URL', plugin_dir_url(__FILE__));
define('CONTACT_SUBMISSIONS_TABLE', 'contact_submissions');

/**
 * Main Plugin Class
 */
class Contact_Submissions_Manager {

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));

        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

        // Handle form submissions
        add_action('admin_post_submit_contact_form', array($this, 'handle_contact_form'));

        // Handle public status check AJAX
        add_action('wp_ajax_nopriv_check_submission_status', array($this, 'ajax_check_submission_status'));
        add_action('wp_ajax_check_submission_status', array($this, 'ajax_check_submission_status'));

        // Add shortcode
        add_shortcode('contact_submission_status', array($this, 'submission_status_shortcode'));
    }

    /**
     * Plugin activation
     */
    public function activate() {
        $this->create_database_table();
        flush_rewrite_rules();
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        flush_rewrite_rules();
    }

    /**
     * Create database table for submissions
     */
    private function create_database_table() {
        global $wpdb;

        $table_name = $wpdb->prefix . CONTACT_SUBMISSIONS_TABLE;
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(100) NOT NULL,
            email varchar(100) NOT NULL,
            phone varchar(50) DEFAULT '' NOT NULL,
            company varchar(100) DEFAULT '' NOT NULL,
            referral varchar(255) DEFAULT '' NOT NULL,
            subject varchar(255) NOT NULL,
            message text NOT NULL,
            status enum('unread','read','replied') DEFAULT 'unread' NOT NULL,
            submitted_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            replied_at datetime NULL,
            reply_message text NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // Store database version
        add_option('contact_submissions_db_version', CONTACT_SUBMISSIONS_VERSION);
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Contact Submissions', 'contact-submissions'),
            __('Contact Forms', 'contact-submissions'),
            'manage_options',
            'contact-submissions',
            array($this, 'admin_page'),
            'dashicons-email-alt',
            30
        );

        add_submenu_page(
            'contact-submissions',
            __('All Submissions', 'contact-submissions'),
            __('All Submissions', 'contact-submissions'),
            'manage_options',
            'contact-submissions',
            array($this, 'admin_page')
        );

        add_submenu_page(
            'contact-submissions',
            __('Settings', 'contact-submissions'),
            __('Settings', 'contact-submissions'),
            'manage_options',
            'contact-submissions-settings',
            array($this, 'settings_page')
        );
    }

    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        if (strpos($hook, 'contact-submissions') === false) {
            return;
        }

        wp_enqueue_style(
            'contact-submissions-admin',
            CONTACT_SUBMISSIONS_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            CONTACT_SUBMISSIONS_VERSION
        );

        wp_enqueue_script(
            'contact-submissions-admin',
            CONTACT_SUBMISSIONS_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery'),
            CONTACT_SUBMISSIONS_VERSION,
            true
        );

        wp_localize_script('contact-submissions-admin', 'contactSubmissionsAjax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('contact_submissions_nonce'),
        ));
    }

    /**
     * Handle contact form submission
     */
    public function handle_contact_form() {
        if (!isset($_POST['action']) || $_POST['action'] !== 'submit_contact_form') {
            return;
        }

        if (!wp_verify_nonce($_POST['contact_nonce'], 'contact_form_nonce')) {
            wp_die(__('Security check failed.', 'contact-submissions'));
        }

        $name = sanitize_text_field($_POST['contact_name']);
        $email = sanitize_email($_POST['contact_email']);
        $subject = sanitize_text_field($_POST['contact_subject']);
        $phone = sanitize_text_field($_POST['contact_phone']);
        $company = sanitize_text_field($_POST['contact_company']);
        $referral = sanitize_text_field($_POST['contact_referral']);
        $message = sanitize_textarea_field($_POST['contact_message']);

        // Validation
        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            wp_redirect(add_query_arg('contact', 'error', wp_get_referer()));
            exit;
        }

        if (!is_email($email)) {
            wp_redirect(add_query_arg('contact', 'invalid-email', wp_get_referer()));
            exit;
        }

        // Save to database
        $this->save_submission($name, $email, $phone, $company, $referral, $subject, $message);

        // Send email notification to admin
        $this->send_admin_notification($name, $email, $subject, $message);

        wp_redirect(add_query_arg('contact', 'success', wp_get_referer()));
        exit;
    }

    /**
     * Save submission to database
     */
    private function save_submission($name, $email, $phone, $company, $referral, $subject, $message) {
        global $wpdb;

        $table_name = $wpdb->prefix . CONTACT_SUBMISSIONS_TABLE;

        $wpdb->insert(
            $table_name,
            array(
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'company' => $company,
                'referral' => $referral,
                'subject' => $subject,
                'message' => $message,
                'status' => 'unread',
                'submitted_at' => current_time('mysql'),
            ),
            array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
        );
    }

    /**
     * Send admin notification email
     */
    private function send_admin_notification($name, $email, $subject, $message) {
        $to = get_option('admin_email');
        $email_subject = sprintf(__('New Contact Form Submission: %s', 'contact-submissions'), $subject);

        $email_body = sprintf(
            __('New contact form submission received:\n\nName: %s\nEmail: %s\nSubject: %s\n\nMessage:\n%s', 'contact-submissions'),
            $name,
            $email,
            $subject,
            $message
        );

        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . $to . '>',
            'Reply-To: ' . $email
        );

        wp_mail($to, $email_subject, $email_body, $headers);
    }

    /**
     * Admin page
     */
    public function admin_page() {
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : 'list';
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        switch ($action) {
            case 'view':
                $this->view_submission($id);
                break;
            default:
                $this->list_submissions();
                break;
        }
    }

    /**
     * List all submissions
     */
    private function list_submissions() {
        global $wpdb;

        $table_name = $wpdb->prefix . CONTACT_SUBMISSIONS_TABLE;

        // Handle bulk actions
        if (isset($_POST['bulk_action']) && isset($_POST['submission_ids'])) {
            $this->handle_bulk_actions();
        }

        // Pagination
        $per_page = 20;
        $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
        $offset = ($current_page - 1) * $per_page;

        // Get total submissions
        $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");

        // Get submissions
        $submissions = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $table_name ORDER BY submitted_at DESC LIMIT %d OFFSET %d",
                $per_page,
                $offset
            )
        );

        $total_pages = ceil($total_items / $per_page);

        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Contact Submissions', 'contact-submissions'); ?></h1>

            <form method="post">
                <div class="tablenav top">
                    <div class="alignleft actions bulkactions">
                        <label for="bulk-action-selector-top" class="screen-reader-text">
                            <?php esc_html_e('Select bulk action', 'contact-submissions'); ?>
                        </label>
                        <select name="bulk_action" id="bulk-action-selector-top">
                            <option value="-1"><?php esc_html_e('Bulk actions', 'contact-submissions'); ?></option>
                            <option value="mark_read"><?php esc_html_e('Mark as Read', 'contact-submissions'); ?></option>
                            <option value="delete"><?php esc_html_e('Delete', 'contact-submissions'); ?></option>
                        </select>
                        <input type="submit" class="button action" value="<?php esc_attr_e('Apply', 'contact-submissions'); ?>">
                    </div>

                    <div class="tablenav-pages">
                        <?php
                        echo paginate_links(array(
                            'base' => add_query_arg('paged', '%#%'),
                            'format' => '',
                            'prev_text' => __('&laquo;'),
                            'next_text' => __('&raquo;'),
                            'total' => $total_pages,
                            'current' => $current_page
                        ));
                        ?>
                    </div>
                </div>

                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <td id="cb" class="manage-column column-cb check-column">
                                <label class="screen-reader-text" for="cb-select-all-1">
                                    <?php esc_html_e('Select All', 'contact-submissions'); ?>
                                </label>
                                <input id="cb-select-all-1" type="checkbox">
                            </td>
                            <th><?php esc_html_e('Name', 'contact-submissions'); ?></th>
                            <th><?php esc_html_e('Email', 'contact-submissions'); ?></th>
                            <th><?php esc_html_e('Subject', 'contact-submissions'); ?></th>
                            <th><?php esc_html_e('Status', 'contact-submissions'); ?></th>
                            <th><?php esc_html_e('Date', 'contact-submissions'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($submissions)) : ?>
                            <tr>
                                <td colspan="6"><?php esc_html_e('No submissions found.', 'contact-submissions'); ?></td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($submissions as $submission) : ?>
                                <tr class="<?php echo $submission->status === 'unread' ? 'unread' : ''; ?>">
                                    <th scope="row" class="check-column">
                                        <input type="checkbox" name="submission_ids[]" value="<?php echo $submission->id; ?>">
                                    </th>
                                    <td>
                                        <strong>
                                            <a href="<?php echo add_query_arg(array('action' => 'view', 'id' => $submission->id)); ?>">
                                                <?php echo esc_html($submission->name); ?>
                                            </a>
                                        </strong>
                                        <?php if ($submission->status === 'unread') : ?>
                                            <span class="unread-indicator"><?php esc_html_e('Unread', 'contact-submissions'); ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo esc_html($submission->email); ?></td>
                                    <td><?php echo esc_html($submission->subject); ?></td>
                                    <td>
                                        <span class="status-<?php echo esc_attr($submission->status); ?>">
                                            <?php echo esc_html(ucfirst($submission->status)); ?>
                                        </span>
                                    </td>
                                    <td><?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($submission->submitted_at))); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </form>
        </div>
        <?php
    }

    /**
     * View single submission
     */
    private function view_submission($id) {
        global $wpdb;

        $table_name = $wpdb->prefix . CONTACT_SUBMISSIONS_TABLE;
        $submission = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id));

        if (!$submission) {
            wp_die(__('Submission not found.', 'contact-submissions'));
        }

        // Mark as read if unread
        if ($submission->status === 'unread') {
            $wpdb->update(
                $table_name,
                array('status' => 'read'),
                array('id' => $id),
                array('%s'),
                array('%d')
            );
            $submission->status = 'read';
        }

        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Contact Submission', 'contact-submissions'); ?></h1>

            <div class="submission-details">
                <div class="submission-header">
                    <h2><?php echo esc_html($submission->subject); ?></h2>
                    <div class="submission-meta">
                        <span class="status status-<?php echo esc_attr($submission->status); ?>">
                            <?php echo esc_html(ucfirst($submission->status)); ?>
                        </span>
                        <span class="date">
                            <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($submission->submitted_at))); ?>
                        </span>
                    </div>
                </div>

                <div class="submission-content">
                    <div class="contact-info">
                        <h3><?php esc_html_e('Contact Information', 'contact-submissions'); ?></h3>
                        <table class="form-table">
                            <tr>
                                <th><?php esc_html_e('Name', 'contact-submissions'); ?>:</th>
                                <td><?php echo esc_html($submission->name); ?></td>
                            </tr>
                            <tr>
                                <th><?php esc_html_e('Email', 'contact-submissions'); ?>:</th>
                                <td><a href="mailto:<?php echo esc_attr($submission->email); ?>"><?php echo esc_html($submission->email); ?></a></td>
                            </tr>
                            <?php if (!empty($submission->phone)) : ?>
                            <tr>
                                <th><?php esc_html_e('Phone', 'contact-submissions'); ?>:</th>
                                <td><?php echo esc_html($submission->phone); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($submission->company)) : ?>
                            <tr>
                                <th><?php esc_html_e('Company', 'contact-submissions'); ?>:</th>
                                <td><?php echo esc_html($submission->company); ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if (!empty($submission->referral)) : ?>
                            <tr>
                                <th><?php esc_html_e('How did you find us?', 'contact-submissions'); ?>:</th>
                                <td><?php echo esc_html($submission->referral); ?></td>
                            </tr>
                            <?php endif; ?>
                        </table>
                    </div>

                    <div class="message-content">
                        <h3><?php esc_html_e('Message', 'contact-submissions'); ?></h3>
                        <div class="message-text">
                            <?php echo nl2br(esc_html($submission->message)); ?>
                        </div>
                    </div>

                    <?php if (!empty($submission->reply_message)) : ?>
                    <div class="reply-content">
                        <h3><?php esc_html_e('Your Reply', 'contact-submissions'); ?></h3>
                        <div class="reply-text">
                            <?php echo nl2br(esc_html($submission->reply_message)); ?>
                        </div>
                        <p class="reply-date">
                            <?php esc_html_e('Replied on', 'contact-submissions'); ?>:
                            <?php echo esc_html(date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($submission->replied_at))); ?>
                        </p>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="submission-actions">
                    <a href="<?php echo remove_query_arg(array('action', 'id')); ?>" class="button"><?php esc_html_e('Back to List', 'contact-submissions'); ?></a>
                    <button type="button" class="button button-primary" id="reply-button"><?php esc_html_e('Reply', 'contact-submissions'); ?></button>
                    <button type="button" class="button button-secondary" id="delete-button" data-id="<?php echo $submission->id; ?>"><?php esc_html_e('Delete', 'contact-submissions'); ?></button>
                </div>

                <!-- Reply Form (hidden by default) -->
                <div id="reply-form" style="display: none;">
                    <h3><?php esc_html_e('Reply to Submission', 'contact-submissions'); ?></h3>
                    <form id="reply-form-element">
                        <input type="hidden" name="submission_id" value="<?php echo $submission->id; ?>">
                        <table class="form-table">
                            <tr>
                                <th><label for="reply_subject"><?php esc_html_e('Subject', 'contact-submissions'); ?></label></th>
                                <td><input type="text" name="reply_subject" id="reply_subject" value="<?php echo esc_attr('Re: ' . $submission->subject); ?>" class="regular-text"></td>
                            </tr>
                            <tr>
                                <th><label for="reply_message"><?php esc_html_e('Message', 'contact-submissions'); ?></label></th>
                                <td><textarea name="reply_message" id="reply_message" rows="10" class="large-text"></textarea></td>
                            </tr>
                        </table>
                        <p class="submit">
                            <input type="submit" name="submit" id="submit-reply" class="button button-primary" value="<?php esc_attr_e('Send Reply', 'contact-submissions'); ?>">
                            <button type="button" class="button" id="cancel-reply"><?php esc_html_e('Cancel', 'contact-submissions'); ?></button>
                        </p>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Handle bulk actions
     */
    private function handle_bulk_actions() {
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'bulk-submissions')) {
            return;
        }

        $action = sanitize_text_field($_POST['bulk_action']);
        $ids = array_map('intval', $_POST['submission_ids']);

        if (empty($ids)) {
            return;
        }

        global $wpdb;
        $table_name = $wpdb->prefix . CONTACT_SUBMISSIONS_TABLE;

        switch ($action) {
            case 'mark_read':
                $wpdb->query(
                    "UPDATE $table_name SET status = 'read' WHERE id IN (" . implode(',', $ids) . ")"
                );
                break;
            case 'delete':
                $wpdb->query(
                    "DELETE FROM $table_name WHERE id IN (" . implode(',', $ids) . ")"
                );
                break;
        }

        wp_redirect(remove_query_arg(array('action', 'id')));
        exit;
    }

    /**
     * AJAX: Reply to submission
     */
    public function ajax_reply_to_submission() {
        // Log the request for debugging
        error_log('Contact Submissions: Reply request received');

        check_ajax_referer('contact_submissions_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            error_log('Contact Submissions: Insufficient permissions for user ' . get_current_user_id());
            wp_send_json_error(__('Insufficient permissions.', 'contact-submissions'));
        }

        $submission_id = intval($_POST['submission_id']);
        $reply_subject = sanitize_text_field($_POST['reply_subject']);
        $reply_message = sanitize_textarea_field($_POST['reply_message']);

        // Log the data
        error_log('Contact Submissions: Reply data - ID: ' . $submission_id . ', Subject: ' . $reply_subject);

        if (empty($reply_subject) || empty($reply_message)) {
            error_log('Contact Submissions: Empty subject or message');
            wp_send_json_error(__('Subject and message are required.', 'contact-submissions'));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . CONTACT_SUBMISSIONS_TABLE;

        // Get submission details
        $submission = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $submission_id));

        if (!$submission) {
            error_log('Contact Submissions: Submission not found - ID: ' . $submission_id);
            wp_send_json_error(__('Submission not found.', 'contact-submissions'));
        }

        // Check if we're in development mode (localhost) or email is disabled
        $is_development = (strpos(get_site_url(), 'localhost') !== false) || !get_option('contact_submissions_enable_notifications', '1');

        if ($is_development) {
            // In development, skip email sending and just save the reply
            error_log('Contact Submissions: Development mode - skipping email send');

            $result = $wpdb->update(
                $table_name,
                array(
                    'status' => 'replied',
                    'replied_at' => current_time('mysql'),
                    'reply_message' => $reply_message
                ),
                array('id' => $submission_id),
                array('%s', '%s', '%s'),
                array('%d')
            );

            if ($result !== false) {
                error_log('Contact Submissions: Reply saved successfully (no email sent)');
                wp_send_json_success(__('Reply saved successfully (email not sent in development mode).', 'contact-submissions'));
            } else {
                error_log('Contact Submissions: Database update failed - ' . $wpdb->last_error);
                wp_send_json_error(__('Failed to save reply.', 'contact-submissions'));
            }
            return;
        }

        // Log email details
        error_log('Contact Submissions: Sending email to: ' . $submission->email . ', From: ' . get_option('admin_email'));

        // Send reply email
        $headers = array(
            'Content-Type: text/plain; charset=UTF-8',
            'From: ' . get_bloginfo('name') . ' <' . get_option('admin_email') . '>',
            'Reply-To: ' . get_option('admin_email')
        );

        $sent = wp_mail($submission->email, $reply_subject, $reply_message, $headers);

        if ($sent) {
            error_log('Contact Submissions: Email sent successfully');

            // Update database
            $result = $wpdb->update(
                $table_name,
                array(
                    'status' => 'replied',
                    'replied_at' => current_time('mysql'),
                    'reply_message' => $reply_message
                ),
                array('id' => $submission_id),
                array('%s', '%s', '%s'),
                array('%d')
            );

            if ($result !== false) {
                error_log('Contact Submissions: Database updated successfully');
                wp_send_json_success(__('Reply sent successfully.', 'contact-submissions'));
            } else {
                error_log('Contact Submissions: Database update failed - ' . $wpdb->last_error);
                wp_send_json_error(__('Email sent but database update failed.', 'contact-submissions'));
            }
        } else {
            error_log('Contact Submissions: Email sending failed');
            wp_send_json_error(__('Failed to send reply email. Please check your email configuration.', 'contact-submissions'));
        }
    }

    /**
     * AJAX: Mark submission as read
     */
    public function ajax_mark_as_read() {
        check_ajax_referer('contact_submissions_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions.', 'contact-submissions'));
        }

        $id = intval($_POST['id']);

        global $wpdb;
        $table_name = $wpdb->prefix . CONTACT_SUBMISSIONS_TABLE;

        $result = $wpdb->update(
            $table_name,
            array('status' => 'read'),
            array('id' => $id),
            array('%s'),
            array('%d')
        );

        if ($result !== false) {
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    }

    /**
     * AJAX: Check submission status (public)
     */
    public function ajax_check_submission_status() {
        check_ajax_referer('contact_submissions_public_nonce', 'nonce');

        $email = sanitize_email($_POST['email']);

        if (empty($email)) {
            wp_send_json_error(__('Please provide an email address.', 'contact-submissions'));
        }

        if (!is_email($email)) {
            wp_send_json_error(__('Please enter a valid email address.', 'contact-submissions'));
        }

        global $wpdb;
        $table_name = $wpdb->prefix . CONTACT_SUBMISSIONS_TABLE;

        // Get submissions matching the email
        $submissions = $wpdb->get_results(
            $wpdb->prepare("SELECT id, name, subject, status, submitted_at, replied_at FROM $table_name WHERE email = %s ORDER BY submitted_at DESC", $email)
        );

        if (empty($submissions)) {
            wp_send_json_error(__('No submissions found with the provided email address.', 'contact-submissions'));
        }

        // Format the results
        $results = array();
        foreach ($submissions as $submission) {
            $results[] = array(
                'id' => $submission->id,
                'name' => $submission->name,
                'subject' => $submission->subject,
                'status' => $submission->status,
                'status_text' => $this->get_status_text($submission->status),
                'submitted_at' => date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($submission->submitted_at)),
                'replied_at' => $submission->replied_at ? date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($submission->replied_at)) : null,
            );
        }

        wp_send_json_success(array(
            'submissions' => $results,
            'count' => count($results)
        ));
    }

    /**
     * Get human-readable status text
     */
    private function get_status_text($status) {
        $statuses = array(
            'unread' => __('Unread', 'contact-submissions'),
            'read' => __('Read', 'contact-submissions'),
            'replied' => __('Replied', 'contact-submissions'),
        );

        return isset($statuses[$status]) ? $statuses[$status] : $status;
    }

    /**
     * Settings page
     */
    public function settings_page() {
        if (isset($_POST['submit'])) {
            // Handle settings save
            $this->save_settings();
        }

        ?>
        <div class="wrap">
            <h1><?php esc_html_e('Contact Submissions Settings', 'contact-submissions'); ?></h1>

            <form method="post" action="">
                <table class="form-table">
                    <tr>
                        <th scope="row"><?php esc_html_e('Email Notifications', 'contact-submissions'); ?></th>
                        <td>
                            <fieldset>
                                <legend class="screen-reader-text">
                                    <span><?php esc_html_e('Email Notifications', 'contact-submissions'); ?></span>
                                </legend>
                                <label for="enable_notifications">
                                    <input name="enable_notifications" type="checkbox" id="enable_notifications" value="1" <?php checked(get_option('contact_submissions_enable_notifications', '1')); ?>>
                                    <?php esc_html_e('Send email notifications for new submissions', 'contact-submissions'); ?>
                                </label>
                            </fieldset>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"><?php esc_html_e('Notification Email', 'contact-submissions'); ?></th>
                        <td>
                            <input name="notification_email" type="email" id="notification_email" value="<?php echo esc_attr(get_option('contact_submissions_notification_email', get_option('admin_email'))); ?>" class="regular-text">
                            <p class="description"><?php esc_html_e('Email address where notifications will be sent.', 'contact-submissions'); ?></p>
                        </td>
                    </tr>
                </table>

                <?php wp_nonce_field('contact_submissions_settings'); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Save settings
     */
    private function save_settings() {
        if (!wp_verify_nonce($_POST['_wpnonce'], 'contact_submissions_settings')) {
            return;
        }

        update_option('contact_submissions_enable_notifications', isset($_POST['enable_notifications']) ? '1' : '0');
        update_option('contact_submissions_notification_email', sanitize_email($_POST['notification_email']));
    }

    /**
     * Submission status shortcode
     */
    public function submission_status_shortcode($atts) {
        // Enqueue scripts for frontend
        wp_enqueue_script(
            'contact-submissions-public',
            CONTACT_SUBMISSIONS_PLUGIN_URL . 'assets/js/public.js',
            array('jquery'),
            CONTACT_SUBMISSIONS_VERSION,
            true
        );

        wp_enqueue_style(
            'contact-submissions-public',
            CONTACT_SUBMISSIONS_PLUGIN_URL . 'assets/css/public.css',
            array(),
            CONTACT_SUBMISSIONS_VERSION
        );

        wp_localize_script('contact-submissions-public', 'contactSubmissionsPublic', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('contact_submissions_public_nonce'),
        ));

        ob_start();
        ?>
        <div class="contact-submission-status">
            <h3><?php esc_html_e('Check Your Submission Status', 'contact-submissions'); ?></h3>

            <div id="status-checker-form">
                <p><?php esc_html_e('Enter your email address to check the status of your contact form submissions.', 'contact-submissions'); ?></p>

                <form id="submission-status-form">
                    <div class="form-group">
                        <label for="status_email"><?php esc_html_e('Email Address:', 'contact-submissions'); ?></label>
                        <input type="email" id="status_email" name="email" required placeholder="<?php esc_attr_e('your-email@example.com', 'contact-submissions'); ?>">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="status-check-btn"><?php esc_html_e('Check Status', 'contact-submissions'); ?></button>
                    </div>
                </form>
            </div>

            <div id="status-results" style="display: none;">
                <!-- Results will be loaded here via AJAX -->
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}

// Initialize the plugin
new Contact_Submissions_Manager();
