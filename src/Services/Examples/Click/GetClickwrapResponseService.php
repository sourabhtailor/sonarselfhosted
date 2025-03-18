<?php

namespace DocuSign\Services\Examples\Click;

use DocuSign\Click\Client\ApiException;
use DocuSign\Click\Model\ClickwrapAgreementsResponse;
use DocuSign\Services\ClickApiClientService;
use DocuSign\Services\RouterService;

class GetClickwrapResponseService
{
    /**
     * @param  $args array
     * @param ClickApiClientService $clientService
     * @return ClickwrapAgreementsResponse
     */
    public static function getClickwrapResponse(array $args, ClickApiClientService $clientService): ClickwrapAgreementsResponse
    {

        try {
            #ds-snippet-start:Click5Step3
            $accounts_api = $clientService->accountsApi();
            $response = $accounts_api->getClickwrapAgreements($args['account_id'], $args['clickwrap_id']);
            #ds-snippet-end:Click5Step3
        } catch (ApiException $e) {
            error_log($e);
            $clientService->showErrorTemplate($e);
            exit;
        }

        return $response;
    }

    public static function getClickwraps(
        RouterService $routerService,
        ClickApiClientService $clientService,
        array $args,
        string $eg
    ): array {
        if ($routerService->dsTokenOk($GLOBALS['DS_CONFIG']['minimum_buffer_min'])) {
            try {
                $apiClient = $clientService->accountsApi();
                return $apiClient->getClickwraps($args['account_id'])['clickwraps'];
            } catch (ApiException $e) {
                error_log($e);
                return [];
            }
        } else {
            $clientService->needToReAuth($eg);
        }
    }
}
