<?php

/**
 * This code was generated by
 * ___ _ _ _ _ _    _ ____    ____ ____ _    ____ ____ _  _ ____ ____ ____ ___ __   __
 *  |  | | | | |    | |  | __ |  | |__| | __ | __ |___ |\ | |___ |__/ |__|  | |  | |__/
 *  |  |_|_| | |___ | |__|    |__| |  | |    |__] |___ | \| |___ |  \ |  |  | |__| |  \
 *
 * Twilio - Verify
 * This is the public Twilio REST API.
 *
 * NOTE: This class is auto generated by OpenAPI Generator.
 * https://openapi-generator.tech
 * Do not edit the class manually.
 */


namespace Twilio\Rest\Verify\V2;

use Twilio\Exceptions\TwilioException;
use Twilio\Version;
use Twilio\InstanceContext;


class SafelistContext extends InstanceContext
    {
    /**
     * Initialize the SafelistContext
     *
     * @param Version $version Version that contains the resource
     * @param string $phoneNumber The phone number to be removed from SafeList. Phone numbers must be in [E.164 format](https://www.twilio.com/docs/glossary/what-e164).
     */
    public function __construct(
        Version $version,
        $phoneNumber
    ) {
        parent::__construct($version);

        // Path Solution
        $this->solution = [
        'phoneNumber' =>
            $phoneNumber,
        ];

        $this->uri = '/SafeList/Numbers/' . \rawurlencode($phoneNumber)
        .'';
    }

    /**
     * Delete the SafelistInstance
     *
     * @return bool True if delete succeeds, false otherwise
     * @throws TwilioException When an HTTP error occurs.
     */
    public function delete(): bool
    {

        return $this->version->delete('DELETE', $this->uri);
    }


    /**
     * Fetch the SafelistInstance
     *
     * @return SafelistInstance Fetched SafelistInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function fetch(): SafelistInstance
    {

        $payload = $this->version->fetch('GET', $this->uri, [], []);

        return new SafelistInstance(
            $this->version,
            $payload,
            $this->solution['phoneNumber']
        );
    }


    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string
    {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.Verify.V2.SafelistContext ' . \implode(' ', $context) . ']';
    }
}
