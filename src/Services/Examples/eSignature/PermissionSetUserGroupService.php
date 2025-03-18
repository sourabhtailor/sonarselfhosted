<?php

namespace DocuSign\Services\Examples\eSignature;

use DocuSign\eSign\Client\ApiException;
use DocuSign\eSign\Model\Group;
use DocuSign\eSign\Model\GroupInformation;

class PermissionSetUserGroupService
{
    /**
     * Do the work of the example
     * 1. Create the envelope request object
     * 2. Send the envelope
     *
     * @param  $args array
     * @param $clientService
     * @return string
     */
    public static function permissionSetUserGroup(array $args, $clientService): string
    {
        # Step 3. Construct your request body
        #ds-snippet-start:eSign25Step3
        $groups_api = $clientService->getGroupsApi();
        $group = new Group($args['permission_args']);
        $group_information = new GroupInformation(['groups' => [$group]]);
        #ds-snippet-end:eSign25Step3
        try {
            # Step 4. call the eSignature REST API
            #ds-snippet-start:eSign25Step4
            $updatedGroups = $groups_api->updateGroups(
                $args['account_id'],
                $group_information
            );
            #ds-snippet-end:eSign25Step4
        } catch (ApiException $e) {
            $clientService->showErrorTemplate($e);
            exit;
        }

        return $updatedGroups;
    }
}
