<?php

/**
 * @author Jakub Lech <info@smartbyte.pl>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Spinbits\SyliusBaselinkerPlugin\Behat\Context\Api;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;
use Tests\Spinbits\SyliusBaselinkerPlugin\Behat\Page\Shop\BaslinkerConnectorPage;
use Exception;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertSame;

class BaselinkerConnectorContext implements Context
{
    private BaslinkerConnectorPage $connectorPage;

    /**
     * The Guzzle HTTP Client.
     */
    protected $client;

    /**
     * The current resource
     */
    protected $resource;

    /**
     * The request payload
     */
    protected $requestPayload;

    /**
     * The Guzzle HTTP Response.
     */
    protected $response;

    /**
     * The decoded response object.
     */
    protected $responsePayload;

    public function __construct(BaslinkerConnectorPage $connectorPage, array $parameters = [])
    {
        $this->connectorPage = $connectorPage;
        $config = isset($parameters['guzzle']) && is_array($parameters['guzzle']) ? $parameters['guzzle'] : [];

        $config['base_uri'] = $parameters['base_uri'] ?? 'http://localhost:8080';

        $this->client = new Client($config);
    }

    /**
     * @Given /^I have the payload:$/
     * @param PyStringNode $requestPayload
     */
    public function iHaveThePayload(PyStringNode $requestPayload)
    {
        $this->requestPayload = $requestPayload;
    }

    /**
     * @When /^I request "(GET|PUT|POST|DELETE|PATCH) ([^"]*)"$/
     */
    public function iRequest(string $httpMethod, string $resource)
    {
        $this->resource = $resource;

        $options = [];
        if ($this->requestPayload) {
            $options = [
                'form_params' => json_decode($this->requestPayload->getRaw(), true),
                'headers' => [
                    'Content-type' => 'application/x-www-form-urlencoded',
                ]
            ];
        }

        try {
            $this->response = $this
                ->client
                ->request($httpMethod, $resource, $options);
        } catch (BadResponseException $e) {
            $response = $e->getResponse();

            if ($response === null) {
                throw $e;
            }

            $this->response = $response;
        }
    }

    /**
     * @Then /^I get a "([^"]*)" response$/
     */
    public function iGetAResponse($statusCode)
    {
        $response = $this->getResponse();
        $contentType = $response->getHeader('Content-Type');

        assertSame('application/json', $contentType[0], 'Content-type is ' . $contentType[0]);
        assertSame((int) $statusCode, (int) $response->getStatusCode());
    }

    /**
     * @Then /^I get response body:$/
     */
    public function iGetResponseBody(PyStringNode $responsePayload)
    {
        $expected = json_decode($responsePayload->getRaw(), true);
        $response = $this->getResponsePayload();

        assertSame($expected, $response, json_encode($response));
    }

    /**
     * @Then /^I get response body with same structure:$/
     */
    public function iGetResponseBodyWithSameStructure(PyStringNode $responsePayload)
    {
        $expected = json_decode($responsePayload->getRaw(), true);
        $response = $this->getResponsePayload();

        assertSame([], $this->arrayDiffKeysAssocRecursive($expected, $response), json_encode($response));
    }

    protected function getResponsePayload()
    {
        if (null === $this->responsePayload) {
            $body = (string) $this->getResponse()->getBody();
            $json = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                $message = 'Failed to decode JSON body: ' . $body;

                switch (json_last_error()) {
                    case JSON_ERROR_DEPTH:
                        $message .= '(Maximum stack depth exceeded).';
                        break;
                    case JSON_ERROR_STATE_MISMATCH:
                        $message .= '(Underflow or the modes mismatch).';
                        break;
                    case JSON_ERROR_CTRL_CHAR:
                        $message .= '(Unexpected control character found).';
                        break;
                    case JSON_ERROR_SYNTAX:
                        $message .= '(Syntax error, malformed JSON).';
                        break;
                    case JSON_ERROR_UTF8:
                        $message .= '(Malformed UTF-8 characters, possibly incorrectly encoded).';
                        break;
                    default:
                        $message .= '(Unknown error).';
                        break;
                }

                throw new Exception($message);
            }

            $this->responsePayload = $json;
        }

        return $this->responsePayload;
    }

    /**
     * @return mixed
     * @throws Exception
     */
    protected function getResponse()
    {
        if (! $this->response) {
            throw new Exception("You must first make a request to check a response.");
        }

        return $this->response;
    }

    /**
     * This method returns difference between assoc arrays when comparing keys only.
     * Values are not compared. Basically it shows difference in array keys structure.
     * @param $array1 array
     * @param $array2 array
     * @return array
     */
    private function arrayDiffKeysAssocRecursive(array $array1, array $array2): array
    {
        $difference = [];
        foreach ($array1 as $key => $value) {
            if (is_array($value)) {
                if (!isset($array2[$key]) || !is_array($array2[$key])) {
                    $difference[$key] = $value;
                } else {
                    $new_diff = $this->arrayDiffKeysAssocRecursive($value, $array2[$key]);
                    if (!empty($new_diff)) {
                        $difference[$key] = $new_diff;
                    }
                }
            } elseif (!array_key_exists($key, $array2) /*|| $array2[$key] !== $value*/) {
                $difference[$key] = $value;
            }
        }
        return $difference;
    }
}
