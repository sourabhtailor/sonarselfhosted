<?php

namespace DocuSign\Services\Examples\eSignature;

use DocuSign\eSign\Client\ApiException;
use DocuSign\eSign\Model\Document;
use DocuSign\eSign\Model\EnvelopeDefinition;
use DocuSign\eSign\Model\Recipients;
use DocuSign\eSign\Model\Signer;
use DocuSign\eSign\Model\SignHere;
use DocuSign\eSign\Model\Tabs;
use DocuSign\Services\SignatureClientService;

class EmbeddedSigningService
{
    /**
     * Do the work of the example:
     * Create the envelope request object
     * Send the envelope
     * Create the Recipient View request object
     * Obtain the recipient_view_url for the embedded signing
     *
     * @param  $args array
     * @param SignatureClientService $clientService
     * @return array ['redirect_url']
     */
    public static function worker(array $args, SignatureClientService $clientService, string $demoPath, string $pdfFile): array
    {
        # Create the envelope request object
        #ds-snippet-start:eSign1Step3
        $envelope_definition = EmbeddedSigningService::makeEnvelope($args["envelope_args"], $demoPath, $pdfFile);
        $envelope_api = $clientService->getEnvelopeApi();

        # Call Envelopes::create API method
        # Exceptions will be caught by the calling function
        try {
            $envelopeSummary = $envelope_api->createEnvelope($args['account_id'], $envelope_definition);
        } catch (ApiException $e) {
            $clientService->showErrorTemplate($e);
            exit;
        }
        $envelope_id = $envelopeSummary->getEnvelopeId();
        #ds-snippet-end:eSign1Step3

        # Create the Recipient View request object
        #ds-snippet-start:eSign1Step4
        $authentication_method = 'None'; # How is this application authenticating
        # the signer? See the `authentication_method' definition
        # https://developers.docusign.com/docs/esign-rest-api/reference/envelopes/envelopeviews/createrecipient/
        $recipient_view_request = $clientService->getRecipientViewRequest(
            $authentication_method,
            $args["envelope_args"]
        );
        #ds-snippet-end:eSign1Step4

        # Obtain the recipient_view_url for the embedded signing
        # Exceptions will be caught by the calling function
        #ds-snippet-start:eSign1Step5
        $viewUrl = $clientService->getRecipientView($args['account_id'], $envelope_id, $recipient_view_request);

        return ['envelope_id' => $envelope_id, 'redirect_url' => $viewUrl['url']];
        #ds-snippet-end:eSign1Step5
    }

    /**
     *  Creates envelope definition
     *  Parameters for the envelope: signer_email, signer_name, signer_client_id
     *
     * @param  $args array
     * @return EnvelopeDefinition -- returns an envelope definition
     */
    #ds-snippet-start:eSign1Step2
    public static function makeEnvelope(array $args, string $demoPath, string $pdfFile): EnvelopeDefinition
    {
        # document 1 (pdf) has tag /sn1/
        #
        # The envelope has one recipient.
        # recipient 1 - signer
        #
        # Read the file
        $content_bytes = file_get_contents($demoPath . $pdfFile);
        $base64_file_content = base64_encode($content_bytes);

        # Create the document model
        $document = new Document(
            [ # create the DocuSign document object
                'document_base64' => $base64_file_content,
                'name' => 'Example document', # can be different from actual file name
                'file_extension' => 'pdf', # many different document types are accepted
                'document_id' => 1 # a label used to reference the doc
            ]
        );

        # Create the signer recipient model
        $signer = new Signer(
            [ # The signer
                'email' => $args['signer_email'],
                'name' => $args['signer_name'],
                'recipient_id' => "1",
                'routing_order' => "1",
                # Setting the client_user_id marks the signer as embedded
                'client_user_id' => $args['signer_client_id']
            ]
        );

        # Create a sign_here tab (field on the document)
        $sign_here = new SignHere(
            [ # DocuSign SignHere field/tab
                'anchor_string' => '/sn1/',
                'anchor_units' => 'pixels',
                'anchor_y_offset' => '10',
                'anchor_x_offset' => '20'
            ]
        );

        # Add the tabs model (including the sign_here tab) to the signer
        # The Tabs object wants arrays of the different field/tab types
        $signer->settabs(new Tabs(['sign_here_tabs' => [$sign_here]]));

        // Next, create the top level envelope definition and populate it.
        $envelope_definition = new EnvelopeDefinition(
            [
                'email_subject' => "Please sign this document sent from the PHP SDK",
                'documents' => [$document],
                # The Recipients object wants arrays for each recipient type
                'recipients' => new Recipients(['signers' => [$signer]]),
                'status' => "sent" # requests that the envelope be created and sent.
            ]
        );

        return $envelope_definition;
    }
    #ds-snippet-end:eSign1Step2
}
