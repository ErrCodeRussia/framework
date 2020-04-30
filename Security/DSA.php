<?php


namespace base\security;


use base\App;
use base\exceptions\config\ConfigValuesException;

/**
 * Class DSA
 * @package base\security
 *
 * Создан для работы с Digital Signature Algorithm.
 *
 * ВНИМАНИЕ! Для работы с классом необходимо наличие массива конфигурации для
 * DSA в файле config/config.php в движке ErrCode.
 *
 * Учитываются два варианта работы с этим классом.
 * Если вам нужно сгенерировать публичный и приватный ключ и продолжить работу
 * с полученными значениями - вы создаёте новый экземпляр этого класса. При
 * создании ключи генерируются сразу в конструкторе.
 *
 * Далее вы можете работать с полученными значениями:
 * 1. Получить их с помощью getPublicKey и getPrivateKey;
 * 2. Зашифровать строку с помощью сгенерированного публичного и приватного ключа
 * в publicEncrypt и privateEncrypt соответственно;
 * 3. Расшифровать строку с помощью сгенерированного публичного и приватного ключа
 * в publicDecrypt и privateDecrypt соответственно.
 *
 * Если у вас уже есть публичный или приватный ключ, для шифрования и дешифрования
 * данных вы можете пользоваться статичными методами:
 * - DSA::publicKeyEncrypt,
 * - DSA::publicKeyDecrypt,
 * - DSA::privateKeyEncrypt,
 * - DSA::privateKeyDecrypt.
 * Вторым параметром вы передаёте публичный/приватный ключ, который и будет
 * использоваться для работы с данными.
 */
class DSA
{
    private $pkeyNew;
    private $publicKey;
    private $privateKey;

    public function __construct()
    {
        try {
            if (is_null(App::$config->DSA)) {
                throw new ConfigValuesException("DSA", "array");
            }
        }
        catch (\Exception $e) {
            echo $e->getMessage();
            die();
        }

        $this->pkeyNew = $this->pkeyNew(App::$config->DSA);

        $this->generatePublicKey();
        $this->generatePrivateKey();
    }

    /**
     * Шифрует переданную строку с помощью сгенерированного публичного ключа.
     *
     * @param $data string
     * @return string
     */
    public function publicEncrypt($data)
    {
        openssl_public_encrypt($data, $secureData, $this->publicKey);

        return $secureData;
    }

    /**
     * Дешифрует переданную строку с помощью сгенерированного публичного ключа.
     *
     * @param $data string
     * @return string
     */
    public function publicDecrypt($data)
    {
        openssl_public_decrypt($data, $secureData, $this->publicKey);

        return $secureData;
    }

    /**
     * Шифрует переданную строку с помощью переданного публичного ключа.
     *
     * @param $data string
     * @param $publicKey string
     * @return string
     */
    public static function publicKeyEncrypt($data, $publicKey)
    {
        openssl_public_encrypt($data, $secureData, $publicKey);

        return $secureData;
    }

    /**
     * Дешифрует переданную строку с помощью переданного публичного ключа.
     *
     * @param $data string
     * @param $publicKey string
     * @return string
     */
    public static function publicKeyDecrypt($data, $publicKey)
    {
        openssl_public_decrypt($data, $secureData, $publicKey);

        return $secureData;
    }

    /**
     * Шифрует переданную строку с помощью сгенерированного приватного ключа.
     *
     * @param $data string
     * @return string
     */
    public function privateEncrypt($data)
    {
        openssl_private_encrypt($data, $secureData, $this->privateKey);

        return $secureData;
    }

    /**
     * Дешифрует переданную строку с помощью сгенерированного приватного ключа.
     *
     * @param $data string
     * @return string
     */
    public function privateDecrypt($data)
    {
        openssl_private_decrypt($data, $secureData, $this->privateKey);

        return $secureData;
    }

    /**
     * Шифрует переданную строку с помощью переданного приватного ключа.
     *
     * @param $data string
     * @param $privateKey string
     * @return string
     */
    public static function privateKeyEncrypt($data, $privateKey)
    {
        openssl_private_encrypt($data, $secureData, $privateKey);

        return $secureData;
    }

    /**
     * Дешифрует переданную строку с помощью переданного приватного ключа.
     *
     * @param $data string
     * @param $privateKey string
     * @return string
     */
    public static function privateKeyDecrypt($data, $privateKey)
    {
        openssl_private_decrypt($data, $secureData, $privateKey);

        return $secureData;
    }

    public function getPublicKey()
    {
        return $this->publicKey;
    }

    public function getPrivateKey()
    {
        return $this->privateKey;
    }

    /**
     * Генерация нового секретного ключа
     * @param $config array
     * @return false|resource
     */
    private function pkeyNew($config)
    {
        return openssl_pkey_new($config);
    }

    private function generatePublicKey()
    {
        $details = openssl_pkey_get_details($this->pkeyNew);
        $this->publicKey = $details['key'];
    }

    private function generatePrivateKey()
    {
        openssl_pkey_export($this->pkeyNew, $this->privateKey);
    }
}