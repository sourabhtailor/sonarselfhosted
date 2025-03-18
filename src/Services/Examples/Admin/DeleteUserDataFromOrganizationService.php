<?php

namespace DocuSign\Services\Examples\Admin;

use DocuSign\Admin\Api\OrganizationsApi;
use DocuSign\Admin\Api\UsersApi;
use DocuSign\Admin\Api\UsersApi\GetUserDSProfilesByEmailOptions;
use DocuSign\Admin\Client\ApiException;
use DocuSign\Admin\Model\IndividualUserDataRedactionRequest;
use DocuSign\Admin\Model\MembershipDataRedactionRequest;
use DocuSign\Admin\Model\IndividualUserDataRedactionResponse;

class DeleteUserDataFromOrganizationService
{
    /**
     * Delete user data from organization.
     *
     * @param UsersApi         $usersApi
     * @param OrganizationsApi $organizationsApi
     * @param string           $organizationId
     * @param string           $emailAddress
     * @return IndividualUserDataRedactionResponse
     * @throws ApiException
     */
    public static function deleteUserDataFromOrganization(
        UsersApi $usersApi,
        OrganizationsApi $organizationsApi,
        string $organizationId,
        string $emailAddress
    ): IndividualUserDataRedactionResponse {
        $getProfilesOptions = new GetUserDSProfilesByEmailOptions();
        $getProfilesOptions->setEmail($emailAddress);

        $profiles = $usersApi->getUserDSProfilesByEmail($organizationId, $getProfilesOptions);
        $user = $profiles->getUsers()[0];

        #ds-snippet-start:Admin10Step3
        $userRedactionRequest = new IndividualUserDataRedactionRequest();
        $userRedactionRequest->setUserId($user->getId());
        $userRedactionRequest->setMemberships([
            new MembershipDataRedactionRequest([
                "account_id" => $user->getMemberships()[0]->getAccountId()
            ])
        ]);
        #ds-snippet-end:Admin10Step3

        #ds-snippet-start:Admin10Step4
        return $organizationsApi->redactIndividualUserData($organizationId, $userRedactionRequest);
        #ds-snippet-end:Admin10Step4
    }
}
