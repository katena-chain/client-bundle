<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\ClientBundle\Services;

use GuzzleHttp\Exception\GuzzleException;

use KatenaChain\Client\Crypto\ED25519\PublicKey as ED25519PubKey;
use KatenaChain\Client\Crypto\Nacl\PublicKey as NaclPubKey;
use KatenaChain\Client\Entity\Account\KeyV1;
use KatenaChain\Client\Entity\Api\TxStatus;
use KatenaChain\Client\Entity\Api\TxWrapper;
use KatenaChain\Client\Entity\Api\TxWrappers;
use KatenaChain\Client\Entity\Bytes;
use KatenaChain\Client\Exceptions\ApiException;
use KatenaChain\Client\Exceptions\ClientException;
use KatenaChain\Client\Transactor as SDKTransactor;
use KatenaChain\Client\Utils\Crypto;
use KatenaChain\Client\Utils\Uri;
use SodiumException;

class Transactor
{
    /**
     * @var SDKTransactor
     */
    protected $client;

    /**
     * Transacter constructor.
     * @param string $apiUrl
     * @param string $apiUrlSuffix
     * @param string $chainId
     * @param string $privateKeyBase64 (base64)
     * @param string $companyChainId
     */
    public function __construct(
        string $apiUrl,
        string $apiUrlSuffix,
        string $chainId,
        string $companyChainId = "",
        ?string $privateKeyBase64 = null
    )
    {
        if ($privateKeyBase64) {
            $privateKey = Crypto::createPrivateKeyED25519FromBase64($privateKeyBase64);
        } else {
            $privateKey = null;
        }

        $url = Uri::getUri($apiUrl, [$apiUrlSuffix]);
        $this->client = new SDKTransactor($url, $chainId, $companyChainId, $privateKey);
    }

    /**
     * @param string $uuid
     * @param string $value
     * @return TxStatus
     * @throws ApiException
     * @throws GuzzleException
     * @throws SodiumException
     * @throws ClientException
     */
    public function sendCertificateRawV1(string $uuid, string $value): TxStatus
    {
        return $this->client->sendCertificateRawV1($uuid, $value);
    }

    /**
     * @param string $uuid
     * @param ED25519PubKey $signer
     * @param string $signature
     * @return TxStatus
     * @throws ApiException
     * @throws GuzzleException
     * @throws SodiumException
     * @throws ClientException
     */
    public function sendCertificateEd25519V1(string $uuid, ED25519PubKey $signer, string $signature): TxStatus
    {
        return $this->client->sendCertificateEd25519V1($uuid, $signer, $signature);
    }

    /**
     * @param string $uuid
     * @param Ed25519PubKey $publicKey
     * @param string $role
     * @return TxStatus
     * @throws ApiException
     * @throws ClientException
     * @throws GuzzleException
     * @throws SodiumException
     */
    public function sendKeyCreateV1(string $uuid, Ed25519PubKey $publicKey, string $role): TxStatus
    {
        return $this->client-$this->sendKeyCreateV1($uuid, $publicKey, $role);
    }

    /**
     * @param string $uuid
     * @param Ed25519PubKey $publicKey
     * @return TxStatus
     * @throws ApiException
     * @throws ClientException
     * @throws GuzzleException
     * @throws SodiumException
     */
    public function sendKeyRevokeV1(string $uuid, Ed25519PubKey $publicKey): TxStatus
    {
        return $this->client-$this->sendKeyRevokeV1($uuid, $publicKey);
    }


    /**
     * @param string $certificateUuid
     * @param NaclPubKey $lockEncryptor
     * @param Bytes $lockNonce
     * @param Bytes $lockContent
     * @return TxStatus
     * @throws ApiException
     * @throws GuzzleException
     * @throws SodiumException
     * @throws ClientException
     */
    public function sendSecretNaclBoxV1(
        string $certificateUuid,
        NaclPubKey $lockEncryptor,
        Bytes $lockNonce,
        Bytes $lockContent
    ): TxStatus
    {
        return $this->client->sendSecretNaclBoxV1($certificateUuid, $lockEncryptor, $lockNonce, $lockContent);
    }

    /**
     * @param string $companyBcid
     * @param string $uuid
     * @return TxWrapper
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveLastCertificate(string $companyBcid, string $uuid): TxWrapper
    {
        return $this->client->retrieveLastCertificate($companyBcid, $uuid);
    }

    /**
     * @param string $companyBcid
     * @param string $uuid
     * @param int $page
     * @param int $txPerPage
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveCertificates(string $companyBcid, string $uuid, int $page, int $txPerPage): TxWrappers
    {
        return $this->client->retrieveCertificates($companyBcid, $uuid, $page, $txPerPage);
    }

    /**
     * @param string $companyBcid
     * @param string $uuid
     * @param int $page
     * @param int $txPerPage
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveKeyCreateTxs(string $companyBcid, string $uuid, int $page, int $txPerPage): TxWrappers
    {
        return $this->client->retrieveKeyCreateTxs($companyBcid, $uuid, $page, $txPerPage);
    }

    /**
     * @param string $companyBcid
     * @param string $uuid
     * @param int $page
     * @param int $txPerPage
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveKeyRevokeTxs(string $companyBcid, string $uuid, int $page, int $txPerPage): TxWrappers
    {
        return $this->client->retrieveKeyRevokeTxs($companyBcid, $uuid, $page, $txPerPage);
    }

    /**
     * @param string $companyBcid
     * @param int $page
     * @param int $txPerPage
     * @return KeyV1[]
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveCompanyKeys(string $companyBcid, int $page, int $txPerPage): array
    {
        return $this->client->retrieveCompanyKeys($companyBcid, $page, $txPerPage);
    }

    /**
     * @param string $companyBcid
     * @param string $uuid
     * @param int $page
     * @param int $txPerPage
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveSecrets(string $companyBcid, string $uuid, int $page, int $txPerPage): TxWrappers
    {
        return $this->client->retrieveSecrets($companyBcid, $uuid, $page, $txPerPage);
    }

    /**
     * @param string $txCategory
     * @param string $companyBcid
     * @param string $uuid
     * @param int $page
     * @param int $txPerPage
     * @return TxWrappers
     * @throws ApiException
     * @throws GuzzleException
     */
    public function retrieveTxs(string $txCategory, string $companyBcid, string $uuid, int $page, int $txPerPage): TxWrappers
    {
        return $this->client->retrieveTxs($txCategory, $companyBcid, $uuid, $page, $txPerPage);
    }
}
