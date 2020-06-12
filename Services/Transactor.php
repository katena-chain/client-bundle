<?php

/**
 * Copyright (c) 2018, TransChain.
 *
 * This source code is licensed under the Apache 2.0 license found in the
 * LICENSE file in the root directory of this source tree.
 */

namespace KatenaChain\ClientBundle\Services;

use KatenaChain\Client\Entity\TxSigner;
use KatenaChain\Client\Transactor as SDKTransactor;
use KatenaChain\Client\Utils\Common;
use KatenaChain\Client\Utils\Crypto;

class Transactor extends SDKTransactor
{
    /**
     * @param string $apiUrl
     * @param string $chainId
     * @param string $signerCompanyBcId
     * @param string $signerId
     * @param string $signerPrivateKeyBase64 (base64)
     */
    public function __construct(
        string $apiUrl,
        string $chainId,
        string $signerCompanyBcId = "",
        string $signerId = "",
        ?string $signerPrivateKeyBase64 = null
    )
    {
        if ($signerPrivateKeyBase64) {
            $privateKey = Crypto::createPrivateKeyED25519FromBase64($signerPrivateKeyBase64);
        } else {
            $privateKey = null;
        }
        $txSigner = new TxSigner(Common::concatFqId($signerCompanyBcId, $signerId), $privateKey);
        parent::__construct($apiUrl, $chainId, $txSigner);
    }
}
