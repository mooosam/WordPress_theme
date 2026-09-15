<?php
/**
 * Baloch Heritage — User Roles & Capabilities
 *
 * Role hierarchy (lowest → highest):
 *   pending_member → bh_member → bh_moderator → bh_board → administrator
 */
defined('ABSPATH') || exit;

/* ═══════════════════════════════════════════════════════
   REGISTER CUSTOM ROLES
   ═══════════════════════════════════════════════════════ */
function bh_register_roles() {

    // 1. Pending Member — submitted application, awaiting approval
    if (!get_role('pending_member')) {
        add_role('pending_member', __('Pending Member', 'baloch-heritage'), [
            'read'                  => true,
            'bh_pending_member'     => true,
        ]);
    }

    // 2. Member — approved community member
    if (!get_role('bh_member')) {
        add_role('bh_member', __('Member', 'baloch-heritage'), [
            'read'                  => true,
            'bh_member'             => true,
            'bh_rsvp_events'        => true,
            'bh_view_directory'     => true,
            'bh_post_forum'         => true,
            'bh_submit_article'     => true,
        ]);
    }

    // 3. Moderator — Level 1 approver, can approve new members
    if (!get_role('bh_moderator')) {
        add_role('bh_moderator', __('Moderator', 'baloch-heritage'), [
            'read'                  => true,
            'bh_member'             => true,
            'bh_moderator'          => true,
            'bh_rsvp_events'        => true,
            'bh_view_directory'     => true,
            'bh_post_forum'         => true,
            'bh_submit_article'     => true,
            'bh_approve_members'    => true,   // Level 1 approval
            'bh_reject_members'     => true,
            'bh_view_pending'       => true,
            'edit_posts'            => true,
            'publish_posts'         => true,
            'delete_posts'          => false,
            'moderate_comments'     => true,
        ]);
    }

    // 4. Board Member — Level 2 approver, governance access
    if (!get_role('bh_board')) {
        add_role('bh_board', __('Board Member', 'baloch-heritage'), [
            'read'                  => true,
            'bh_member'             => true,
            'bh_moderator'          => true,
            'bh_board_member'       => true,
            'bh_rsvp_events'        => true,
            'bh_view_directory'     => true,
            'bh_post_forum'         => true,
            'bh_submit_article'     => true,
            'bh_approve_members'    => true,   // Level 1 + Level 2
            'bh_reject_members'     => true,
            'bh_promote_members'    => true,   // Can promote to moderator
            'bh_view_pending'       => true,
            'bh_manage_events'      => true,
            'bh_manage_resources'   => true,
            'edit_posts'            => true,
            'publish_posts'         => true,
            'edit_others_posts'     => true,
            'delete_posts'          => true,
            'moderate_comments'     => true,
            'manage_categories'     => true,
            'upload_files'          => true,
            'list_users'            => true,
            'edit_users'            => false,  // Cannot edit other admins
        ]);
    }
}
add_action('init', 'bh_register_roles');


/* ═══════════════════════════════════════════════════════
   GRANT CAPABILITIES TO ADMINISTRATOR
   ═══════════════════════════════════════════════════════ */
function bh_grant_admin_caps() {
    $admin = get_role('administrator');
    if (!$admin) return;
    $bh_caps = [
        'bh_member', 'bh_moderator', 'bh_board_member',
        'bh_approve_members', 'bh_reject_members', 'bh_promote_members',
        'bh_rsvp_events', 'bh_view_directory', 'bh_post_forum',
        'bh_submit_article', 'bh_view_pending', 'bh_manage_events',
        'bh_manage_resources', 'bh_pending_member',
    ];
    foreach ($bh_caps as $cap) $admin->add_cap($cap);
}
add_action('admin_init', 'bh_grant_admin_caps');


/* ═══════════════════════════════════════════════════════
   ROLE HELPERS
   ═══════════════════════════════════════════════════════ */
function bh_get_role($user_id = null) {
    $user = $user_id ? get_userdata($user_id) : wp_get_current_user();
    if (!$user) return '';
    return $user->roles[0] ?? '';
}

function bh_role_level($role = null) {
    if (!$role) $role = bh_get_role();
    $levels = [
        'pending_member' => 0,
        'bh_member'      => 1,
        'bh_moderator'   => 2,
        'bh_board'       => 3,
        'administrator'  => 4,
    ];
    return $levels[$role] ?? 0;
}

function bh_has_role($required, $user_id = null) {
    return bh_role_level(bh_get_role($user_id)) >= bh_role_level($required);
}

function bh_is_approved($user_id = null) {
    $role = bh_get_role($user_id);
    return in_array($role, ['bh_member','bh_moderator','bh_board','administrator']);
}

function bh_is_pending($user_id = null) {
    return bh_get_role($user_id) === 'pending_member';
}

function bh_can_approve_members($user_id = null) {
    $user = $user_id ? get_userdata($user_id) : wp_get_current_user();
    return $user && $user->has_cap('bh_approve_members');
}


/* ═══════════════════════════════════════════════════════
   ADMIN APPROVAL / REJECTION ACTIONS
   ═══════════════════════════════════════════════════════ */
add_action('admin_post_bh_approve_member', 'bh_handle_approve_member');
function bh_handle_approve_member() {
    $user_id = absint($_GET['user_id'] ?? 0);
    check_admin_referer('bh_approve_' . $user_id);
    if (!current_user_can('bh_approve_members') || !$user_id) {
        wp_die(__('Permission denied.', 'baloch-heritage'));
    }
    $user = new WP_User($user_id);
    $user->set_role('bh_member');
    update_user_meta($user_id, 'bh_approved_by',    get_current_user_id());
    update_user_meta($user_id, 'bh_approved_date',  current_time('mysql'));
    update_user_meta($user_id, 'bh_member_status',  'approved');

    // Email the user
    $subject = __('Welcome to Baloch Heritage Canada — Application Approved!', 'baloch-heritage');
    $body    = sprintf(
        __("Dear %s,\n\nCongratulations! Your membership application for the Baloch Cultural Society of Canada has been approved.\n\nYou can now access your Member Portal at: %s\n\nWith pride and solidarity,\nBaloch Heritage Canada Team", 'baloch-heritage'),
        $user->display_name,
        home_url('/members/')
    );
    wp_mail($user->user_email, $subject, $body);

    wp_redirect(admin_url('admin.php?page=bh-members&approved=1'));
    exit;
}

add_action('admin_post_bh_reject_member', 'bh_handle_reject_member');
function bh_handle_reject_member() {
    $user_id = absint($_GET['user_id'] ?? 0);
    check_admin_referer('bh_reject_' . $user_id);
    if (!current_user_can('bh_approve_members') || !$user_id) {
        wp_die(__('Permission denied.', 'baloch-heritage'));
    }
    update_user_meta($user_id, 'bh_member_status', 'rejected');
    update_user_meta($user_id, 'bh_rejected_by',   get_current_user_id());
    update_user_meta($user_id, 'bh_rejected_date',  current_time('mysql'));

    // Optionally notify user
    $user = get_userdata($user_id);
    wp_mail($user->user_email,
        __('Baloch Heritage Canada — Application Update', 'baloch-heritage'),
        __("Dear applicant,\n\nThank you for your interest in joining the Baloch Cultural Society of Canada. After review, we are unable to approve your application at this time.\n\nFor questions, please contact us at info@balochheritage.org\n\nBaloch Heritage Canada", 'baloch-heritage')
    );

    wp_redirect(admin_url('admin.php?page=bh-members&rejected=1'));
    exit;
}

// AJAX approval (for frontend member portal)
add_action('wp_ajax_bh_approve_member_ajax', 'bh_ajax_approve_member');
function bh_ajax_approve_member() {
    check_ajax_referer('bh_nonce', 'nonce');
    if (!current_user_can('bh_approve_members')) {
        wp_send_json_error(['message' => __('Permission denied.', 'baloch-heritage')]);
    }
    $user_id = absint($_POST['user_id'] ?? 0);
    if (!$user_id) wp_send_json_error(['message' => __('Invalid user.', 'baloch-heritage')]);

    $user = new WP_User($user_id);
    $user->set_role('bh_member');
    update_user_meta($user_id, 'bh_approved_by',   get_current_user_id());
    update_user_meta($user_id, 'bh_approved_date', current_time('mysql'));
    update_user_meta($user_id, 'bh_member_status', 'approved');

    wp_send_json_success(['message' => sprintf(__('%s has been approved as a member.', 'baloch-heritage'), $user->display_name)]);
}

add_action('wp_ajax_bh_reject_member_ajax', 'bh_ajax_reject_member');
function bh_ajax_reject_member() {
    check_ajax_referer('bh_nonce', 'nonce');
    if (!current_user_can('bh_approve_members')) {
        wp_send_json_error(['message' => __('Permission denied.', 'baloch-heritage')]);
    }
    $user_id = absint($_POST['user_id'] ?? 0);
    if (!$user_id) wp_send_json_error(['message' => __('Invalid user.', 'baloch-heritage')]);

    update_user_meta($user_id, 'bh_member_status', 'rejected');
    $user = get_userdata($user_id);
    wp_send_json_success(['message' => sprintf(__('%s\'s application has been rejected.', 'baloch-heritage'), $user->display_name)]);
}

// Change role (admin only)
add_action('wp_ajax_bh_change_role', 'bh_ajax_change_role');
function bh_ajax_change_role() {
    check_ajax_referer('bh_nonce', 'nonce');
    if (!current_user_can('promote_users')) {
        wp_send_json_error(['message' => __('Permission denied.', 'baloch-heritage')]);
    }
    $user_id  = absint($_POST['user_id'] ?? 0);
    $new_role = sanitize_text_field($_POST['role'] ?? '');
    $allowed  = ['bh_member','bh_moderator','bh_board','pending_member'];
    if (!$user_id || !in_array($new_role, $allowed)) {
        wp_send_json_error(['message' => __('Invalid request.', 'baloch-heritage')]);
    }
    $user = new WP_User($user_id);
    $user->set_role($new_role);
    wp_send_json_success(['message' => __('Role updated successfully.', 'baloch-heritage')]);
}


/* ═══════════════════════════════════════════════════════
   ADMIN NOTICES
   ═══════════════════════════════════════════════════════ */
function bh_admin_approval_notice() {
    if (!current_user_can('bh_approve_members')) return;
    $pending = count(get_users(['role' => 'pending_member']));
    if ($pending > 0) {
        $url = admin_url('admin.php?page=bh-members');
        printf('<div class="notice notice-warning"><p><strong>Baloch Heritage:</strong> %s <a href="%s">%s</a></p></div>',
            sprintf(_n('%d new membership application awaiting approval.', '%d new membership applications awaiting approval.', $pending, 'baloch-heritage'), $pending),
            esc_url($url),
            __('Review now →', 'baloch-heritage')
        );
    }
}
add_action('admin_notices', 'bh_admin_approval_notice');


/* ═══════════════════════════════════════════════════════
   MEMBER DASHBOARD COLUMNS (Users screen)
   ═══════════════════════════════════════════════════════ */
add_filter('manage_users_columns', function($cols) {
    $cols['bh_status'] = __('BH Status', 'baloch-heritage');
    $cols['bh_approved_by'] = __('Approved By', 'baloch-heritage');
    return $cols;
});
add_filter('manage_users_custom_column', function($val, $col, $user_id) {
    if ($col === 'bh_status') {
        $status = get_user_meta($user_id, 'bh_member_status', true) ?: 'n/a';
        $color  = ['approved'=>'green','rejected'=>'red','pending'=>'orange'][$status] ?? 'grey';
        return "<span style='color:{$color};font-weight:700;text-transform:capitalize;'>{$status}</span>";
    }
    if ($col === 'bh_approved_by') {
        $approver_id = get_user_meta($user_id, 'bh_approved_by', true);
        return $approver_id ? get_userdata($approver_id)->display_name : '—';
    }
    return $val;
}, 10, 3);
