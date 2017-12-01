<?php
namespace Unloq;

use Unloq\Api\Base;
use Unloq\Api\Contracts as UnloqContracts;

/**
 * Class Unloq
 *
 * @package Unloq
 * @version 1.0.0
 * @author Florin Popescu florin@unloq.io
 * @copyright 2017 © UNLOQ Systems LTD.
 */
class Unloq extends Base {

    /**
     * Unloq constructor.
     *
     * @param null $apiKey
     */
    public function __construct($apiKey = null)
    {
        parent::__construct();

        if(isset($apiKey))
            $this->apiKey = $apiKey;
    }

    /************************ APPROVALS *************************/

    /**
     * @param Api\Contracts\Approval\Authenticate $payload
     *
     * @return object
     */
    public function authenticate(UnloqContracts\Approval\Authenticate $payload)
    {
        return $this->execute('POST', 'authenticate', $payload);
    }

    public function authorize(UnloqContracts\Approval\Authorize $payload)
    {
        return $this->execute('POST', 'authorize/' . $payload->getCode(), $payload);
    }

    public function encrypt(UnloqContracts\Approval\Encryption $payload)
    {
        return $this->execute('POST', 'encryption/user', $payload);
    }

    public function getApprovals($id = null)
    {
        $action = isset($id) ? 'approvals/' . $id : 'approvals';

        return $this->execute('GET', $action);
    }

    /************************ ENROLLMENT *************************/

    /**
     * @param Api\Contracts\Enrollment\Enroll $payload
     *
     * @return object
     */
    public function isEnrolled(UnloqContracts\Enrollment\Enroll $payload)
    {
        return $this->execute('GET', 'enrolled?email=' . $payload->getEmail(), $payload);
    }

    /**
     * @param Api\Contracts\Enrollment\Enroll $payload
     *
     * @return object
     */
    public function enroll(UnloqContracts\Enrollment\Enroll $payload)
    {
        return $this->execute('POST', 'enroll', $payload);
    }

    /**
     * @param Api\Contracts\Enrollment\Enroll $payload
     *
     * @return object
     */
    public function deactivate(UnloqContracts\Enrollment\Enroll $payload)
    {
        return $this->execute('POST', 'deactivate', $payload);
    }

    /************************ TOKENS *************************/

    /**
     * @param Api\Contracts\Tokens\Token $payload
     *
     * @return object
     */
    public function token(UnloqContracts\Tokens\Token $payload)
    {
        return $this->execute('POST', 'token', $payload);
    }

    /**
     * @param Api\Contracts\Tokens\Session $payload
     *
     * @return object
     */
    public function tokenSession(UnloqContracts\Tokens\Session $payload)
    {
        return $this->execute('POST', 'token/session', $payload);
    }

    /**
     * @param Api\Contracts\Tokens\Device $payload
     *
     * @return object
     */
    public function tokenDevice(UnloqContracts\Tokens\Device $payload)
    {
        return $this->execute('POST', 'token/device', $payload);
    }

    /************************ ACTIONS *************************/

    /**
     * @param Api\Contracts\Actions\Action $payload
     *
     * @return object
     */
    public function createAction(UnloqContracts\Actions\Action $payload)
    {
        return $this->execute('POST', 'actions', $payload);
    }

    /**
     * @param Api\Contracts\Actions\Action $payload
     *
     * @return object
     */
    public function updateAction(UnloqContracts\Actions\Action $payload)
    {
        return $this->execute('PUT', 'actions/' . $payload->getCode(), $payload);
    }

    /**
     * @param null $page
     * @param null $limit
     *
     * @return object
     */
    public function getActions($page = null, $limit = null)
    {
        $action = 'actions';
        if($page !== null)
            $action = $action . '?page=' . $page;

        if($limit !== null)
            $action = $limit . '?limit=' . $limit;

        return $this->execute('GET', $action);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function getAction($id)
    {
        return $this->execute('GET', 'actions/' . $id);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function deleteAction($id)
    {
        return $this->execute('DELETE', 'actions/' . $id);
    }

    /************************ APIKEYS *************************/

    /**
     * @param UnloqContracts\ApiKeys\ApiKey $payload
     *
     * @return object
     */
    public function createApiKey(UnloqContracts\ApiKeys\ApiKey $payload)
    {
        return $this->execute('POST', 'api-keys', $payload);
    }

    /**
     * @param null $page
     * @param null $limit
     *
     * @return object
     */
    public function getApiKeys($page = null, $limit = null)
    {
        $action = 'api-keys';
        if($page !== null)
            $action = $action . '?page=' . $page;

        if($limit !== null)
            $action = $limit . '?limit=' . $limit;

        return $this->execute('GET', $action);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function getApiKey($id)
    {
        return $this->execute('GET', 'api-keys/' . $id);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function getApiKeyLogs($id)
    {
        return $this->execute('GET', 'api-keys/' . $id . '/logs');
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function deleteApiKeys($id)
    {
        return $this->execute('DELETE', 'api-keys/' . $id);
    }

    /************************ EMAILS *************************/

    /**
     * @param UnloqContracts\Emails\Email $payload
     *
     * @return object
     */
    public function setEmailSettings(UnloqContracts\Emails\Email $payload)
    {
        return $this->execute('POST', 'custom/emails', $payload);
    }

    /**
     * @return object
     */
    public function getEmailSettings()
    {
        return $this->execute('GET', 'custom/emails');
    }

    /**
     * @return object
     */
    public function verifyEmailSettings()
    {
        return $this->execute('POST', 'custom/emails/verify');
    }

    /**
     * @return object
     */
    public function deleteEmailSettings()
    {
        return $this->execute('DELETE', 'custom/emails');
    }

    /************************ EMAIL TEMPLATES *************************/

    /**
     * @param UnloqContracts\Emails\Template $payload
     *
     * @return object
     */
    public function updateEmailTemplate(UnloqContracts\Emails\Template $payload)
    {
        return $this->execute('PUT', 'custom/emails/templates/' . $payload->getCode(), $payload);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function getEmailTemplates($id = null)
    {
        $action = isset($id) ? 'custom/emails/templates/' . $id : 'custom/emails/templates';

        return $this->execute('GET', $action);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function deleteEmailTemplate($id)
    {
        return $this->execute('DELETE', 'custom/emails/templates/' . $id);
    }

    /**************************** STORAGE *****************************/

    /**
     * @return object
     */
    public function getStorageSettings()
    {
        return $this->execute('GET', 'custom/storage');
    }

    /**
     * @param UnloqContracts\Storage\Storage $payload
     *
     * @return object
     */
    public function updateStorageSettings(UnloqContracts\Storage\Storage $payload)
    {
        return $this->execute('POST', 'custom/storage', $payload);
    }

    public function verifyStorageSettings()
    {
        return $this->execute('POST', 'custom/verify');
    }

    /**
     * @return object
     */
    public function deleteStorageSettings()
    {
        return $this->execute('DELETE', 'custom/storage');
    }

    /**************************** FIREWALL *****************************/

    /**
     * @param null $page
     * @param null $limit
     *
     * @return object
     */
    public function getFirewallRules($page = null, $limit = null)
    {
        $action = 'firewalls';
        if($page !== null)
            $action = $action . '?page=' . $page;

        if($limit !== null)
            $action = $limit . '?limit=' . $limit;

        return $this->execute('GET', $action);
    }

    /**
     * @param $payload UnloqContracts\Firewalls\Firewall
     *
     * @return object
     */
    public function createFirewallRule(UnloqContracts\Firewalls\Firewall $payload)
    {
        return $this->execute('POST', 'firewalls', $payload);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function getFirewallRule($id)
    {
        return $this->execute('GET', 'firewalls/' . $id);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function deleteFirewallRule($id)
    {
        return $this->execute('DELETE', 'firewalls/' . $id);
    }

    /**
     * @param $ruleId
     * @param $payload UnloqContracts\Firewalls\Target
     *
     * @return object
     */
    public function createFirewallRuleTarget($ruleId, UnloqContracts\Firewalls\Target $payload)
    {
        return $this->execute('POST', 'firewalls/' . $ruleId, $payload);
    }

    /**
     * @param $ruleId
     * @param $targetId
     *
     * @return object
     */
    public function deleteFirewallRuleTarget($ruleId, $targetId)
    {
        return $this->execute('DELETE', 'firewalls/' . $ruleId . '/target/' . $targetId);
    }

    /**
     * @param $ruleId
     * @param $payload UnloqContracts\Firewalls\Log;
     *
     * @return object
     */
    public function getFirewallRuleLogs($ruleId, UnloqContracts\Firewalls\Log $payload)
    {
        return $this->execute('GET', 'firewalls/' . $ruleId, $payload);
    }

    /**************************** GROUPS *****************************/

    /**
     * @param null $page
     * @param null $limit
     *
     * @return object
     */
    public function getGroups($page = null, $limit = null)
    {
        $action = 'groups';
        if($page !== null)
            $action = $action . '?page=' . $page;

        if($limit !== null)
            $action = $limit . '?limit=' . $limit;

        return $this->execute('GET', $action);
    }

    /**
     * @param $payload UnloqContracts\Groups\Group
     *
     * @return object
     */
    public function createGroup(UnloqContracts\Groups\Group $payload)
    {
        return $this->execute('POST', 'groups', $payload);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function getGroup($id)
    {
        return $this->execute('GET', 'groups/' . $id);
    }

    /**
     * @param $id
     * @param $payload UnloqContracts\Groups\Group
     *
     * @return object
     */
    public function updateGroup($id, UnloqContracts\Groups\Group $payload)
    {
        return $this->execute('PUT', 'groups/' . $id, $payload);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function deleteGroup($id)
    {
        return $this->execute('DELETE', 'groups/' . $id);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function getGroupUsers($id)
    {
        return $this->execute('POST', 'groups/' . $id . '/users');
    }

    /**
     * @param $groupId
     * @param $payload UnloqContracts\Groups\Users;
     *
     * @return object
     */
    public function addGroupUser($groupId, UnloqContracts\Groups\Users $payload)
    {
        return $this->execute('POST', 'groups/' . $groupId, $payload);
    }

    /**
     * @param $groupId
     * @param $userId
     *
     * @return object
     */
    public function removeGroupUser($groupId, $userId)
    {
        return $this->execute('DELETE', 'groups/' . $groupId . '/users/' . $userId);
    }

    /**************************** NOTIFICATIONS *****************************/

    /**
     * @param null $page
     * @param null $limit
     *
     * @return object
     */
    public function getNotifications($page = null, $limit = null)
    {
        $action = 'notifications';
        if($page !== null)
            $action = $action . '?page=' . $page;

        if($limit !== null)
            $action = $limit . '?limit=' . $limit;

        return $this->execute('GET', $action);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function getNotificationDetails($id)
    {
        return $this->execute('GET', 'notifications/' . $id);
    }

    /**
     * @param $payload UnloqContracts\Notifications\Notification
     *
     * @return object
     */
    public function createNotification(UnloqContracts\Notifications\Notification $payload)
    {
        return $this->execute('POST', 'notifications', $payload);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function cancelNotification($id)
    {
        return $this->execute('POST', 'notifications/' . $id . '/cancel');
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function deleteNotification($id)
    {
        return $this->execute('DELETE', 'notifications/' . $id);
    }

    /**
     * @param $notificationId
     * @param $itemId
     *
     * @return object
     */
    public function deleteNotificationItem($notificationId, $itemId)
    {
        return $this->execute('DELETE', 'notifications/' . $notificationId . '/' . $itemId);
    }

    /**************************** REPORTS *****************************/

    /**
     * @param $payload UnloqContracts\Reports\Authorization
     *
     * @return object
     */
    public function getAuthenticationReports(UnloqContracts\Reports\Authorization $payload)
    {
        return $this->execute('GET', 'reports/authentication/', $payload);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function getAuthenticationReport($id)
    {
        return $this->execute('GET', 'reports/authentication/' . $id);
    }

    /**
     * @param $payload UnloqContracts\Reports\Authorization
     *
     * @return object
     */
    public function getAuthorizationReports(UnloqContracts\Reports\Authorization $payload)
    {
        return $this->execute('GET', 'reports/authorization', $payload);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function getAuthorizationReport($id)
    {
        return $this->execute('GET', 'reports/authorization/' . $id);
    }

    /**
     * @param $payload UnloqContracts\Reports\Encryption
     *
     * @return object
     */
    public function getEncryptionReports(UnloqContracts\Reports\Encryption $payload)
    {
        return $this->execute('GET', 'reports/encryption', $payload);
    }

    /**************************** USERS *****************************/

    /**
     * @param null $page
     * @param null $limit
     *
     * @return object
     */
    public function getUsers($page = null, $limit = null)
    {
        $action = 'users';
        if($page !== null)
            $action = $action . '?page=' . $page;

        if($limit !== null)
            $action = $limit . '?limit=' . $limit;

        return $this->execute('GET', $action);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function getUser($id)
    {
        return $this->execute('GET', 'users/' . $id);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function getUserGroups($id)
    {
        return $this->execute('GET', 'users/' . $id . '/groups');
    }

    /**
     * @param null $page
     * @param null $limit
     * @param string $id
     *
     * @return object
     */
    public function getUserActivity($page = null, $limit = null, $id)
    {
        $action = 'users/' . $id . '/activity';

        if($page !== null)
            $action = $action . '?page=' . $page;

        if($limit !== null)
            $action = $limit . '?limit=' . $limit;

        return $this->execute('GET', $action);
    }

    /**************************** WEBHOOKS *****************************/

    /**
     * @param null $page
     * @param null $limit
     *
     * @return object
     */
    public function getWebhooks($page = null, $limit = null)
    {
        $action = 'webhooks';
        if($page !== null)
            $action = $action . '?page=' . $page;

        if($limit !== null)
            $action = $limit . '?limit=' . $limit;

        return $this->execute('GET', $action);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function getWebhookDetails($id)
    {
        return $this->execute('GET', 'webhooks/' . $id);
    }

    /**
     * @param null $page
     * @param null $limit
     * @param string $id
     *
     * @return object
     */
    public function getWebhookHistory($page = null, $limit = null, $id)
    {
        $action = 'webhooks/' . $id . '/history';
        if($page !== null)
            $action = $action . '?page=' . $page;

        if($limit !== null)
            $action = $limit . '?limit=' . $limit;

        return $this->execute('GET', $action);
    }

    /**
     * @param $payload UnloqContracts\Webhooks\Webhook
     *
     * @return object
     */
    public function createWebhook(UnloqContracts\Webhooks\Webhook $payload)
    {
        return $this->execute('POST', 'webhooks', $payload);
    }

    /**
     * @param string $id
     * @param $payload UnloqContracts\Webhooks\Webhook
     *
     * @return object
     */
    public function updateWebhook($id, UnloqContracts\Webhooks\Webhook $payload)
    {
        return $this->execute('PUT', 'webhooks/'.$id, $payload);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function deleteWebhook($id)
    {
        return $this->execute('DELETE', 'webhooks/.' . $id);
    }

    /**************************** WIDGETS *****************************/

    /**
     * @param null $page
     * @param null $limit
     *
     * @return object
     */
    public function getWidgets($page = null, $limit = null)
    {
        $action = 'widgets';
        if($page !== null)
            $action = $action . '?page=' . $page;

        if($limit !== null)
            $action = $limit . '?limit=' . $limit;

        return $this->execute('GET', $action);
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function getWidgetDetails($id)
    {
        return $this->execute('GET', 'widgets/' . $id);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function getWidgetScript($id)
    {
        return $this->execute('GET', 'widgets/' . $id . '/script');
    }

    /**
     * @param $payload UnloqContracts\Widgets\Widget
     *
     * @return object
     */
    public function createWidget(UnloqContracts\Widgets\Widget $payload)
    {
        return $this->execute('POST', 'widgets', $payload);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function verifyWidget($id)
    {
        return $this->execute('POST', 'widgets/' . $id . '/verify');
    }

    /**
     * @param string $id
     * @param $payload UnloqContracts\Widgets\Widget
     *
     * @return object
     */
    public function updateWidget($id, UnloqContracts\Widgets\Widget $payload)
    {
        return $this->execute('PUT', 'widgets/'.$id, $payload);
    }

    /**
     * @param string $id
     *
     * @return object
     */
    public function deleteWidget($id)
    {
        return $this->execute('DELETE', 'widgets/.' . $id);
    }
}