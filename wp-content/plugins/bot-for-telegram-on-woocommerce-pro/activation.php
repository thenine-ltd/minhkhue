<?php
require_once BFTOW_PRO_DIR . '/includes/BFTOW_Plugin_Statuses.php';
require_once BFTOW_PRO_DIR . '/includes/BFTOW_Verification_Service.php';

$status = new BFTOW_Plugin_Statuses(get_transient('bftow_pro_status'));
$verifyService = new BFTOW_Verification_Service();

if ($status->isNotActivated()) {
    $verifyService->verify();
    $isVerified = $verifyService->isVerified();

    if ($isVerified) {
        $status = new BFTOW_Plugin_Statuses(get_transient('bftow_pro_status'));
    }
}

add_filter('wpcfto_check_is_pro_field', function () use ($status) {
    return $status->isActivated();
}, 100);

add_filter('bftow_call_to_action_button', function ($html) use ($status) {
    if ($status->isNotActivated()): ?>
        <a href="https://analythicsthemes.com/auth/redirect?domain=<?php echo site_url()?>"
           target="_blank"><?php esc_html_e('Activate Pro Version', 'bftow'); ?></a>
    <?php endif; ?>
<?php }, 100, 1);
