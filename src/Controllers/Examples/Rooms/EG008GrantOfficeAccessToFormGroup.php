<?php

namespace DocuSign\Controllers\Examples\Rooms;

use DocuSign\Controllers\RoomsApiBaseController;
use DocuSign\Services\Examples\Rooms\GrantOfficeAccessToFormGroupService;
use DocuSign\Services\ManifestService;

class EG008GrantOfficeAccessToFormGroup extends RoomsApiBaseController
{
    const EG = 'reg008'; # reference (and URL) for this example
    const FILE = __FILE__;

    /**
     * 1. Get available offices.
     * 2. Get available form groups.
     * 3. Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        #ds-snippet-start:Rooms8Step3
        $offices = GrantOfficeAccessToFormGroupService::getOffices(
            $this->routerService,
            $this->clientService,
            $this->args,
            $this::EG
        );
        #ds-snippet-end:Rooms8Step3

        #ds-snippet-start:Rooms8Step4
        $formGroups = GrantOfficeAccessToFormGroupService::getFormGroups(
            $this->routerService,
            $this->clientService,
            $this->args,
            $this::EG
        );
        parent::controller(null, null, null, $offices, $formGroups);
        #ds-snippet-end:Rooms8Step4
    }

    /**
     * 1. Check the token
     * 2. Call the worker method
     * 3. Render request results
     *
     * @return void
     */
    protected function createController(): void
    {
        $this->checkDsToken();
        GrantOfficeAccessToFormGroupService::grantOfficeAccessToFormGroup($this->args, $this->clientService);
        $this->clientService->showDoneTemplateFromManifest(
            $this->codeExampleText,
            null,
            ManifestService::replacePlaceholders(
                "{1}",
                $this->args["form_group_id"],
                ManifestService::replacePlaceholders("{0}", $this->args["office_id"], $this->codeExampleText["ResultsPageText"])
            ),
        );
    }

    /**
     * Get specific template arguments
     *
     * @return array
     */
    public function getTemplateArgs(): array
    {
        return [
            'account_id' => $_SESSION['ds_account_id'],
            'ds_access_token' => $_SESSION['ds_access_token'],
            'office_id' => $this->checkInputValues($_POST['office_id']),
            'form_group_id' => $this->checkInputValues($_POST['form_group_id'])
        ];
    }
}
