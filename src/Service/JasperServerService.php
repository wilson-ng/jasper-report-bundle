<?php
declare(strict_types=1);

/**
 * This file is part of the JasperReportBundle.
 *
 * (c) Wilson Ng <frozalid.wilson@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace WilsonNg\JasperReportBundle\Service;

use Jaspersoft\Client\Client;
use Symfony\Component\HttpFoundation\Response;

/**
 * The Jasper Client Service.
 *
 * @author Wilson Ng <frozalid.wilson@gmail.com>
 */
final class JasperServerService
{

    /**
     * Scheme of jasper server.
     *
     * @example http | https
     *
     * @var string
     */
    private $scheme;

    /**
     * Host of jasper server.
     *
     * @var string
     */
    private $host;

    /**
     * Base url of jasper server.
     *
     * @var string
     */
    private $baseUrl;

    /**
     * Port of jasper server.
     *
     * @var string
     */
    private $port;

    /**
     * Request timeout of jasper server.
     *
     * @var int
     */
    private $timeout;

    /**
     * Username of jasper server user.
     *
     * @var string
     */
    private $username;

    /**
     * Password of jasper server user.
     *
     * @var string
     */
    private $password;

    /**
     * Organization of jasper server user.
     *
     * @var string
     */
    private $organization;

    /**
     * @var Client
     */
    private $client;

    public function connect()
    {
        if (@fsockopen($this->getHost(), (int) $this->getPort())) {
            $serverUrl = sprintf('%s://%s:%s/%s', $this->getScheme(), $this->getHost(), $this->getPort(), $this->getBaseUrl());
            $client = new Client($serverUrl, $this->getUsername(), $this->getPassword(), $this->getOrganization());
            $client->setRequestTimeout($this->getTimeout());
            $this->setClient($client);

            return true;
        }

        return false;
    }

    public function generate(string $reportUnit, array $parameters = [], string $filename = 'Report', string $format = 'pdf', int $page = 1): Response
    {
        if (!$this->connect()) {
            return new Response('Not connect to jasper server.');
        }

        $response = new Response();
        $reportService = $this->getClient()->reportService();

        switch ($format) {
            case 'html':
                $response->setContent($reportService->runReport($reportUnit, $format, $page, null, $parameters));
                break;
            case 'xml':
            case 'pdf':
            case 'xlsx':
            case 'xls':
            case 'rtf':
            case 'csv':
            case 'odt':
            case 'docx':
            case 'ods':
            case 'pptx':
                $response->setContent($reportService->runReport($reportUnit, $format, null, null, $parameters));
                break;
            default:
                $response->setContent("Sorry file format ".$format." is not supported.");
                break;
        }

        $response->headers->set('Cache-Control', 'must-revalidate');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Content-Description', '');
        $response->headers->set('Content-Disposition', 'inline; filename='.$filename.'.'.$format);
        $response->headers->set('Content-Transfer-Encoding', 'binary');
        $response->headers->set('Content-Length', strlen($response->getContent()));
        $response->headers->set('Content-Type', 'application/'.$format);

        return $response;
    }

    /**
     * @return string
     */
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param string $scheme
     * 
     * @return JasperServerService
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     * 
     * @return JasperServerService
     */
    public function setHost($host)
    {
        $this->host = $host;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     * 
     * @return JasperServerService
     */
    public function setBaseUrl($baseUrl)
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param string $port
     * 
     * @return JasperServerService
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param string $username
     * 
     * @return JasperServerService
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * 
     * @return JasperServerService
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     * 
     * @return JasperServerService
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param string $organization
     * 
     * @return JasperServerService
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
        return $this;
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * 
     * @return JasperServerService
     */
    public function setClient(Client $client)
    {
        $this->client = $client;
        return $this;
    }
}
