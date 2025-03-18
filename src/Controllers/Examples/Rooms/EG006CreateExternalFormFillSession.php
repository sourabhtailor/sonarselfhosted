<?php

namespace DocuSign\Controllers\Examples\Rooms;

use DocuSign\Controllers\RoomsApiBaseController;
use DocuSign\Services\Examples\Rooms\CreateExternalFormFillSessionService;

class EG006CreateExternalFormFillSession extends RoomsApiBaseController
{
    const EG = "reg006"; # reference (and URL) for this example
    const FILE = __FILE__;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $rooms = CreateExternalFormFillSessionService::getRooms(
            $this->clientService,
            $this->routerService,
            $this->args,
            $this::EG
        );
        parent::controller(null, $rooms);
    }

    /**
     * 1. Check the token
     * 2. Render new form if room were selected
     * 3.
     * 4. Return RoomSummaryList for specified time period
     *
     * @return void
     */
    protected function createController(): void
    {
        $this->checkDsToken();
        #ds-snippet-start:Rooms6Step3
        $room_id = $this->args['room_id'];
        $form_id = $this->args['form_id'];
        $this->args['x_frame_allowed_url'] = "http://localhost:8080";
        #ds-snippet-end:Rooms6Step3
        
        if ($room_id && !$form_id) {
            $room = CreateExternalFormFillSessionService::getRoom(
                $room_id,
                $this->routerService,
                $this->clientService,
                $this->args,
                $this::EG
            );
            $room_documents = CreateExternalFormFillSessionService::getDocuments(
                $room_id,
                $this->routerService,
                $this->clientService,
                $this->args,
                $this::EG
            );
            $room_name = $room['name'];
            $room_forms = array_values(
                array_filter(
                    $room_documents,
                    function ($f) {
                        return $f['docu_sign_form_id'];
                    }
                )
            );

            $GLOBALS['twig']->display(
                $this->routerService->getTemplate($this::EG),
                [
                    'title' => $this->routerService->getTitle($this::EG),
                    'forms' => $room_forms,
                    'room_id' => $room_id,
                    'room_name' => $room_name,
                    'source_file' => basename(__FILE__),
                    'source_url' => $GLOBALS['DS_CONFIG']['github_example_url'] . basename($this::FILE),
                    'documentation' => $GLOBALS['DS_CONFIG']['documentation'] . $this::EG,
                    'show_doc' => $GLOBALS['DS_CONFIG']['documentation'],
                    'code_example_text' => $this->codeExampleText,
                    'common_texts' => $this->getCommonText()
                ]
            );
        } else {
            $createExternalFormResponse = CreateExternalFormFillSessionService::createExternalFormFillSession(
                $this->args,
                $this->clientService
            );

            #ds-snippet-start:Rooms6Step5
            if ($createExternalFormResponse) {
                $createExternalFormJSON = json_decode((string)$createExternalFormResponse, true);

                $this->clientService->showDoneTemplateFromManifest(
                    $this->codeExampleText,
                    "",
                    "Results from the Forms::CreateExternalFormFillSession: <br/><code>"
                    . stripslashes($createExternalFormResponse) . "</code>",
                    $createExternalFormJSON['url'],
                );
            }
            #ds-snippet-end:Rooms6Step5
        }
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
            'room_id' => $this->checkInputValues($_POST['room_id']),
            'form_id' => $this->checkInputValues($_POST['form_id']),
            'room_name' => $this->checkInputValues($_POST['room_name']),
        ];
    }
}
