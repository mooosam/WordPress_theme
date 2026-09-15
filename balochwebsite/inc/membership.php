<?php
/**
 * Baloch Heritage — Membership System
 * Registration, login, application workflow
 */
defined('ABSPATH') || exit;

/* ═══════════════════════════════════════════════════════
   REGISTRATION — New Member Application
   ═══════════════════════════════════════════════════════ */
add_action('wp_ajax_nopriv_bh_register', 'bh_handle_registration');
function bh_handle_registration() {
    check_ajax_referer('bh_nonce', 'nonce');

    $first   = sanitize_text_field($_POST['first_name']  ?? '');
    $last    = sanitize_text_field($_POST['last_name']   ?? '');
    $email   = sanitize_email($_POST['email']            ?? '');
    $pass    = $_POST['password']                        ?? '';
    $city    = sanitize_text_field($_POST['city']        ?? '');
    $tribe   = sanitize_text_field($_POST['tribe']       ?? '');
    $source  = sanitize_text_field($_POST['source']      ?? '');
    $intro   = sanitize_textarea_field($_POST['intro']   ?? '');
    $agree   = (bool)($_POST['agree_terms']              ?? false);

    // Validation
    $errors = [];
    if (!$first || !$last)  $errors[] = __('Full name is required.', 'baloch-heritage');
    if (!is_email($email))  $errors[] = __('A valid email address is required.', 'baloch-heritage');
    if (strlen($pass) < 8)  $errors[] = __('Password must be at least 8 characters.', 'baloch-heritage');
    if (!$city)             $errors[] = __('City is required.', 'baloch-heritage');
    if (!$agree)            $errors[] = __('You must agree to the Community Guidelines.', 'baloch-heritage');
    if (email_exists($email)) $errors[] = __('An account with this email already exists.', 'baloch-heritage');

    if ($errors) {
        wp_send_json_error(['message' => implode(' ', $errors)]);
    }

    // Create user
    $username = sanitize_user(strtolower($first . '.' . $last . rand(10,99)));
    // Ensure unique username
    while (username_exists($username)) $username .= rand(1,9);

    $user_id = wp_create_user($username, $pass, $email);
    if (is_wp_error($user_id)) {
        wp_send_json_error(['message' => $user_id->get_error_message()]);
    }

    // Set role to pending
    $user = new WP_User($user_id);
    $user->set_role('pending_member');

    // Save meta
    wp_update_user(['ID'=>$user_id, 'display_name'=>"{$first} {$last}", 'first_name'=>$first, 'last_name'=>$last]);
    update_user_meta($user_id, 'bh_city',          $city);
    update_user_meta($user_id, 'bh_tribe',         $tribe);
    update_user_meta($user_id, 'bh_source',        $source);
    update_user_meta($user_id, 'bh_intro',         $intro);
    update_user_meta($user_id, 'bh_member_status', 'pending');
    update_user_meta($user_id, 'bh_applied_date',  current_time('mysql'));

    // Notify applicant
    $app_subject = __('Application Received — Baloch Heritage Canada', 'baloch-heritage');
    $app_body = sprintf(
        __("Dear %s,\n\nThank you for applying to join the Baloch Cultural Society of Canada!\n\nYour application is now under review by our community team. You will receive an email notification within 1–3 business days once a decision has been made.\n\nWith pride and solidarity,\nBaloch Heritage Canada\ninfo@balochheritage.org", 'baloch-heritage'),
        "{$first} {$last}"
    );
    wp_mail($email, $app_subject, $app_body);

    // Notify approvers
    bh_notify_approvers_of_new_application($user_id, "{$first} {$last}", $email, $city);

    wp_send_json_success([
        'message' => __('Application submitted! Please check your email for confirmation. You will be notified once approved.', 'baloch-heritage'),
        'redirect' => home_url('/members/')
    ]);
}


/* ═══════════════════════════════════════════════════════
   LOGIN
   ═══════════════════════════════════════════════════════ */
add_action('wp_ajax_nopriv_bh_login', 'bh_handle_login');
function bh_handle_login() {
    check_ajax_referer('bh_nonce', 'nonce');

    $email = sanitize_email($_POST['email']    ?? '');
    $pass  = $_POST['password']                ?? '';
    $remember = (bool)($_POST['remember']      ?? false);

    if (!$email || !$pass) {
        wp_send_json_error(['message' => __('Please enter your email and password.', 'baloch-heritage')]);
    }

    $user = get_user_by('email', $email);
    if (!$user || !wp_check_password($pass, $user->user_pass, $user->ID)) {
        // Rate limiting
        $attempts = (int)get_transient('bh_login_fail_' . md5($email));
        set_transient('bh_login_fail_' . md5($email), $attempts + 1, 15 * MINUTE_IN_SECONDS);
        if ($attempts >= 5) {
            wp_send_json_error(['message' => __('Too many failed attempts. Please try again in 15 minutes.', 'baloch-heritage')]);
        }
        wp_send_json_error(['message' => __('Invalid email or password.', 'baloch-heritage')]);
    }

    // Clear failed attempts
    delete_transient('bh_login_fail_' . md5($email));

    // Log in
    wp_set_current_user($user->ID);
    wp_set_auth_cookie($user->ID, $remember);
    do_action('wp_login', $user->user_login, $user);

    $role = bh_get_role($user->ID);
    wp_send_json_success([
        'message'  => sprintf(__('Welcome back, %s!', 'baloch-heritage'), $user->first_name ?: $user->display_name),
        'role'     => $role,
        'status'   => get_user_meta($user->ID, 'bh_member_status', true),
        'redirect' => home_url('/members/')
    ]);
}


/* ═══════════════════════════════════════════════════════
   LOGOUT
   ═══════════════════════════════════════════════════════ */
add_action('wp_ajax_bh_logout', 'bh_handle_logout');
function bh_handle_logout() {
    check_ajax_referer('bh_nonce', 'nonce');
    wp_logout();
    wp_send_json_success(['redirect' => home_url('/')]);
}


/* ═══════════════════════════════════════════════════════
   GET MEMBER DATA (for frontend portal)
   ═══════════════════════════════════════════════════════ */
add_action('wp_ajax_bh_get_member_data', 'bh_ajax_get_member_data');
function bh_ajax_get_member_data() {
    check_ajax_referer('bh_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error(['message'=>__('Not logged in.','baloch-heritage')]);

    $user_id = get_current_user_id();
    $user    = get_userdata($user_id);
    $role    = bh_get_role($user_id);

    $data = [
        'id'        => $user_id,
        'firstName' => get_user_meta($user_id, 'first_name', true) ?: explode(' ', $user->display_name)[0],
        'lastName'  => get_user_meta($user_id, 'last_name',  true) ?: (explode(' ', $user->display_name)[1] ?? ''),
        'email'     => $user->user_email,
        'city'      => get_user_meta($user_id, 'bh_city', true),
        'tribe'     => get_user_meta($user_id, 'bh_tribe', true),
        'role'      => $role,
        'status'    => get_user_meta($user_id, 'bh_member_status', true) ?: 'pending',
        'joinDate'  => $user->user_registered,
        'canApprove'=> current_user_can('bh_approve_members'),
        'isAdmin'   => current_user_can('administrator'),
        'isBoard'   => bh_has_role('bh_board'),
    ];

    wp_send_json_success($data);
}


/* ═══════════════════════════════════════════════════════
   GET MEMBERS LIST (moderator/admin)
   ═══════════════════════════════════════════════════════ */
add_action('wp_ajax_bh_get_members_list', 'bh_ajax_get_members_list');
function bh_ajax_get_members_list() {
    check_ajax_referer('bh_nonce', 'nonce');
    if (!current_user_can('list_users')) {
        wp_send_json_error(['message' => __('Permission denied.', 'baloch-heritage')]);
    }

    $role   = sanitize_text_field($_POST['role']   ?? '');
    $search = sanitize_text_field($_POST['search'] ?? '');
    $status = sanitize_text_field($_POST['status'] ?? '');

    $args = ['number' => 100, 'orderby' => 'registered', 'order' => 'DESC'];
    if ($role)   $args['role']   = $role;
    if ($search) $args['search'] = "*{$search}*";

    $users = get_users($args);

    $list = array_map(function($u) {
        return [
            'id'        => $u->ID,
            'firstName' => get_user_meta($u->ID, 'first_name', true) ?: explode(' ',$u->display_name)[0],
            'lastName'  => get_user_meta($u->ID, 'last_name',  true) ?: (explode(' ',$u->display_name)[1]??''),
            'email'     => $u->user_email,
            'city'      => get_user_meta($u->ID, 'bh_city', true),
            'role'      => $u->roles[0] ?? '',
            'status'    => get_user_meta($u->ID, 'bh_member_status', true) ?: 'pending',
            'joinDate'  => $u->user_registered,
        ];
    }, $users);

    if ($status) {
        $list = array_values(array_filter($list, fn($m) => $m['status'] === $status));
    }

    wp_send_json_success(['members' => $list, 'total' => count($list)]);
}


/* ═══════════════════════════════════════════════════════
   UPDATE PROFILE
   ═══════════════════════════════════════════════════════ */
add_action('wp_ajax_bh_update_profile', 'bh_ajax_update_profile');
function bh_ajax_update_profile() {
    check_ajax_referer('bh_nonce', 'nonce');
    if (!is_user_logged_in()) wp_send_json_error(['message'=>__('Not logged in.','baloch-heritage')]);

    $user_id   = get_current_user_id();
    $first     = sanitize_text_field($_POST['first_name'] ?? '');
    $last      = sanitize_text_field($_POST['last_name']  ?? '');
    $city      = sanitize_text_field($_POST['city']       ?? '');
    $new_pass  = $_POST['new_password'] ?? '';

    $update = ['ID' => $user_id];
    if ($first) { $update['first_name'] = $first; update_user_meta($user_id, 'first_name', $first); }
    if ($last)  { $update['last_name']  = $last;  update_user_meta($user_id, 'last_name',  $last); }
    if ($first || $last) $update['display_name'] = trim("{$first} {$last}");
    if ($city)  update_user_meta($user_id, 'bh_city', $city);

    if ($new_pass && strlen($new_pass) >= 8) {
        $update['user_pass'] = $new_pass;
    }

    $result = wp_update_user($update);
    if (is_wp_error($result)) {
        wp_send_json_error(['message' => $result->get_error_message()]);
    }
    wp_send_json_success(['message' => __('Profile updated successfully!', 'baloch-heritage')]);
}


/* ═══════════════════════════════════════════════════════
   NOTIFY APPROVERS
   ═══════════════════════════════════════════════════════ */
function bh_notify_approvers_of_new_application($applicant_id, $name, $email, $city) {
    $approvers = get_users(['role__in' => ['bh_moderator', 'bh_board', 'administrator'], 'number' => 20]);
    if (!$approvers) return;

    $admin_url = admin_url('admin.php?page=bh-members');
    $subject   = __('[Baloch Heritage] New Membership Application', 'baloch-heritage');
    $body      = sprintf(
        __("A new membership application has been submitted.\n\nApplicant: %s\nEmail: %s\nCity: %s\n\nReview and approve or reject at:\n%s\n\n— Baloch Heritage Canada", 'baloch-heritage'),
        $name, $email, $city, $admin_url
    );

    foreach ($approvers as $approver) {
        wp_mail($approver->user_email, $subject, $body);
    }
}


/* ═══════════════════════════════════════════════════════
   PASSWORD RESET
   ═══════════════════════════════════════════════════════ */
add_action('wp_ajax_nopriv_bh_password_reset', 'bh_ajax_password_reset');
function bh_ajax_password_reset() {
    check_ajax_referer('bh_nonce', 'nonce');
    $email = sanitize_email($_POST['email'] ?? '');
    if (!$email || !email_exists($email)) {
        // Don't reveal if email exists
        wp_send_json_success(['message' => __('If that email is registered, you will receive a reset link shortly.', 'baloch-heritage')]);
    }
    $user = get_user_by('email', $email);
    retrieve_password($user->user_login);
    wp_send_json_success(['message' => __('If that email is registered, you will receive a reset link shortly.', 'baloch-heritage')]);
}


/* ═══════════════════════════════════════════════════════
   MEMBER STATS (for admin dashboard widget)
   ═══════════════════════════════════════════════════════ */
add_action('wp_dashboard_setup', 'bh_dashboard_widget');
function bh_dashboard_widget() {
    wp_add_dashboard_widget('bh_member_stats', __('Baloch Heritage — Member Stats', 'baloch-heritage'), 'bh_render_dashboard_widget');
}
function bh_render_dashboard_widget() {
    $pending  = count(get_users(['role' => 'pending_member']));
    $members  = count(get_users(['role' => 'bh_member']));
    $mods     = count(get_users(['role' => 'bh_moderator']));
    $board    = count(get_users(['role' => 'bh_board']));
    $total    = $pending + $members + $mods + $board;
    echo "<table style='width:100%;'>";
    echo "<tr><td><strong>Pending Approval</strong></td><td style='color:orange;font-weight:bold;'>{$pending}</td></tr>";
    echo "<tr><td><strong>Active Members</strong></td><td style='color:green;font-weight:bold;'>{$members}</td></tr>";
    echo "<tr><td><strong>Moderators</strong></td><td>{$mods}</td></tr>";
    echo "<tr><td><strong>Board Members</strong></td><td>{$board}</td></tr>";
    echo "<tr><td><strong>Total Users</strong></td><td><strong>{$total}</strong></td></tr>";
    echo "</table>";
    if ($pending > 0) {
        echo "<p><a href='" . admin_url('admin.php?page=bh-members') . "' class='button button-primary'>" . sprintf(__('Review %d pending application(s)', 'baloch-heritage'), $pending) . "</a></p>";
    }
}
