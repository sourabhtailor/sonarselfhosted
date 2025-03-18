<?php

/**
 * Example 019: Access-code authentication for recipient
 */

namespace DocuSign\Controllers\Examples\eSignature;

use DocuSign\Controllers\eSignBaseController;
use DocuSign\Services\Examples\eSignature\AccessCodeAuthenticationService;
use DocuSign\Services\ManifestService;

class EG019AccessCodeAuthentication extends eSignBaseController
{
    const EG = 'eg019'; # reference (and URL) for this example
    const FILE = __FILE__;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        parent::controller();
    }

    /**
     * 1. Check the token
     * 2. Call the worker method
     * 3. Redirect the user to the embedded signing
     *
     * @return void
     */
    public function createController(): void
    {
        # Step 1: Obtain your OAuth Token
        $this->checkDsToken();

        $envelopeId = AccessCodeAuthenticationService::accessCodeAuthentication(
            $this->args,
            $this->clientService,
            $this::DEMO_DOCS_PATH
        );

        if ($envelopeId) {
            $_SESSION["envelope_id"] = $envelopeId["envelope_id"]; # Save for use by other examples
            # which need an envelope_id
            $this->clientService->showDoneTemplateFromManifest(
                $this->codeExampleText,
                null,
                ManifestService::replacePlaceholders("{0}", $envelopeId["envelope_id"], $this->codeExampleText["ResultsPageText"])
            );
        }
    }

    /**
     * Get specific template arguments
     *
     * @return array
     */
    public function getTemplateArgs(): array
    {
        $envelope_args = [
            'signer_email' => $this->checkEmailInputValue($_POST['signer_email']),
            'signer_name' => $this->checkInputValues($_POST['signer_name']),
            'access_code' => $this->checkInputValues($_POST['access_code'])
        ];
        return [
            'account_id' => $_SESSION['ds_account_id'],
            'base_path' => $_SESSION['ds_base_path'],
            'ds_access_token' => $_SESSION['ds_access_token'],
            'envelope_args' => $envelope_args
        ];
    }
}
