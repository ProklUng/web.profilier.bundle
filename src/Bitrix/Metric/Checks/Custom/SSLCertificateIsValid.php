<?php

namespace Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Custom;

use Prokl\WebProfilierBundle\Bitrix\Metric\Checks\Check;
use Exception;

class SSLCertificateIsValid extends Check
{
    /**
     * @var string
     */
    protected $domain;

    /**
     * @var int
     */
    protected $days;

    public function __construct($domain, $days = 7)
    {
        $this->domain = $domain;
        $this->days = $days;
    }

    /**
     * @return string
     */
    public function name()
    {
        return "Проверка валидности SSL сертификата...";
    }

    /**
     * @return boolean
     */
    public function run()
    {
        $certificate = $this->downloadCertificate($this->domain);
        if (!$certificate) {
            $this->logError('Не удалось получить сертификат для ' . $this->domain);
            return false;
        }

        if (empty($certificate['validTo_time_t'])) {
            $this->logError('Не удалось получить дату окончания валидности сертификата');
            return false;
        }

        if ($certificate['validTo_time_t'] < time() + $this->days * 24 * 60 * 60) {
            $error = sprintf(
                'Сертификат для %s истечёт в течение следующих %s дней, а именно: %s',
                $this->domain,
                $this->days,
                date('d.m.Y', $certificate['validTo_time_t'])
            );
            $this->logError($error);
            return false;
        }

        return true;
    }

    /**
     * Download ssl certificate for domain.
     *
     * @param string $domain
     * @return array
     */
    protected function downloadCertificate($domain)
    {
        if (!function_exists('openssl_x509_parse')) {
            $this->skip('Функция openssl_x509_parse не объявлена');
        }

        $sslOptions = [
            'capture_peer_cert' => true,
            'SNI_enabled' => true,
            'verify_peer' => true,
            'verify_peer_name' => true,
        ];
        $streamContext = stream_context_create([
            'ssl' => $sslOptions,
        ]);
        try {
            $client = stream_socket_client(
                "ssl://{$domain}:443",
                $errorNumber,
                $errorDescription,
                10,
                STREAM_CLIENT_CONNECT,
                $streamContext
            );

            if ($client === false && $errorNumber == 0) {
                // Socket initialization problems
                throw new Exception('The resource (stream_socket_client) has not been created');
            }
        } catch (Exception $e) {
            $this->logError($e->getMessage());
            return [];
        }

        if ($errorNumber && $errorDescription) {
            $this->logError('stream_socket_client error ' . $errorNumber . ': ' . $errorDescription);
        }

        $response = stream_context_get_params($client);
        fclose($client);

        return !empty($response['options']['ssl']['peer_certificate'])
            ? openssl_x509_parse($response['options']['ssl']['peer_certificate']) : [];
    }
}
